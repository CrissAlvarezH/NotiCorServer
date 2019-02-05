<?php 
require_once 'datos/conexion.php';
require_once 'modelos/base.model.php';
require_once 'utils/constantes.php';

class NotificacionModel {

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

            if( BaseModel::enviarNoti('notificacion', $notificacion, $notificacion['destinatario']) ){
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

    public static function eliminarUna($id) {
        $con = Conexion::getInstancia()->getConexion();

        $sent = $con->prepare(
            'DELETE FROM '. TABLA_NOTIFICACIONES .' WHERE '.ID.' = :id '
        );

        $sent->bindParam('id', $id);

        if( $sent->execute() ){
            return [
                'okay' => true
            ];
        }else{
            return [
                'okay' => false
            ];
        }
    }

}

?>