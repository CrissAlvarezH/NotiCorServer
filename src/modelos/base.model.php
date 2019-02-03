<?php
require_once 'datos/conexion.php';
require_once 'utils/constantes.php';

class BaseModel {

    /**
     * Este metodo devuelve la información inicial que necesita cargar la app
     * cuando apenas se abre, que es la información que va a mostrar en las primeras
     * vistas de las tabs
     */
    public static function getInfoInicio() {
        $con = Conexion::getInstancia()->getConexion();
        
        // Consultamos las facultades
        $sentFacultades = $con->prepare(
            'SELECT '.ID.','.NOMBRE.' FROM '.TABLA_FACULTADES
        );

        $resFacultades = [];

        if ( $sentFacultades->execute() ) {
            $resFacultades = $sentFacultades->fetchAll(PDO::FETCH_ASSOC);
        }

        // Consultamos las carreras
        $sentCarreras = $con->prepare(
            'SELECT '.ID.','.NOMBRE.','.ID_FACULTAD.' AS idFacultad FROM '.TABLA_CARRERAS
        );

        $resCarreras = [];

        if ( $sentCarreras->execute() ) {
            $resCarreras = $sentCarreras->fetchAll(PDO::FETCH_ASSOC);
        }

        // Obtenemos las ultimas 10 notificaciones
        $sentNotificaciones = $con->prepare(
            'SELECT * FROM '.TABLA_NOTIFICACIONES.' ORDER BY '.FECHA.' DESC, '.HORA.' DESC LIMIT 10'
        );

        $resNotificaciones = [];

        if ( $sentNotificaciones->execute() ) {
            $resNotificaciones = $sentNotificaciones->fetchAll(PDO::FETCH_ASSOC);
        }

        // Obtenemos las ultimas 5 noticias
        $sentNoticias = $con->prepare(
            'SELECT *, '.URL_IMAGEN.' AS urlImagen, '.ID_CARRERA.' AS idCarrera FROM '.TABLA_NOTICIAS.' ORDER BY '.FECHA.' DESC'
        );

        $resNoticias = [];

        if ( $sentNoticias->execute() ) {
            $resNoticias = $sentNoticias->fetchAll(PDO::FETCH_ASSOC);
        }

        return [
            'facultades' => $resFacultades,
            'carreras' => $resCarreras,
            'notificaciones' => $resNotificaciones,
            'noticias' => $resNoticias
        ];
    }

}

?>