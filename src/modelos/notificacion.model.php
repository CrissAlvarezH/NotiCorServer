<?php 
require_once 'datos/conexion.php';
require_once 'utils/constantes.php';

class NotificacionModel {

    public static function insertar($notificacion) {
        $con = Conexion::getInstancia()->getConexion();

        $sentencia = $con->prepare(
            'INSERT INTO '.TABLA_NOTIFICACIONES.' VALUES (null, :titulo, :descripcion, :tipo, :fecha, :hora);'
        );

        $sentencia->bindParam('titulo', $notificacion->titulo);
        $sentencia->bindParam('descripcion', $notificacion->descripcion);
        $sentencia->bindParam('tipo', $notificacion->tipo);
        $sentencia->bindParam('fecha', $notificacion->fecha);
        $sentencia->bindParam('hora', $notificacion->hora);

        if( $sentencia->execute() ){
            return [
                'okay' => true,
                'respuesta' => $notificacion
            ];
        }else{
            return [
                'okay' => false,
                'respuesta' => 'Notificacion NO insertada'
            ];
        }
    }

}

?>