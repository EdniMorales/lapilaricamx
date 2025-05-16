<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../config/db.php';

// Crear la conexión
$conn = Database::connect();

// Comprobar si hubo un error de conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Función para obtener todas las Categorías
function getAllCategorias($conn) {
    $sql = "SELECT * FROM CATEGORIAS WHERE NOMBRE != ' '";
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
function searchIdAllProductosByCategories($conn, $id_categorie) {
    $sql = <<<EOD
        SELECT
            PRODUCTOS.ID_PRODUCTOS,
            PRODUCTOS.NOMBRE AS PRODUCTO,
            PRODUCTOS.PRESENTACION,
            PRODUCTOS.MARCA,
            PRODUCTOS.IMAGEN_ETIQUETA,
            PRODUCTOS.IMAGEN_PRODUCTO,
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

        WHERE CATEGORIAS.ID_CATEGORIA = ? AND TABLA_ALIMENTICIA.STATUS = 'ACTIVO'

        ORDER BY PRODUCTOS.NOMBRE;
    EOD;
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_categorie);  // "i" significa un parámetro entero
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

// Función para obtener un producto específico por ID
function searchIdAllProductos($conn, $id_producto) {
    $sql = <<<EOD
        SELECT
            PRODUCTOS.NOMBRE AS PRODUCTO,
            PRODUCTOS.PRESENTACION,
            PRODUCTOS.MARCA,
            PRODUCTOS.HISTORIA,
            PRODUCTOS.IMAGEN_ETIQUETA,
            PRODUCTOS.IMAGEN_PRODUCTO,
            PRODUCTOS.IMG_BANNER,
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

        ORDER BY NOMBRE
        
        LIMIT 4;
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
    } elseif ($action == 'searchIdAllProductosByCategories' && isset($_GET['search_categories'])){
        $data = searchIdAllProductosByCategories($conn, $_GET['search_categories']);
    } elseif ($action == 'searchOnlyProductos' && isset($_GET['search_prod'])) {
        $search_term = mysqli_real_escape_string($conn, $_GET['search_prod']);
        $data = searchOnlyProductos($conn, $search_term);
    } else {
        $data = ["error" => "Acción no válida"];
    }

    // Agregar encabezado de tipo de contenido para JSON
    header('Content-Type: application/json');

    // Comprobar si los datos están vacíos
    if (empty($data)) {
        // Si no hay datos, devolver un mensaje adecuado
        echo json_encode(["error" => "No hay datos disponibles."], JSON_UNESCAPED_UNICODE);
    } else {
        // Devolver los datos en formato JSON
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}

$conn->close();
?>