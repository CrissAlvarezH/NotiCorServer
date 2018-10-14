<?php 
require_once 'datos/conexion.php';
require_once 'utils/constantes.php';

class NotificacionModel {


    private static function enviarNoti($noti){
        $curl = curl_init();

        curl_setopt_array($curl, array(
                CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => '{
                    "to" : "/topics/'.$noti['destinatario'].'",
                    "data" : {
                        "idNoti"  :  "'. $noti['id'] .'",
                        "tituloNoti"  :  "'. $noti['titulo'] .'",
                        "descripcionNoti" : "'. $noti['descripcion'] .'",
                        "fechaNoti" : "'. $noti['fecha'] .'",
                        "horaNoti" : "'. $noti['hora'] .'",
                        "tipoNoti": "'.$noti['tipo'].'"
                    },
                    "priority": "high"
                }',
                CURLOPT_HTTPHEADER => array(
                    "Authorization: key=AIzaSyDJNVzLTUhAVTS2p7CDAjuvMj2-l12P5ks",
                    "Content-Type: application/json",
                    "cache-control: no-cache"
                ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return false;
        } else {
            return true;
        }
    }

    public static function insertar($notificacion) {
        $con = Conexion::getInstancia()->getConexion();

        $sentencia = $con->prepare(
            'INSERT INTO '.TABLA_NOTIFICACIONES.' VALUES (null, :titulo, :descripcion, :tipo, :fecha, :hora, :destinatario);'
        );

        $sentencia->bindParam('titulo', $notificacion['titulo']);
        $sentencia->bindParam('descripcion', $notificacion['descripcion']);
        $sentencia->bindParam('tipo', $notificacion['tipo']);
        $sentencia->bindParam('fecha', $notificacion['fecha']);
        $sentencia->bindParam('hora', $notificacion['hora']);
        $sentencia->bindParam('destinatario', $notificacion['destinatario']);

        if( $sentencia->execute() ){
            $id = $con->lastInsertId();

            $notificacion['id'] = $id;

            if( self::enviarNoti($notificacion) ){
                return [
                    'okay' => true,
                    'respuesta' =>  $id
                ];
            }else{
                return [
                    'okay' => true,
                    'respuesta' =>  $id,
                    'error' => 'noti_no_enviada'
                ];
            }
            
        }else{
            return [
                'okay' => false,
                'respuesta' => 'Notificacion NO insertada'
            ];
        }
    }

    public static function getTodas() {
        $con = Conexion::getInstancia()->getConexion();

        $sentencia = $con->prepare(
            'SELECT * FROM ' . TABLA_NOTIFICACIONES . ' ORDER BY ' . ID . ' DESC'
        );

        if( $sentencia->execute() ){
            return [
                'okay' => true,
                'respuesta' => $sentencia->fetchAll(PDO::FETCH_ASSOC)
            ];
        }else{
            return [
                'okay' => false,
                'respuesta' => 'Error al ejecutar sentencia'
            ];
        }
    }

    public static function getUna($id) {
        $con = Conexion::getInstancia()->getConexion();

        $sentencia = $con->prepare(
            'SELECT * FROM ' . TABLA_NOTIFICACIONES . ' WHERE ' . ID . ' = :id '
        );

        $sentencia->bindParam('id', $id);

        if( $sentencia->execute() ){

            if( $sentencia->rowCount() > 0 ){
                return [
                    'okay' => true,
                    'respuesta' => $sentencia->fetch(PDO::FETCH_ASSOC)
                ];
            }else{
                return [
                    'okay' => false,
                    'respuesta' => 'No existe una notificacion con ese id'
                ];
            }
        }
            
        return [
            'okay' => false,
            'respuesta' => 'Error al ejecutar sentencia'
        ];
    }

}

?>