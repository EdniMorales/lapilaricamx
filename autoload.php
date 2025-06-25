<?php
function controllers_autoload($classname) {
    //echo $classname;
    if ($classname == '/' || empty($classname)) {
        // Si no se proporciona ninguna clase, incluir un controlador por defecto
        include 'controllers/PrincipalController.php';
    } else {
        $ruta = 'controllers/' . $classname . '.php';
        if (file_exists($ruta)) {
            include $ruta;
        } else {
            // Opcional: incluir un controlador de errores o manejar la ausencia
            include 'controllers/ErrorController.php';
        }
    }
}
spl_autoload_register('controllers_autoload');