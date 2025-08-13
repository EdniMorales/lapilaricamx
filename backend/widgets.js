
export function dropdownprod (widget,array) {
    const ddrop = document.getElementById(widget);
    ddrop.innerHTML = '';

    if(array.error){
        ddrop.innerHTML = `<p>${array.error}</p>`;
        return;
    }
    if(array.length === 0){
        ddrop.innerHTML = '<p>No se encontraron productos.</p>';
        return;
    }

    array.forEach(product => {
        const drop = document.createElement('div');
        drop.classList.add('dropdownList');
        drop.innerHTML= `<h3><a href="../articulos/index?Id=${product.ID_PRODUCTOS}">${product.NOMBRE}</a></h3>`;

    ddrop.appendChild(drop);
});
}

export function DropCategorias(widget,array){
    const ddrop = document.getElementById(widget);
    ddrop.innerHTML = '';
    
    if(array.error){ // Por si falla la consulta
        ddrop.innerHTML = `
        <li>
            <a class="dropdown-item" href="../Principal/index">
            ${array.error}
            </a>
        </li>`;
        return;
    }
    if(array.length === 0){ // si la consulta esta vacia
        ddrop.innerHTML = `
        <li>
            <a class="dropdown-item" href="../Principal/index">
            No se encontraron productos.
            </a>
        </li>`;
        return;
    }

    array.forEach(product => { // la funcion para colocar los datos de la consulta
        let nombreLimpio = product.NOMBRE.replace(/\s+/g, '').toLowerCase() || "principal";
        let nombreCapitalizado = product.NOMBRE.toLowerCase().replace(/\b\w/g, char => char.toUpperCase());

        const drop = document.createElement('li');
        drop.innerHTML= `<a class="dropdown-item" href="../${nombreLimpio}/index">
            ${nombreCapitalizado}
            </a>`;

    ddrop.appendChild(drop);
});
}

export function ColocarLosProductosEnLasTarjetas(widget,array){
    const ddrop = document.getElementById(widget);
    ddrop.innerHTML = '';
    
    if(array.error){ // Por si falla la consulta
        ddrop.innerHTML = `
        <div class="card h-100">
            <!-- Product image-->
            <img class="card-img-top" src="../assets/new-cheese/default.png" alt="..." />
            <!-- Product details-->
            <div class="card-body p-4">
                <div class="text-center">
                    <!-- Product name-->
                    <h5 class="fw-bolder">${array.error}</h5>
                </div>
            </div>
            <!-- Product actions-->
            <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                <div class="text-center"><a class="btn btn-outline-dark mt-auto" href="../Principal/index">Mas Info.</a></div>
            </div>
        </div>`;
        return;
    }
    if(array.length === 0){ // si la consulta esta vacia
        ddrop.innerHTML = `
        <div class="card h-100">
            <!-- Product image-->
            <img class="card-img-top" src="../assets/new-cheese/default.png" alt="..." />
            <!-- Product details-->
            <div class="card-body p-4">
                <div class="text-center">
                    <!-- Product name-->
                    <h5 class="fw-bolder">No se encontraron productos.</h5>
                    <!-- Product price-->
                    <h6>Marca: Pilarica</h6>
                    <h6>Presentacion: 0.5 Kg</h6>
                    Categoria: Quesos Blancos
                </div>
            </div>
            <!-- Product actions-->
            <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                <div class="text-center"><a class="btn btn-outline-dark mt-auto" href="../Principal/index">Mas Info.</a></div>
            </div>
        </div>`;
        return;
    }

    array.forEach(product => { // la funcion para colocar los datos de la consulta
        let nombreLimpio = product.CATEGORIA.replace(/\s+/g, '').toLowerCase();
        let nombreCapitalizado = product.CATEGORIA.toLowerCase().replace(/\b\w/g, char => char.toUpperCase());

        // Verificar si IMAGEN_PRODUCTO tiene un valor Base64 o es null
        let imagenProducto = product.IMAGEN_ETIQUETA;
        if (imagenProducto != 'pilarica') { // !imagenProducto
            // Si IMAGEN_PRODUCTO es null o vacío, usar una imagen predeterminada
            imagenProducto = '../assets/new-cheese/default.png';
        } else {
            // Asegurarse de que la imagen esté en formato Base64 adecuado
            imagenProducto = 'data:image/png;base64,' + imagenProducto;
        }

        ///const imagenProducto = product.IMAGEN_ETIQUETA || '../assets/new-cheese/default.png';  // Si no tiene imagen, usar la predeterminada

        const drop = document.createElement('div');
        drop.classList.add('caj-prod');
        drop.innerHTML= `<div class="row">
                            <div class="col mb-5">
            <div class="card h-100 wow bounceInUp data-wow-duration="2s" data-wow-delay="2s">
                <!-- Product image-->
                <img class="card-img-top" src="${imagenProducto}" alt="..." />
                <!-- Product details-->
                <div class="card-body p-4">
                    <div class="text-center">
                        <!-- Product name-->
                        <h5 class="fw-bolder">${product.PRODUCTO}</h5>
                        <!-- Product price-->
                        <h6>Marca: ${product.MARCA}</h6>
                        <h6>Presentacion: ${product.PRESENTACION}</h6>
                        Categoria: ${product.CATEGORIA}
                    </div>
                </div>
                <!-- Product actions-->
                <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                    <div class="text-center"><a class="btn btn-outline-dark mt-auto" href="../articulos/index?Id=${product.ID_PRODUCTOS}">Mas Info.</a></div>
                </div>
            </div>
        </div> </div>`;

    ddrop.appendChild(drop);
});
}

