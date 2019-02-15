<?php 
require_once 'datos/conexion.php';
require_once 'utils/constantes.php';

class UsuariosModelo {

    public static function insertarProfesor($profesor) {
        $con = Conexion::getInstancia()->getConexion();

        $sent = $con->prepare(
            "INSERT INTO ".TABLA_USUARIOS." VALUES (null, :usuario, :pass, :nombres, :apellidos, 'PROFESOR') "  
        );

        $sent->bindParam('usuario', $profesor['usuario']);
        $sent->bindParam('pass', $profesor['pass']);
        $sent->bindParam('nombres', $profesor['nombres']);
        $sent->bindParam('apellidos', $profesor['apellidos']);

        if ( $sent->execute() ) {
            $id = $con->lastInsertId();

            $sentCrr = $con->prepare(
                'INSERT INTO '.TABLA_PROFESOR_CARRERA.' VALUES (:id_profesor, : id_carrera) '
            );

            $sentCrr->bindParam('id_profesor', $id);
            $sentCrr->bindParam('id_carrera', $profesor['idCarrera']);

            if ( $sentCrr->execute() ) {


                return $id;
            }
        }

        return false;
    }

    public static function login($usuario, $pass) {
        $con = Conexion::getInstancia()->getConexion();

        $sent = $con->prepare(
            "SELECT * FROM ".TABLA_USUARIOS." WHERE ".USUARIO." = :usuario AND ".PASS." = :pass "
        );

        $sent->bindParam('usuario', $usuario);
        $sent->bindParam('pass', $pass);

        if ( $sent->execute() ) {

            if ( $sent->rowCount() > 0 ) {
                
                $resultado = $sent->fetchAll(PDO::FETCH_ASSOC)[0];

                switch ( $resultado['rol'] ) {
                    case 'PROFESOR':
                        // Buscamos la carrera que tiene relacionada el profesor
                        $sentCarrera = $con->prepare(
                            "SELECT c.* FROM ".TABLA_PROFESOR_CARRERA." pc, ".TABLA_CARRERAS." c WHERE pc.id_carrera = c.id AND pc.id_profesor = :id_profesor "
                        );

                        $sentCarrera->bindParam('id_profesor', $resultado['id']);

                        if ( $sentCarrera->execute() ) {

                            if ( $sentCarrera->rowCount() > 0 ){

                                $idCarrera = $sentCarrera->fetchAll(PDO::FETCH_ASSOC)[0]['id'];

                                $resultado['idCarrera'] = $idCarrera;
                            } else {
                                $resultado['idCarrera'] = -1;
                            }
                        }
                        
                        return $resultado;

                        break;
                    case 'PUBLICADOR':

                        break;
                }
            }
        }

        return false;
    }
}

?>
