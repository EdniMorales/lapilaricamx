<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Cargar el autoload de Composer
require __DIR__ . '/../vendor/autoload.php';

require '../config/db.php';

// Crear conexion
$conn = Database::connect();

// verificar si no hubo errores de conexion
if ($conn->connect_error){
    die("Conexion fallida: " . $conn->connect_error);
}

// funcion para enviar un correo de verificacion de su mensaje al usuario
function enviarCorreoAlUsuario($email, $nombre){
    $asunto = "Hemos recibido tu mensaje";
    $mensaje = "Hola $nombre,\n\nGracias por contactarnos. Tu mensaje ha sido recibido correctamente.\n\nAtentamente,\nEquipo de soporte.";
    $headers = "From: soporte@tupagina.com";

    mail($email, $asunto, $mensaje, $headers);
}

// funcion para enviar un correo con el mensaje del formulario
function enviarCorreoSoporte($form){
    $asunto = "Nuevo mensaje de formulario - " . $form['TipoFormQS'];
    $mensaje = "Nombre: {$form['NombreFormQS']} {$form['ApellidoFormQS']}\n";
    $mensaje .= "Email: {$form['EmailFormQS']}\n";
    $mensaje .= "Teléfono: {$form['TelFormQS']}\n";
    $mensaje .= "Empresa: {$form['EmpresaFormQS']}\n";
    $mensaje .= "Tipo: {$form['TipoFormQS']}\n";
    $mensaje .= "Mensaje:\n{$form['MensajeFormQS']}\n";

    $headers = "From: no-reply@tupagina.com";
    mail("soporte@tupagina.com", $asunto, $mensaje, $headers);
}

// funcion para informar que se ha suscrito a la pagina
function enviarCorreoAlUsuarioSuscripcion($email){
    $asunto = "¡Gracias por suscribirte!";
    $mensaje = "Ahora recibirás nuestras promociones y noticias. ¡Bienvenido!";
    $headers = "From: newsletter@tupagina.com";

    mail($email, $asunto, $mensaje, $headers);
}

// ================================= COMENTARIOS =================================

