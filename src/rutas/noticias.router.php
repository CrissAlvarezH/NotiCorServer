<?php
require_once 'modelos/noticias.model.php';
require_once 'utils/constantes.php';

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
        // Insertamos los datos y tomamos el id para guardar la imagen con ese nombre
        $id = NoticiasModel::insertarNoticia( $req->getParsedBody() );

        if ( $id ) {
            $ruta = $this->get('ruta_img_noticias');// Obtenemos la ruta definida en index.php
            $uploadedFiles = $req->getUploadedFiles();

            // Pedimos el archivo que se ha subido con el name dado en el form url encode
            $uploadedFile = $uploadedFiles['imagen'];
            if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
                // Guardamos la imagen cuyo nombre es el id de la noticia
                $filename = $id . '.jpg';
                $uploadedFile->moveTo($ruta . DIRECTORY_SEPARATOR . $filename);
            }

            $res->getBody()->write( json_encode( [ 'okay' => true, 'id' => $id ] ) );

        } else {
            $res->getBody()->write( json_encode( [ 'okay' => false ] ) );
        }

        return $res;
    });
    // [ FIN ] RUTAS POST

}

?>