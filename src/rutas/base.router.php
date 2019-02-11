<?php

require_once 'modelos/base.model.php';

$grupoRutasBase = function () {

    // [ INICIO ] RUTAS GET
    $this->get('/info-inicial/{rol}', function ($req, $res, $args) {
        $resp = BaseModel::getInfoInicio( $args['rol'] );

        $resp['okay'] = true;

        $res->getBody()->write( json_encode( $resp ) );

        return $res;
    });
    // [ FIN ] RUTAS GET

    // [ INICIO ] RUTAS POST
    $this->post('/login', function($req, $res, $args) {
        $postParams = $req->getParsedBody();

        $resp = BaseModel::verificarCredenciales( $postParams['usuario'], $postParams['pass'] );

        if ($resp) {
            $res->getBody()->write( 
                json_encode(
                    [
                        'okay' => true,
                        'respuesta' => $resp
                    ]
                )
            );
        } else {
            $res->getBody()->write(
                json_encode(
                    [
                        'okay' => false
                    ]
                )
                    );
        }

        return $res;
    });
    // [ FIN ] RUTAS POST

}

?>