// funcion para guardar la queja en la base de datos
function saveMessageOnDataBase($conn, $form, $archivoNombreFinal = null) {
    // Validación básica de los datos
    $nombre = htmlspecialchars($form['NOMBRE']);
    $apellido = htmlspecialchars($form['APELLIDO']);
    $correo = filter_var($form['CORREO'], FILTER_VALIDATE_EMAIL);
    if (!$correo) {
        throw new Exception("Correo inválido.");
    }

    $telefono = htmlspecialchars($form['TELEFONO']);
    $direccion = htmlspecialchars($form['DIRECCION']);
    $empresa = htmlspecialchars($form['EMPRESA']);
    $tipo = htmlspecialchars($form['TIPO']);
    $descripcion = htmlspecialchars($form['DESCRIPCION']);
    $estado = 'Pendiente'; // Asumiendo que por defecto es pendiente

    // Preparar la consulta SQL
    $sql = "INSERT INTO COMENTARIOS (NOMBRE, APELLIDO, CORREO, TELEFONO, DIRECCION, EMPRESA, TIPO, DESCRIPCION, ARCHIVO_ADJUNTO, ESTADO) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Error en la preparación de la consulta: " . $conn->error);
    }

    // Si no se subió un archivo, lo marcamos como 'undefined'
    $archivo_adjunto = $archivoNombreFinal ?? 'undefined';

    // Vincular los parámetros de la consulta
    $stmt->bind_param("ssssssssss", $nombre, $apellido, $correo, $telefono, $direccion, $empresa, $tipo, $descripcion, $archivo_adjunto, $estado);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        return true;
    } else {
        throw new Exception("Error al guardar el mensaje: " . $stmt->error);
    }
}
// funcion para editar la queja en la base de datos
function editMessageOnDataBase($conn, $form, $id_comentario, $archivoNombreFinal = null) {
    // Validación de datos
    $nombre = htmlspecialchars($form['NOMBRE']);
    $apellido = htmlspecialchars($form['APELLIDO']);
    $correo = filter_var($form['CORREO'], FILTER_VALIDATE_EMAIL);
    if (!$correo) {
        throw new Exception("Correo inválido.");
    }

    $telefono = htmlspecialchars($form['TELEFONO']);
    $direccion = htmlspecialchars($form['DIRECCION']);
    $empresa = htmlspecialchars($form['EMPRESA']);
    $tipo = htmlspecialchars($form['TIPO']);
    $descripcion = htmlspecialchars($form['DESCRIPCION']);
    $estado = htmlspecialchars($form['ESTADO']);
    $archivo_adjunto = $archivoNombreFinal ?? 'undefined';

    // Preparar la consulta SQL para actualización
    $sql = "UPDATE COMENTARIOS SET 
                NOMBRE = ?, APELLIDO = ?, CORREO = ?, TELEFONO = ?, DIRECCION = ?, EMPRESA = ?, 
                TIPO = ?, DESCRIPCION = ?, ARCHIVO_ADJUNTO = ?, ESTADO = ? 
            WHERE ID_COMENTARIO = ?";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Error en la preparación de la consulta: " . $conn->error);
    }

    // Vincular los parámetros
    $stmt->bind_param("ssssssssssi", $nombre, $apellido, $correo, $telefono, $direccion, $empresa, $tipo, $descripcion, $archivo_adjunto, $estado, $id_comentario);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        return true;
    } else {
        throw new Exception("Error al actualizar el mensaje: " . $stmt->error);
    }
}
// funcion para leer la queja en la base de datos
function readMessageOnDataBase($conn, $filters = []) {
    $sql = "SELECT * FROM COMENTARIOS";
    $params = [];
    $types = [];

    // Si hay filtros, construimos el WHERE
    if (!empty($filters)) {
        $conditions = [];

        foreach ($filters as $field => $value) {
            $conditions[] = "$field = ?";
            $params[] = $value;

            // Determinar tipo para bind_param
            if (is_int($value)) {
                $types[] = "i";
            } elseif (is_double($value)) {
                $types[] = "d";
            } else {
                $types[] = "s"; // string por defecto
            }
        }

        $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    $sql .= " ORDER BY FECHA_CREACION DESC";

    // Preparar y ejecutar la consulta
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Error en la preparación de la consulta: " . $conn->error);
    }

    // Vincular los parámetros si existen
    if (!empty($params)) {
        $stmt->bind_param(implode("", $types), ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $comentarios = [];
    while ($row = $result->fetch_assoc()) {
        $comentarios[] = $row;
    }

    return $comentarios;
}
// funcion para eliminar la queja en la base de datos
function deleteMessageOnDataBase($conn, $id_comentario) {
    // Asegurarse de que $id_comentario es un número válido
    if (!is_numeric($id_comentario)) {
        throw new Exception("ID inválido.");
    }

    // Preparar la consulta SQL para eliminar el comentario
    $sql = "DELETE FROM COMENTARIOS WHERE ID_COMENTARIO = ?";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Error en la preparación de la consulta: " . $conn->error);
    }

    // Vincular el parámetro y ejecutar la consulta
    $stmt->bind_param("i", $id_comentario);

    if ($stmt->execute()) {
        return true;
    } else {
        throw new Exception("Error al eliminar el mensaje: " . $stmt->error);
    }
}

// ================================= ENRUTAMIENTO =================================
if (isset($_GET['action'])) {
    $action = $_GET['action'];

    // Definir un array para almacenar las respuestas
    $data = [];

    // Dependiendo de la acción solicitada, ejecutar la función correspondiente
    switch ($action) {
        case 'getComentarios':
            // Obtiene todos los comentarios (o los filtrados por parámetros adicionales)
            try {
                $filters = isset($_GET['filters']) ? json_decode($_GET['filters'], true) : [];
                $comentarios = readMessageOnDataBase($conn, $filters);
                $data = $comentarios;
            } catch (Exception $e) {
                $data = ["error" => $e->getMessage()];
            }
            break;

        case 'getComentario':
            // Obtiene un comentario específico según su ID
            if (isset($_GET['id_comentario'])) {
                try {
                    $comentario = readMessageOnDataBase($conn, ['ID_COMENTARIO' => $_GET['id_comentario']]);
                    $data = $comentario;
                } catch (Exception $e) {
                    $data = ["error" => $e->getMessage()];
                }
            } else {
                $data = ["error" => "ID_COMENTARIO es obligatorio"];
            }
            break;

        case 'saveComentario':
            // Guarda un nuevo comentario
            try {
                $form = json_decode(file_get_contents('php://input'), true);
                if (isset($form['NOMBRE']) && isset($form['APELLIDO']) && isset($form['CORREO']) && isset($form['TIPO']) && isset($form['DESCRIPCION'])) {
                    $archivoNombreFinal = null; // Si hay archivo, deberías manejarlo
                    if (isset($form['ARCHIVO_ADJUNTO'])) {
                        $archivoNombreFinal = handleFileUpload($form['ARCHIVO_ADJUNTO']);
                    }
                    saveMessageOnDataBase($conn, $form, $archivoNombreFinal);
                    $data = ["success" => "Comentario guardado correctamente"];
                } else {
                    $data = ["error" => "Faltan campos obligatorios en el formulario"];
                }
            } catch (Exception $e) {
                $data = ["error" => $e->getMessage()];
            }
            break;

        case 'updateComentario':
            // Actualiza un comentario existente
            if (isset($_GET['id_comentario'])) {
                try {
                    $form = json_decode(file_get_contents('php://input'), true);
                    if (isset($form['NOMBRE']) && isset($form['APELLIDO']) && isset($form['CORREO']) && isset($form['TIPO']) && isset($form['DESCRIPCION'])) {
                        $archivoNombreFinal = null; // Si hay archivo, deberías manejarlo
                        if (isset($form['ARCHIVO_ADJUNTO'])) {
                            $archivoNombreFinal = handleFileUpload($form['ARCHIVO_ADJUNTO']);
                        }
                        editMessageOnDataBase($conn, $form, $_GET['id_comentario'], $archivoNombreFinal);
                        $data = ["success" => "Comentario actualizado correctamente"];
                    } else {
                        $data = ["error" => "Faltan campos obligatorios en el formulario"];
                    }
                } catch (Exception $e) {
                    $data = ["error" => $e->getMessage()];
                }
            } else {
                $data = ["error" => "ID_COMENTARIO es obligatorio"];
            }
            break;

        case 'deleteComentario':
            // Elimina un comentario de la base de datos
            if (isset($_GET['id_comentario'])) {
                try {
                    deleteMessageOnDataBase($conn, $_GET['id_comentario']);
                    $data = ["success" => "Comentario eliminado correctamente"];
                } catch (Exception $e) {
                    $data = ["error" => $e->getMessage()];
                }
            } else {
                $data = ["error" => "ID_COMENTARIO es obligatorio"];
            }
            break;

        default:
            // Si la acción no es válida, se devuelve un error
            $data = ["error" => "Acción no válida"];
            break;
    }

    // Establecer el tipo de contenido como JSON
    header('Content-Type: application/json');

    // Si no hay datos, devolver un mensaje adecuado
    if (empty($data)) {
        $data = ["error" => "No hay datos disponibles."];
    }
    // Devolver los datos en formato JSON
    echo json_encode($data);
    } else {
        // Si no se pasa ninguna acción, devolver un error
        echo json_encode(["error" => "Falta la acción en la solicitud"]);
}


$conn->close();
?>