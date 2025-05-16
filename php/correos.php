<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Importar las clases necesarias de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Cargar el autoload de Composer
require __DIR__ . '/../vendor/autoload.php';

// Función para configurar PHPMailer con SMTP
function configurarSMTP(PHPMailer $mail) {
    // Codificar los caracteres en el formato estándar
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';

    // Cargar las variables de entorno desde el archivo .env
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__, '/../backend/settings.env');
    $dotenv->load();

    // Validar que las variables de entorno necesarias estén configuradas
    if (empty($_ENV['CORREO_HOST']) || empty($_ENV['CORREO_USER']) || empty($_ENV['CORREO_PASS']) || empty($_ENV['CORREO_SMTPS']) || empty($_ENV['CORREO_PORT'])) {
        throw new Exception("Faltan variables de entorno necesarias para configurar SMTP.");
    }

    // Configuración del servidor SMTP
    $mail->isSMTP();
    $mail->Host = $_ENV['CORREO_HOST'];  // Configuracion del servidor SMTP

    // Validar que SMTPAuth esté configurado correctamente
    $smtpAuth = filter_var($_ENV['CORREO_SMTPA'], FILTER_VALIDATE_BOOLEAN);
    $mail->SMTPAuth = $smtpAuth;
    
    $mail->Username = $_ENV['CORREO_USER'];  // Usuario SMTP
    $mail->Password = $_ENV['CORREO_PASS'];  // Contraseña SMTP
    $mail->SMTPSecure = $_ENV['CORREO_SMTPS'];  // Seguridad del servidor SMTP
    $mail->Port = (int)$_ENV['CORREO_PORT'];  // Puerto del servidor SMTP
}

