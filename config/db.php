<?php
/* 
 * creamos la conexion a la base de datos en una clase estatica
 */
class Database{
    public static function connect(){
        // Credenciales de la base de datos
        $servername ="107.161.179.69";
        $username ="fvyvvdbc_fvyvvdbc";
        $password ="LaPilarica24#";
        $dbname = "fvyvvdbc_pilaweb";
        $port =3306;

        // Crear la conexión
        $conexion = new mysqli($servername, $username, $password, $dbname, $port);
        // Establecer la codificación de caracteres de la conexión a UTF-8
        $conexion->set_charset("utf8mb4");
        // Retornar la conexion
        return $conexion;
    }
}

