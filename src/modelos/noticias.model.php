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

    public static function insertarNoticia($noticia) {
        $con = Conexion::getInstancia()->getConexion();

        $sent = $con->prepare(
            'INSERT INTO '.TABLA_NOTICIAS.' VALUES (null, :url_img, :titulo, :descripcion, :tipo, :fecha, :enlace, :id_carrera)'
        );

        $sent->bindParam('url_img', $noticia['urlImagen']);
        $sent->bindParam('titulo', $noticia['titulo']);
        $sent->bindParam('descripcion', $noticia['descripcion']);
        $sent->bindParam('tipo', $noticia['tipo']);
        $sent->bindParam('fecha', $noticia['fecha']);
        $sent->bindParam('enlace', $noticia['enlace']);
        $sent->bindParam('id_carrera', $noticia['idCarrera']);

        if ( $sent->execute() ) {
            $id = $con->lastInsertId();

            return $id;
        } else {
            return false;
        }
    }
}

?>