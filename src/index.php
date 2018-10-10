<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require_once 'rutas/notificaciones.router.php';

$app = new \Slim\App();

$app->group('/notificaciones', $grupoRutasNotificaciones);

$app->run();

?>