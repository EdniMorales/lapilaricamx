import * as widgets from './widgets.js';

// Disparadores de los botones del DOM
export function ProductSearch(Id_Producto, page, dropdown){
    const Search_Text = document.getElementById(Id_Producto).value;
    console.log(`Texto buscado: ${Search_Text}`);

    // Validar que no este vacio
    if (Search_Text.length > 0){
        // Ajax asi es servidor
        fetch(`php/backend.php?action=searchOnlyProductos&search_prod=${encodeURIComponent(Search_Text)}`)
            .then(response => response.json()) // Espera la respuesta como JSON
            .then(data => {
                console.log("Datos obtenidos: ", data); 
                widgets.dropdownprod(dropdown , data);
            })
            .catch(error => {
                console.error("Error al buscar productos:", error);
            });
    }
};