export function ColocarLosDatosDelProductoEnLaPagina(array) {
    // Declarar los elementos del DOM para modificarlos
    // Datos del producto
    const imagenEtiqueta = document.getElementById("ImagenEtiqueta_Producto");
    //const imagenProducto = document.getElementById("ImagenProducto_Producto");
    const nombreProducto = document.getElementById("NombreDeProducto_Producto");
    const descripcion = document.getElementById("Descripcion_Producto");
    const ingredientes = document.getElementById("Ingredientes_Producto");
    const historia = document.getElementById("Historia_Producto");

    //Tabla nutrimental
    const porcion = document.getElementById("Porcion_Producto");
    const contenidoEnergetico = document.getElementById("ContenidoEnergetico_Producto");
    const proteina = document.getElementById("Proteina_Producto");
    const grasasTotales = document.getElementById("GrasasTotales_Producto");
    const grasasSaturadas = document.getElementById("GrasasSaturadas_Producto");
    const grasasTrans = document.getElementById("GrasasTrans_Producto");
    const carbohidratos = document.getElementById("Carbohidratos_Producto");
    const azucaresAnadidos = document.getElementById("AzucaresAnadidos_Producto");
    const azucaresTotales = document.getElementById("AzucaresTotales_producto");
    const fibraDietetica = document.getElementById("FibraDietetica_Producto");
    const sodio = document.getElementById("Sodio_Producto");

    //Progress bars
    const humedad = document.getElementById("Humedad_Producto");
    const humedadProgress = document.getElementById("HumedadProgress_Producto")
    const grasaButiricaMin = document.getElementById("GrasaButiricaMin_Producto");
    const grasaButiricaMinProgress = document.getElementById("GrasaButiricaMinProgress_Producto");
    const proteinaMin = document.getElementById("ProteinaMin_Producto");
    const proteinaMinProgress = document.getElementById("ProteinaMinProgress_Producto");

    //Botones
    const BotonConoseMas = document.getElementById("BotonConoceMas_Producto");


    if(array.error){ // Por si falla la consulta
        console.log(array.error)
        return
    }
    if(array.length === 0){ // si la consulta esta vacia
        return
    }

    array.forEach(product => { // la funcion para colocar los datos de la consulta
        let nombreLimpio = product.CATEGORIA.replace(/\s+/g, '').toLowerCase();
        let nombreCapitalizado = product.CATEGORIA.toLowerCase().replace(/\b\w/g, char => char.toUpperCase());
        
        //Encabezado
        nombreProducto.innerText = product.PRODUCTO;
        descripcion.innerText = product.DESCRIPCION;
        BotonConoseMas.href = `../${nombreLimpio}/index`;

        //Tabla
        porcion.innerText = `Información nutrimental por cada ${product.PORCION} g.`;
        contenidoEnergetico.innerText = product.CONTENIDO_ENERGETICO;
        proteina.innerText = product.PROTEINA;
        grasasTotales.innerText = product.GRASAS_TOTALES;
        grasasSaturadas.innerText = product.GRASAS_SATURADAS;
        grasasTrans.innerText = product.GRASAS_TRANS;
        carbohidratos.innerText = product.CARBOHIDRATOS;
        azucaresAnadidos.innerText = product.AZUCARES_AÑADIDOS;
        azucaresTotales.innerText = product.AZUCARES_TOTALES;
        fibraDietetica.innerText = product.FIBRA_DIETETICA;
        sodio.innerText = product.SODIO;
        ingredientes.innerText = product.INGREDIENTES

        //Progres bars
        humedad.innerHTML = `<strong>Humedad: ${product.HUMEDAD}</strong>`;
        let humedadPorcentaje = parseFloat(product.HUMEDAD);
        humedadProgress.style = `width: ${humedadPorcentaje}%`;

        grasaButiricaMin.innerHTML = `<strong>Grasa Butírica min: ${product.GRASA_BUTIRICA_MIN}</strong>`;
        let grasaButiricaMinPorsentaje = parseFloat(product.GRASA_BUTIRICA_MIN);
        grasaButiricaMinProgress.style = `width: ${grasaButiricaMinPorsentaje}%`;

        proteinaMin.innerHTML = `<strong>Proteína min: ${product.PROTEINA_MIN}</strong>`;
        let proteinaMinPorsentaje = parseFloat(product.PROTEINA_MIN);
        proteinaMinProgress.style = `width: ${proteinaMinPorsentaje}%`;

        //Historia
        historia.innerText = product.HISTORIA;

        //Imagenes

        // Verificar si IMAGEN_PRODUCTO tiene un valor Base64 o es null
        let imagenProductoDB = product.IMAGEN_PRODUCTO;
        if (imagenProductoDB != 'Pilarica') { // !imagenProductoDB
            // Si IMAGEN_PRODUCTO es null o vacío, usar una imagen predeterminada
            imagenProductoDB = '../assets/new-cheese/default.png';
        } else {
            // Asegurarse de que la imagen esté en formato Base64 adecuado
            imagenProductoDB = 'data:image/png;base64,' + imagenProductoDB;
        }

        // Verificar si IMAGEN_ETIQWUETA tiene un valor Base64 o es null
        let imagenEtiquetaDB = product.IMAGEN_ETIQUETA;
        if (imagenEtiquetaDB != 'Pilarica') { // !imagenEtiquetaDB
            // Si IMAGEN_PRODUCTO es null o vacío, usar una imagen predeterminada
            imagenEtiquetaDB = '../assets/new-cheese/default.png';
        } else {
            // Asegurarse de que la imagen esté en formato Base64 adecuado
            imagenEtiquetaDB = 'data:image/png;base64,' + imagenEtiquetaDB;
        }

        // Verificar si IMG_BANNER tiene una ruta o es null
        let imagenBannerDB = product.IMG_BANNER;
        if (!imagenBannerDB) {
            // Si IMAGEN_PRODUCTO es null o vacío, usar una imagen predeterminada
            imagenBannerDB = '../assets/new-cheese/default.png';
        } else {
            // Asegurarse de que la imagen esté en formato Base64 adecuado
            imagenBannerDB = imagenBannerDB;
        }

        //imagenProducto.src = imagenProductoDB;
        imagenEtiqueta.src = imagenEtiquetaDB;
});
}