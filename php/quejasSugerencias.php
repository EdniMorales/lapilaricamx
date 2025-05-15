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

// ================================= SUSCRIPCION =================================

// funcion para guardar el correo en la base de datos
function saveEmailUserSuscriptionOnDataBase($conn, $email) {
    // Validación de correo
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Correo no válido.";
    }

    // Preparamos la consulta para evitar inyección SQL
    $query = "INSERT INTO SUSCRIPCIONES (CORREO, ESTADO) VALUES (?, 'Suscrito')";
    $stmt = $conn->prepare($query);
    
    if ($stmt === false) {
        return "Error en la preparación de la consulta: " . $conn->error;
    }

    // Vinculamos el parámetro del correo
    $stmt->bind_param("s", $email);

    // Ejecutamos la consulta
    if ($stmt->execute()) {
        return "Correo suscrito exitosamente.";
    } else {
        return "Error al suscribir el correo: " . $stmt->error;
    }

    $stmt->close();
}
// funcion para editar el correo en la base de datos
function editEmailUserSuscriptionOnDataBase($conn, $id_correo, $correo, $estado) {
    // Verificar que se pasen los parámetros correctos
    if (empty($id_correo) || empty($correo) || empty($estado)) {
        return "Error: Debes proporcionar el ID, correo y estado para editar.";
    }

    // Consulta SQL para actualizar el correo y el estado, y establecer la fecha de edición
    $query = "UPDATE SUSCRIPCIONES 
            SET CORREO = ?, ESTADO = ?, FECHA_CREACION = CURRENT_TIMESTAMP
            WHERE ID_SUSCRIPCION = ?";
    
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        return "Error en la preparación de la consulta: " . $conn->error;
    }

    // Vinculamos los parámetros: 's' para string (correo y estado), 'i' para integer (id_correo)
    $stmt->bind_param("ssi", $correo, $estado, $id_correo);

    // Ejecutamos la consulta
    if ($stmt->execute()) {
        return "Suscripción actualizada correctamente.";
    } else {
        return "Error al actualizar la suscripción: " . $stmt->error;
    }

    $stmt->close();
}
// funcion para leer el correo en la base de datos
function readEmailUserSuscriptionOnDataBase($conn, $id_correo = null, $correo = null) {
    // Si se proporciona un ID o un correo, filtrar según el caso
    if ($id_correo) {
        // Filtrar por ID
        $query = "SELECT * FROM SUSCRIPCIONES WHERE ID_SUSCRIPCION = ?";
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            return "Error en la preparación de la consulta: " . $conn->error;
        }
        $stmt->bind_param("i", $id_correo); // 'i' para entero (ID)
    } elseif ($correo) {
        // Filtrar por correo
        $query = "SELECT * FROM SUSCRIPCIONES WHERE CORREO = ?";
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            return "Error en la preparación de la consulta: " . $conn->error;
        }
        $stmt->bind_param("s", $correo); // 's' para string (correo)
    } else {
        // Si no se proporciona ni ID ni correo, obtener todos los registros
        $query = "SELECT * FROM SUSCRIPCIONES";
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            return "Error en la preparación de la consulta: " . $conn->error;
        }
    }

    // Ejecutamos la consulta
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows > 0) {
        // Devolvemos los resultados como un array asociativo
        return $resultado->fetch_all(MYSQLI_ASSOC);
    } else {
        return "No se encontraron suscripciones.";
    }

    $stmt->close();
}
// funcion para eliminar el correo en la base de datos
function deleteEmailUserSuscriptionOnDataBase($conn, $id_correo) {
    // Preparamos la consulta para eliminar la suscripción
    $query = "DELETE FROM SUSCRIPCIONES WHERE ID_SUSCRIPCION = ?";
    $stmt = $conn->prepare($query);
    
    if ($stmt === false) {
        return "Error en la preparación de la consulta: " . $conn->error;
    }

    // Vinculamos el parámetro del ID
    $stmt->bind_param("i", $id_correo); // 'i' para entero

    // Ejecutamos la consulta
    if ($stmt->execute()) {
        return "Correo eliminado de la base de datos.";
    } else {
        return "Error al eliminar el correo: " . $stmt->error;
    }

    $stmt->close();
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

        case 'guardarSuscripcion':
            if (isset($_POST['correo'])) {
                $correo = $_POST['correo'];
                // Validación del correo (opcional)
                if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                    $data = ["error" => "Correo electrónico no válido"];
                } else {
                    // Llamar a la función para guardar en la base de datos
                    $result = saveEmailUserSuscriptionOnDataBase($conn, $correo);
                    if ($result) {
                        $data = ["success" => "Correo guardado correctamente"];
                    } else {
                        $data = ["error" => "Hubo un problema al guardar el correo"];
                    }
                }
            } else {
                $data = ["error" => "Correo no proporcionado"];
            }
            break;

        // Acción para editar el estado de suscripción
        case 'editarSuscripcion':
            if (isset($_POST['id_correo']) && isset($_POST['estado'])) {
                $id_correo = $_POST['id_correo'];
                $estado = $_POST['estado'];
                // Llamar a la función para editar el correo en la base de datos
                $result = editEmailUserSuscriptionOnDataBase($conn, $id_correo, $estado);
                if ($result) {
                    $data = ["success" => "Estado de suscripción actualizado"];
                } else {
                    $data = ["error" => "Hubo un problema al actualizar el estado"];
                }
            } else {
                $data = ["error" => "Faltan parámetros para editar"];
            }
            break;

        // Acción para leer todos los correos de suscripción
        case 'leerSuscripciones':
            // Llamar a la función para leer todos los correos de suscripción
            $suscripciones = readEmailUserSuscriptionOnDataBase($conn);
            if ($suscripciones) {
                $data = ["success" => "Suscripciones obtenidas", "suscripciones" => $suscripciones];
            } else {
                $data = ["error" => "No se encontraron suscripciones"];
            }
            break;

        // Acción para eliminar una suscripción
        case 'eliminarSuscripcion':
            if (isset($_POST['id_correo'])) {
                $id_correo = $_POST['id_correo'];
                // Llamar a la función para eliminar el correo en la base de datos
                $result = deleteEmailUserSuscriptionOnDataBase($conn, $id_correo);
                if ($result) {
                    $data = ["success" => "Correo eliminado correctamente"];
                } else {
                    $data = ["error" => "Hubo un problema al eliminar el correo"];
                }
            } else {
                $data = ["error" => "Faltan parámetros para eliminar"];
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