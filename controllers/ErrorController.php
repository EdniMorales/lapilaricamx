<?php

class ErrorController{
    public function __construct()
    {
        /* validamos si extiste a sesison */
    /*     if(!isset($_SESSION['usuario'])){Utls::sinSession();}
        if($_SESSION['usuario']["passwd"] == 1){
            $_SESSION["changePswInicio"] = "DEBES CAMBIAR TU CONTRASEÑA PARA USAR EL SISTEMA";
            echo '<script>window.location="'.base_url.'Doctor/editar&id='.SED::encryption($_SESSION['usuario']["id"]).'"</script>';
        }*/
    } 
    /*este es una clase de prueba para saber que todo esta bien relacionado */
    public function index(){
        require_once 'views/error/error404.php';
    }

    public function update(){
        require_once 'views/error/errorUpdate.php';
    }
}
