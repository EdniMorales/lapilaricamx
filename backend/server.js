// Cargar las variables del archivo .env
require('dotenv').config({ path: './settings.env' }); // Especifica la ruta al archivo

const express = require('express');
const mysql = require('mysql2');
const app = express();
const port = 3000;

// Middleware para manejar datos en formato JSON
app.use(express.json());

// Configuración de la conexión a la base de datos MySQL utilizando las variables de entorno
const connection = mysql.createConnection({
  host: process.env.DB_HOST,     // Obtiene la variable DB_HOST del archivo .env
  user: process.env.DB_USER,     // Obtiene la variable DB_USER del archivo .env
  password: process.env.DB_PASSWORD, // Obtiene la variable DB_PASSWORD del archivo .env
  database: process.env.DB_DATABASE, // Obtiene la variable DB_DATABASE del archivo .env
});

// Conectar a la base de datos
connection.connect((err) => {
  if (err) {
    console.error('Error al conectar a la base de datos:', err);
    return;
  }
  console.log('Conectado a la base de datos MySQL.');
});

// Ruta raíz
app.get('/', (req, res) => {
    res.send('¡Bienvenido a mi API!');
});

app.get('/lista-productos', (req, res) => {
    const query = `
    SELECT
        PRODUCTOS.NOMBRE AS PRODUCTO,
        PRODUCTOS.PRESENTACION AS PRESENTACION,
        PRODUCTOS.MARCA AS MARCA,
        CATEGORIAS.NOMBRE AS CATEGORIA,
        PRODUCTOS.HISTORIA AS HISTORIA
    FROM PRODUCTOS

    INNER JOIN
        TABLA_ALIMENTICIA
    ON PRODUCTOS.ID_PRODUCTOS = TABLA_ALIMENTICIA.ID_PRODUCTO
    
    INNER JOIN
        CATEGORIAS
    ON TABLA_ALIMENTICIA.ID_CATEGORIA = CATEGORIAS.ID_CATEGORIA;`; // Consulta SQL
  
    connection.query(query, (err, results) => {
      if (err) {
        console.error('Error al hacer la consulta:', err);
        res.status(500).json({ error: 'Error al consultar la base de datos' });
        return;
      }
  
      res.json(results);  // Retorna el resultado de la consulta como JSON
    });
  });
  

// Iniciar el servidor
app.listen(port, () => {
  console.log(`Servidor corriendo en http://localhost:${port}`);
});

