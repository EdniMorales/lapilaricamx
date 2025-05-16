import * as formulario from './quejasSugerencias.js';
import * as widgets from './widgets.js';

// Disparadores de los botones del DOM
export function ProductSearch(Id_Producto, page, dropdown){
    const Search_Text = document.getElementById(Id_Producto).value;
    const widget = document.getElementById(dropdown)
    console.log(`Texto buscado: ${Search_Text}`);

    // Validar que no este vacio
    if (Search_Text.length > 0){
        // Ajax asi es servidor
        fetch(`../php/backend.php?action=searchOnlyProductos&search_prod=${encodeURIComponent(Search_Text)}`) //<= Se coloca ../ para produccion
            .then(response => response.json()) // Espera la respuesta como JSON
            .then(data => {
            //    console.log("Datos obtenidos: ", data);
                widgets.dropdownprod(dropdown , data);
            })
            .catch(error => {
                console.error("Error al buscar productos:", error);
            });
    }else{
        widget.innerHTML = ''
    }
};

export function CategoriasSearch(page, dropdown){
    const widget = document.getElementById(dropdown);
    console.log("Se a clickeado");
    //Ajax hacia el servidor
    fetch(`../php/backend.php?action=getAllCategorias`)
        .then(response => response.json()) // Espera la respuesta como JSON
        .then(data => {
            //console.log("Datos obtenidos: ", data);
            widgets.DropCategorias(dropdown , data);
        })
        .catch(error => {
            console.error("Error al buscar productos:", error);
        });
}

export function ProductosPorCategoriaSearch(parametros){
    console.log(parametros);
    // Declarar cada uno de los parametros para su utilizacion
    let Carpeta = parametros[0];
    let contenedor = parametros[1];
    let IdCategoria = parametros[2];
    let widget = document.getElementById(contenedor);

    // Validar que no este vacio
    if (Carpeta.length > 0){
        //Ajax hacia el servidor
        fetch(`../php/backend.php?action=searchIdAllProductosByCategories&search_categories=${encodeURIComponent(IdCategoria)}`)
            .then(response => response.json()) // Espera la respuesta como JSON
            .then(data => {
                console.log("Datos obtenidos: ", data);
                widgets.ColocarLosProductosEnLasTarjetas(contenedor, data);
            })
            .catch(error => {
                console.error("Error al buscar productos:", error);
            });
    }else{
        widget.innerHTML = ''
    }
}

export function MostrarDatosPorProductoIndividual(Id_Producto){
    if (Id_Producto && Id_Producto>0){
        fetch(`../php/backend.php?action=searchIdAllProductos&search_prod=${Id_Producto}`)
            .then(response => response.json()) // Espera la respuesta como JSON
            .then(data => {
                console.log("Datos obtenidos: ", data);
                widgets.ColocarLosDatosDelProductoEnLaPagina(data);
            })
            .catch(error => {
                console.error("Error al buscar productos:", error);
            });
    }/*else{
        alert("No pudimos encontrar el producto que estas buscando");
        window.location.href = `../principal/index`;
    }*/
}

export function SuscribirCorreoPilaricaNews(widget, user, lastname){
    const correo = document.getElementById(widget);
    const usuario = document.getElementById(user);
    const apellido = document.getElementById(lastname);
    // extraer el valor de la casilla y enviarlo al modelo
    formulario.guardarCorreoEnElServidor(correo.value, usuario.value, apellido.value);
}

export function EnviarDatosDelFormulario(form){
    // pasar el formulario al modelo
    formulario.empaquetarElFormulario(form);
}