<?php
require_once 'datos/conexion.php';
require_once 'utils/constantes.php';

class NoticiasModel {

    public static function getNoticias($idCarrera) {
        $con = Conexion::getInstancia()->getConexion();

        if ( $idCarrera ) {
            $sent = $con->prepare(
                'SELECT * FROM '.TABLA_NOTICIAS.' WHERE '.ID_CARRERA.' = :id_carrera'
            );

            $sent->bindParam('id_carrera', $idCarrera);

            if ( $sent->execute() ) {
                return $sent->fetchAll(PDO::FETCH_ASSOC);
            } else {
                return [];
            }
        } else { // Ultimas 5 noticias

            $sentNoticias = $con->prepare(
                'SELECT *, '.URL_IMAGEN.' AS urlImagen, '.ID_CARRERA.' AS idCarrera FROM '.TABLA_NOTICIAS.' ORDER BY '.FECHA.' DESC LIMIT 5'
            );

            if ( $sentNoticias->execute() ) {
                return $sentNoticias->fetchAll(PDO::FETCH_ASSOC);
            }else {
                return [];
            }
        }
    }

}

?>