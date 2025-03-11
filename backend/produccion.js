// productos.js - Script para cargar productos desde la API

document.addEventListener('DOMContentLoaded', function() {
    cargarProductos();
});

function cargarProductos() {
    // URL de la API (ajustar según su configuración)
    const apiUrl = 'http://localhost:3000/lapilaricamx/lista-productos'; //const apiUrl = 'https://el-dominio.com/api/lista-productos';
    
    // Elemento del DOM donde se mostrarán los productos
    const contenedorProductos = document.getElementById('productos-container');
    
    // Verificar si existe el contenedor
    if (!contenedorProductos) {
        console.error('No se encontró el elemento con ID "productos-container"');
        return;
    }
    
    // Mostrar indicador de carga
    contenedorProductos.innerHTML = '<p>Cargando productos...</p>';
    
    // Realizar la petición a la API
    fetch(apiUrl)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor: ' + response.status);
            }
            return response.json();
        })
        .then(productos => {
            // Limpiar el contenedor
            contenedorProductos.innerHTML = '';
            
            // Verificar si hay productos
            if (productos.length === 0) {
                contenedorProductos.innerHTML = '<p>No hay productos disponibles</p>';
                return;
            }
            
            // Crear elementos HTML para cada producto
            productos.forEach(producto => {
                const productoElement = document.createElement('div');
                productoElement.className = 'producto-item';
                
                productoElement.innerHTML = `
                    <div class="card mb-4">
                        <div class="card-body">
                            <h3 class="card-title">${producto.PRODUCTO}</h3>
                            <p class="card-text"><strong>Presentación:</strong> ${producto.PRESENTACION}</p>
                            <p class="card-text"><strong>Marca:</strong> ${producto.MARCA}</p>
                            <p class="card-text"><strong>Categoría:</strong> ${producto.CATEGORIA}</p>
                            <div class="mt-3">
                                <h4>Historia</h4>
                                <p>${producto.HISTORIA}</p>
                            </div>
                        </div>
                    </div>
                `;
                
                contenedorProductos.appendChild(productoElement);
            });
        })
        .catch(error => {
            console.error('Error al cargar los productos:', error);
            contenedorProductos.innerHTML = `
                <div class="alert alert-danger">
                    Error al cargar los productos: ${error.message}
                </div>
            `;
        });
}