// funcion para enviar un correo de verificacion de su mensaje al usuario
function enviarCorreoAlUsuario($email, $nombre){
    try {
        // Crear una instancia de PHPMailer
        $mail = new PHPMailer(true);

        // Configurar SMTP
        configurarSMTP($mail);

        // Configuración del correo
        $mail->setFrom($_ENV['CORREO_USER'], 'Lacteos La Pilarica');
        $mail->addAddress($email, $nombre);  // Correo del usuario

        // Contenido del correo en HTML
        $mail->isHTML(true);  // Usar HTML
        $mail->Subject = 'Hemos recibido tu mensaje';
        $mail->Body = "
            <html>
                <head>
                    <style>
                        body { font-family: Arial, sans-serif; }
                        .mensaje { color: #333; }
                    </style>
                </head>
                <body>
                    <h3>Hola $nombre,</h3>
                    <p class='mensaje'>Gracias por contactarnos. Tu mensaje ha sido recibido, tabajaremos para mejorar
                    y ofrcerte la mejor calidad en nuestros productos.</p>
                    <p class='mensaje'>Atentamente,</p>
                    <p class='mensaje'>Lacteos La Pilarica.</p>
                </body>
            </html>
        ";

        // Contenido alternativo en texto plano (para clientes que no soportan HTML)
        $mail->AltBody = "Hola $nombre,\n\nGracias por contactarnos. Tu mensaje ha sido recibido, trabajaremos para mejorar y ofrecerte la mejor calidad en nuestros productos.\n\nAtentamente,\nLácteos La Pilarica.";

        // Enviar el correo
        $mail->send();
        return ['success' => true, 'message' => 'Correo enviado correctamente'];
    } catch (Exception $e) {
        return ['success' => false, 'message' => "El correo no pudo enviarse. Error: {$mail->ErrorInfo}"];
    }
}

// funcion para enviar un correo con el mensaje del formulario
function enviarCorreoSoporte($form){
    // Lista de correos adicionales a los que también se les enviará el correo
    $correosAdicionales = [
        'isaacmonted072@gmail.com',
        'rimora.29@gmail.com'
    ];

    try {
        // Crear una instancia de PHPMailer
        $mail = new PHPMailer(true);

        // Configurar SMTP
        configurarSMTP($mail);

        // Configuración del correo
        $mail->setFrom($_ENV['CORREO_USER'], 'Lacteos La Pilarica');

        // Añadir los correos adicionales
        foreach ($correosAdicionales as $correoAdicional) {
            $mail->addAddress($correoAdicional,'Soporte');  // Agregar destinatarios adicionales
        }

        // Contenido del correo en HTML
        $mail->isHTML(true);  // Usar HTML
        $mail->Subject = 'Nuevo mensaje de formulario - ' . $form['TipoFormQS'];
        $mail->Body = "
            <html>
                <head>
                    <style>
                        body { font-family: Arial, sans-serif; }
                        .mensaje { color: #333; }
                        .detalle { color: #555; }
                    </style>
                </head>
                <body>
                    <h3>Nuevo mensaje de {$form['TipoFormQS']}</h3>
                    <p class='detalle'><strong>Nombre:</strong> {$form['NombreFormQS']} {$form['ApellidoFormQS']}</p>
                    <p class='detalle'><strong>Email:</strong> {$form['EmailFormQS']}</p>
                    <p class='delalle'><strong>Direccion</strong> {$form['DireccionFormQS']}</p>
                    <p class='detalle'><strong>Teléfono:</strong> {$form['TelFormQS']}</p>
                    <p class='detalle'><strong>Empresa:</strong> {$form['EmpresaFormQS']}</p>
                    <p class='detalle'><strong>Mensaje:</strong><br>{$form['MensajeFormQS']}</p>
                </body>
            </html>
        ";

        // Contenido alternativo en texto plano (para clientes que no soportan HTML)
        $mail->AltBody = "Nuevo mensaje de {$form['TipoFormQS']}\n\n"
            . "Nombre: {$form['NombreFormQS']} {$form['ApellidoFormQS']}\n"
            . "Email: {$form['EmailFormQS']}\n"
            . "Dirección: {$form['DireccionFormQS']}\n"
            . "Teléfono: {$form['TelFormQS']}\n"
            . "Empresa: {$form['EmpresaFormQS']}\n"
            . "Mensaje:\n{$form['MensajeFormQS']}";

        // Enviar el correo
        $mail->send();
        return ['success' => true, 'message' => 'Correo enviado correctamente'];
    } catch (Exception $e) {
        return ['success' => false, 'message' => "El correo no pudo enviarse. Error: {$mail->ErrorInfo}"];
    }
}

// funcion para informar que se ha suscrito a la pagina
function enviarCorreoAlUsuarioSuscripcion($email, $nombre='', $apellido=''){
     try {
        // Crear una instancia de PHPMailer
        $mail = new PHPMailer(true);

        // Configurar SMTP
        configurarSMTP($mail);

        // Configuración del correo
        $mail->setFrom($_ENV['CORREO_USER'], 'Lacteos La Pilarica');
        $mail->addAddress($email, $nombre);  // Correo del usuario

        // Contenido del correo en HTML
        $mail->isHTML(true);  // Usar HTML
        $mail->Subject = 'Gracias por unirte a nuestra familia';
        $mail->Body = "
            <html>
                <head>
                    <style>
                        body { font-family: Arial, sans-serif; }
                        .mensaje { color: #333; }
                    </style>
                </head>
                <body>
                    <h3>¡Hola {$nombre} {$apellido}!</h3>
                    <p class='mensaje'>Gracias por suscribirte a nuestro boletín de noticias. Ahora recibirás nuestras promociones y novedades.</p>
                    <p class='mensaje'>¡Bienvenido!</p>
                </body>
            </html>
        ";

        $mail->AltBody = "¡Hola {$nombre} {$apellido}!\n\nGracias por suscribirte a nuestro boletín de noticias. Ahora recibirás nuestras promociones y novedades.\n\n¡Bienvenido!";

         // Enviar el correo
        $mail->send();
        return ['success' => true, 'message' => 'Correo enviado correctamente'];
    } catch (Exception $e) {
        return ['success' => false, 'message' => "El correo no pudo enviarse. Error: {$mail->ErrorInfo}"];
    }
}


// ================================= ENRUTAMIENTO =================================
if (isset($_GET['action'])) {
    $action = $_GET['action'];

    // Definir un array para almacenar las respuestas
    $data = [];

    // Dependiendo de la acción solicitada, ejecutar la función correspondiente
    switch ($action) {
        case 'correoUsuario':
            if (isset($_POST['email'], $_POST['nombre'])) {
                $data = enviarCorreoAlUsuario($_POST['email'], $_POST['nombre']);
            } else {
                $data = ['error' => 'Faltan datos: email o nombre'];
            }
            break;

        case 'correoSoporte':
            if (!empty($_POST)) {
                $data = enviarCorreoSoporte($_POST);
            } else {
                $data = ['error' => 'Faltan datos del formulario'];
            }
            break;

        case 'correoSuscripcion':
            if (isset($_POST['email'], $_POST['nombre'], $_POST['apellido'])) {
                $data = enviarCorreoAlUsuarioSuscripcion($_POST['email'], $_POST['nombre'], $_POST['apellido']);
            } else {
                $data = ['error' => 'Faltan datos: email o nombre'];
            }
            break;

        default:
            // Si la acción no es válida, se devuelve un error
            $data = ["error" => "Accion no valida"];
            break;
    }

    // Establecer el tipo de contenido como JSON
    header('Content-Type: application/json');

    // Si no hay datos, devolver un mensaje adecuado
    if (empty($data)) {
        $data = ["error" => "No hay datos disponibles."];
    }
    // Devolver los datos en formato JSON
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    } else {
        // Si no se pasa ninguna acción, devolver un error
        echo json_encode(["error" => "Falta la acción en la solicitud"], JSON_UNESCAPED_UNICODE);
}

?>