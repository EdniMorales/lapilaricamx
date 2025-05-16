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

// ================================= SUSCRIPCION =================================

// funcion para guardar el correo en la base de datos
function saveEmailUserSuscriptionOnDataBase($conn, $email) {
    // Validación de correo
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Correo no válido.";
    }

    // Preparamos la consulta para evitar inyección SQL
    $query = "INSERT INTO SUSCRIPCIONES (CORREO, ESTADO) 
            VALUES (?, 'Suscrito')
            ON DUPLICATE KEY UPDATE ESTADO = 'Suscrito', FECHA_CREACION = CURRENT_TIMESTAMP";
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
        case 'guardarSuscripcion':
            if (isset($_POST['correo'])) {
                $correo = $_POST['correo'];
                if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                    $data = ["error" => "Correo electrónico no válido"];
                } else {
                    $result = saveEmailUserSuscriptionOnDataBase($conn, $correo);

                    // Detectar si hay error dentro del resultado
                    if (stripos($result, 'error') !== false || stripos($result, 'no válido') !== false) {
                        $data = ["error" => $result];
                    } else {
                        $data = ["success" => $result]; // mensaje exitoso
                    }
                }
            } else {
                $data = ["error" => "Correo no proporcionado"];
            }
            break;

        // Acción para editar el estado de suscripción
        case 'editarSuscripcion':
            if (isset($_POST['id_correo']) && isset($_POST['correo']) && isset($_POST['estado'])) {
                $id_correo = $_POST['id_correo'];
                $correo = $_POST['correo'];
                $estado = $_POST['estado'];

                // Llamar a la función para editar el correo en la base de datos
                $result = editEmailUserSuscriptionOnDataBase($conn, $id_correo, $correo, $estado);

                // Procesar el resultado
                if (str_starts_with($result, "Suscripción actualizada")) {
                    $data = ["success" => $result];
                } else {
                    $data = ["error" => $result];
                }
            } else {
                $data = ["error" => "Faltan parámetros para editar (id_correo, correo, estado)"];
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
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    } else {
        // Si no se pasa ninguna acción, devolver un error
        echo json_encode(["error" => "Falta la acción en la solicitud"], JSON_UNESCAPED_UNICODE);
}

$conn->close();
?>