<?php

/* 
 * creamos la conexion a la base de datos en una clase estatica
 */
class Database{
    public static function connect(){
        $conexion = new mysqli('localhost', 'root', '', 'ticdewem');
        $conexion->query("SET NAMES 'utf8'");
        return $conexion;
    }
}

