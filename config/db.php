<?php
/* xd
 * creamos la conexion a la base de datos en una clase estatica
 */
class Database{
    public static function connect(){
        // Credenciales de la base de datos
        $servername ="";
        $username ="";
        $password ="";
        $dbname = "";
        $port =3306;
        
        // Crear la conexión
        $conexion = new mysqli($servername, $username, $password, $dbname, $port);
        // Establecer la codificación de caracteres de la conexión a UTF-8
        $conexion->set_charset("utf8mb4");
        // Retornar la conexion
        return $conexion;
    }
}

