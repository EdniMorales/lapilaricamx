<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername ="";
$username ="";
$password ="";
$dbname = "";
$port =3306;

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname, $port);
// Establecer la codificación de caracteres de la conexión a UTF-8
$conn->set_charset("utf8mb4");

// Comprobar si hubo un error de conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Función para obtener todas las Categorías
function getAllCategorias($conn) {
    $sql = "SELECT * FROM CATEGORIAS";
    $result = $conn->query($sql);

    if (!$result) {
        error_log('Error en la consulta SQL: ' . $conn->error);
        die('Ocurrió un error, por favor inténtelo más tarde.');
    }

    $data = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    return $data;
}


// Función para obtener un producto específico por ID
function searchIdAllProductos($conn, $id_producto) {
    $sql = <<<EOD
        SELECT
            PRODUCTOS.NOMBRE AS PRODUCTO,
            PRODUCTOS.CLAVE,
            PRODUCTOS.PRESENTACION,
            PRODUCTOS.MARCA,
            PRODUCTOS.HISTORIA,
            PRODUCTOS.IMAGEN_ETIQUETA,
            PRODUCTOS.IMAGEN_PRODUCTO,
            PRODUCTOS.RESETAS,
            TABLA_ALIMENTICIA.PORCION,
            TABLA_ALIMENTICIA.CONTENIDO_ENERGETICO,
            TABLA_ALIMENTICIA.PROTEINA,
            TABLA_ALIMENTICIA.GRASAS_TOTALES,
            TABLA_ALIMENTICIA.GRASAS_SATURADAS,
            TABLA_ALIMENTICIA.GRASAS_TRANS,
            TABLA_ALIMENTICIA.CARBOHIDRATOS,
            TABLA_ALIMENTICIA.AZUCARES_TOTALES,
            TABLA_ALIMENTICIA.AZUCARES_AÑADIDOS,
            TABLA_ALIMENTICIA.FIBRA_DIETETICA,
            TABLA_ALIMENTICIA.SODIO,
            TABLA_ALIMENTICIA.HUMEDAD,
            TABLA_ALIMENTICIA.GRASA_BUTIRICA_MIN,
            TABLA_ALIMENTICIA.PROTEINA_MIN,
            TABLA_ALIMENTICIA.INGREDIENTES,
            TABLA_ALIMENTICIA.DESCRIPCION,
            CATEGORIAS.NOMBRE AS CATEGORIA,
            CATEGORIAS.DESCRIPCION AS DESCRIPCION_CATEGORIA
        FROM
            PRODUCTOS
        INNER JOIN
            TABLA_ALIMENTICIA
        ON PRODUCTOS.ID_PRODUCTOS = TABLA_ALIMENTICIA.ID_PRODUCTO

        INNER JOIN
            CATEGORIAS
        ON TABLA_ALIMENTICIA.ID_CATEGORIA = CATEGORIAS.ID_CATEGORIA

        WHERE PRODUCTOS.ID_PRODUCTOS = ?

        ORDER BY PRODUCTOS.NOMBRE;
    EOD;
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_producto);  // "i" significa un parámetro entero
    $stmt->execute();
    $result = $stmt->get_result();
    
    $data = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    
    return $data;
}

// Función para obtener solo el nombre productos con búsqueda aproximada
function searchOnlyProductos($conn, $search_term) {
    $sql = <<<EOD
        SELECT
            ID_PRODUCTOS,
            NOMBRE
        FROM
            PRODUCTOS

        WHERE NOMBRE LIKE ? AND STATUS = 'ACTIVO'

        ORDER BY NOMBRE;
    EOD;

    $stmt = $conn->prepare($sql);
    $search_term = "%" . $search_term . "%";  // Se utiliza el % para hacer una búsqueda con LIKE
    $stmt->bind_param("s", $search_term);  // "s" significa un parámetro de tipo string
    $stmt->execute();
    $result = $stmt->get_result();
    
    $data = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    
    return $data;
}

// Verificar qué función ejecutar en base a un parámetro
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    
    if ($action == 'getAllFamilias') {
        $data = getAllFamilias($conn);
    } elseif ($action == 'getAllCategorias') {
        $data = getAllCategorias($conn);
    } elseif ($action == 'searchProductos') {
        $data = getAllProductos($conn);
    } elseif ($action == 'searchIdAllProductos' && isset($_GET['search_prod'])) {
        $search_prod = mysqli_real_escape_string($conn, $_GET['search_prod']);
        $data = searchIdAllProductos($conn, $search_prod);
    } elseif ($action == 'searchProductos' && isset($_GET['search_term'])) {
        $search_term = mysqli_real_escape_string($conn, $_GET['search_term']);
        $data = searchProductos($conn, $search_term);
    } else {
        $data = ["error" => "Acción no válida"];
    }

    // Agregar encabezado de tipo de contenido para JSON
    header('Content-Type: application/json');

    // Comprobar si los datos están vacíos
    if (empty($data)) {
        // Si no hay datos, devolver un mensaje adecuado
        echo json_encode(["error" => "No hay datos disponibles."]);
    } else {
        // Devolver los datos en formato JSON
        echo json_encode($data);
    }
}

$conn->close();
?>