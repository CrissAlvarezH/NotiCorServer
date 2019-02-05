<?php
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
header("Access-Control-Allow-Origin: *");

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require_once 'rutas/notificaciones.router.php';
require_once 'rutas/base.router.php';
require_once 'rutas/noticias.router.php';

$app = new \Slim\App();

// Tomamos la ruta aquí para tener de referencia el index.php (donde estamos)
// y lo guardamos en las variables del contenedor para acceder a ellas con $app->get('<variable>')
$contenedor = $app->getContainer();
$contenedor['ruta_img_noticias'] =  __DIR__ . '/uploads/noticias';

$app->group('/notificaciones', $grupoRutasNotificaciones);
$app->group('/base', $grupoRutasBase);
$app->group('/noticias', $grupoRutasNoticias);

$app->run();

?>