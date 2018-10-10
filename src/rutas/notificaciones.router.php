<?php

require_once 'modelos/notificacion.model.php';

$grupoRutasNotificaciones = function () {

    // [ INICIO ] RUTAS GET
    $this->get('', function ($req, $res, $args) {
        
        $res->getBody()->write( json_encode( NotificacionModel::getTodas() ) );

        return $res;
    });

    $this->get('/{id}', function ($req, $res, $args) {
        $id = $args['id'];

        $res->getBody()->write( json_encode( NotificacionModel::getUna($id) ) );

        return $res;
    });
    // [ FIN ] RUTAS GET

    // [ INICIO ] RUTAS POST
    $this->post('', function ($req, $res, $args) {

        $notificacion = $req->getParsedBody();

        $res->getBody()->write( json_encode( NotificacionModel::insertar($notificacion) ) );

        return $res;
    });
    // [ FIN ] RUTAS POST
}

?>