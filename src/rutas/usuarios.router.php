<?php
require_once 'modelos/usuarios.model.php';

$grupoRutasUsuarios = function () {

    // [ INICIO ] RUTAS POST
    $this->post('/login', function ($req, $res, $args) {
        $body = $req->getParsedBody();

        $resp = UsuariosModelo::login($body['usuario'], $body['pass']);

        if ( $resp ) {
            $rol = $resp['rol'];

            $res->getBody()->write( json_encode(
                [
                    'okay' => true,
                    'mensaje' => $rol, // Para que la app cliente sepa el rol del usuario logueado
                    strtolower($rol) => $resp // El rol es el key del json
                ]
            ));
        } else {
            $res->getBody()->write( json_encode(
                [
                    'okay' => false
                ]
            ));
        }

        return $res;
    });

    // [ FIN ] RUTAS POST

}

?>