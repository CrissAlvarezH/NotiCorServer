<?php
require_once 'modelos/noticias.model.php';

$grupoRutasNoticias = function () {

    // [ INICIO ] RUTAS GET
    $this->get('', function ($req, $res, $args) {
        $resp = NoticiasModel::getNoticias(false);

        $res->getBody()->write(
            json_encode(
                [
                    'okay' => true,
                    'noticias' => $resp
                ]
            )
        );

        return $res;
    });

    $this->get('/{id_carrera}', function ($req, $res, $args) {
        $resp = NoticiasModel::getNoticias( $args['id_carrera'] );

        $res->getBody()->write( json_encode(
            [
                'okay' => true,
                'noticias' => $resp
            ]
        ));

        return $res;
    });
    // [ FIN ] RUTAS GET

    // [ INICIO ] RUTAS POST
    $this->post('', function ($req, $res, $args) {
        $resp = NoticiasModel::insertarNoticia( $req->getParsedBody() );

        $res->getBody()->write( json_encode( [ 'okay' => $resp ] ) );

        return $res;
    });
    // [ FIN ] RUTAS POST

}

?>