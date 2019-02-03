<?php

class Conexion {
    // Constantes para crear la conexion con PDO
    const HOST = "localhost";
    const BASE_DE_DATOS = "ubiety"; 
    const USUARIO = "root"; 
    const PASS = "guitarra1"; 

    private static $conexion;// conexión con base de datos
    private static $instancia; // Instancia de esta clase SINGLETON

    private final function __construct() {
        try{

        }catch(PDOException $exc){
            // Error en conexión
        }
    }

    public static function getInstancia(){
        if( self::$instancia == null ){
            self::$instancia = new Conexion();
        }

        return self::$instancia;
    }

    public static function getConexion() {
        if( self::$conexion == null ){
            self::$conexion = new PDO(
                'mysql:dbname=' . self::BASE_DE_DATOS . ';host=' . self::HOST,
                self::USUARIO,
                self::PASS
            );

            self::$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$conexion->exec("set names utf8");
        }

        return self::$conexion;
    }
}

?>