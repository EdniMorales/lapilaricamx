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

// funcion gardar archivo
function handleFileUpload($fileInputName = 'archivo') {
    // Verifica que el archivo fue cargado sin errores
    if (!isset($_FILES[$fileInputName]) || $_FILES[$fileInputName]['error'] !== UPLOAD_ERR_OK) {
        return null; // Si no hay archivo o hay error, no subimos nada
    }

    $archivo = $_FILES[$fileInputName];

    // Validar tipo de archivo permitido
    $tiposPermitidos = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'application/pdf' => 'pdf'
    ];

    $tipoArchivo = mime_content_type($archivo['tmp_name']);
    if (!isset($tiposPermitidos[$tipoArchivo])) {
        throw new Exception("Tipo de archivo no permitido: " . $tipoArchivo);
    }

    $extension = $tiposPermitidos[$tipoArchivo];
    $nombreUnico = uniqid('adjunto_', true) . '.' . $extension;

    $carpetaDestino = '../uploads/';
    if (!is_dir($carpetaDestino)) {
        mkdir($carpetaDestino, 0777, true);
    }

    $rutaFinal = $carpetaDestino . $nombreUnico;

    if (!move_uploaded_file($archivo['tmp_name'], $rutaFinal)) {
        throw new Exception("No se pudo mover el archivo al destino.");
    }

    return $rutaFinal;
}

// ================================= COMENTARIOS =================================

// funcion para guardar la queja en la base de datos
function saveMessageOnDataBase($conn, $form, $archivoNombreFinal = null) {
    // Validación básica de los datos
    $nombre = htmlspecialchars($form['NombreFormQS']);
    $apellido = htmlspecialchars($form['ApellidoFormQS']);
    $correo = filter_var($form['EmailFormQS'], FILTER_VALIDATE_EMAIL);
    if (!$correo) {
        throw new Exception("Correo inválido.");
    }

    $telefono = htmlspecialchars($form['TelFormQS']);
    $direccion = htmlspecialchars($form['DireccionFormQS']);
    $empresa = htmlspecialchars($form['EmpresaFormQS']);
    $tipo = htmlspecialchars($form['TipoFormQS']);
    $descripcion = htmlspecialchars($form['MensajeFormQS']);
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
function editMessageOnDataBase($conn, $form, $id_comentario, $estadoForm='Pendiente', $archivoNombreFinal = null) {
    // Validación de datos
    $nombre = htmlspecialchars($form['NombreFormQS']);
    $apellido = htmlspecialchars($form['ApellidoFormQS']);
    $correo = filter_var($form['EmailFormQS'], FILTER_VALIDATE_EMAIL);
    if (!$correo) {
        throw new Exception("Correo inválido.");
    }

    $telefono = htmlspecialchars($form['TelFormQS']);
    $direccion = htmlspecialchars($form['DireccionFormQS']);
    $empresa = htmlspecialchars($form['EmpresaFormQS']);
    $tipo = htmlspecialchars($form['TipoFormQS']);
    $descripcion = htmlspecialchars($form['MensajeFormQS']);
    $estado = htmlspecialchars($estadoForm);
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
            // Guarda un nuevo comentario (para formulario con multipart/form-data)
            try {
                $form = $_POST; // <- AQUÍ el cambio importante

                // Validamos los campos obligatorios
                if (isset($form['NombreFormQS']) && isset($form['ApellidoFormQS']) && isset($form['EmailFormQS']) && isset($form['TipoFormQS']) && isset($form['MensajeFormQS'])) {
                    $archivoNombreFinal = null;

                    // Si se subió un archivo, lo procesamos
                    if (isset($_FILES['FileFormQS']) && $_FILES['FileFormQS']['error'] === 0) {
                        $archivoNombreFinal = handleFileUpload('FileFormQS');
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
                    if (isset($form['NombreFormQS']) && isset($form['ApellidoFormQS']) && isset($form['EmailFormQS']) && isset($form['TipoFormQS']) && isset($form['MensajeFormQS'])) {
                        $archivoNombreFinal = null; // Si hay archivo, deberías manejarlo
                        if (isset($form['FileFormQS'])) {
                            $archivoNombreFinal = handleFileUpload('FileFormQS');
                        }
                        $estado = $form['EstadoFormQS'] ?? 'Pendiente';
                        editMessageOnDataBase($conn, $form, $_GET['id_comentario'], $estado, $archivoNombreFinal);
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
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    } else {
        // Si no se pasa ninguna acción, devolver un error
        echo json_encode(["error" => "Falta la acción en la solicitud"], JSON_UNESCAPED_UNICODE);
}

$conn->close();
?>