<?php

require_once 'autoload.php';
require_once 'config/parameters.php';

// Redirige si no hay controlador ni acción
if (!isset($_GET['controller']) && !isset($_GET['action'])) {
    header('Location: ' . base_url . 'Principal/index');
    exit();
}

// se coloca aqui por que si no no redirige
require_once 'views/layout/head.php';  // cabecera de la pagina

function show_error(){
    $error = new ErrorController();
    $error->index();
}

if(isset($_GET['controller'])){
    $nombreControlador = $_GET['controller'].'Controller';
    
}elseif(!isset($_GET['controller'])  && !isset($_GET['action'])){
    $nombreControlador = controller_default;
}else{
    show_error();
    exit();
}
// comprobar si existe un controlador
if(class_exists($nombreControlador)){
    $controlador = new $nombreControlador();
    
    if(isset($_GET['action']) && method_exists($controlador, $_GET['action'])){
        $action = $_GET['action'];
        $controlador->$action();
    }elseif(!isset($_GET['controller'])  && !isset($_GET['action'])){
        $action_default = action_default;
        $controlador->$action_default();
    }else{
        show_error();
    }
}else{
    show_error();
}

require_once 'views/layout/footer.php';
?>