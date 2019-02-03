<?php

require_once 'modelos/base.model.php';

$grupoRutasBase = function () {

    // [ INICIO ] RUTAS GET
    $this->get('/info-inicial', function ($req, $res, $args) {
        $resp = BaseModel::getInfoInicio();

        $resp['okay'] = true;

        $res->getBody()->write( json_encode( $resp ) );

        return $res;
    });
    // [ FIN ] RUTAS GET

}

?>