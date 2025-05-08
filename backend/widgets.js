
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
        drop.innerHTML= `<h3><a href="../articulos/index?id=${product.ID_PRODUCTOS}" target="_blank">${product.NOMBRE}</a></h3>`;

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
        if (!imagenProducto) {
            // Si IMAGEN_PRODUCTO es null o vacío, usar una imagen predeterminada
            imagenProducto = '../assets/new-cheese/default.png';
        } else {
            // Asegurarse de que la imagen esté en formato Base64 adecuado
            imagenProducto = 'data:image/png;base64,' + imagenProducto;
        }

        ///const imagenProducto = product.IMAGEN_ETIQUETA || '../assets/new-cheese/default.png';  // Si no tiene imagen, usar la predeterminada

        const drop = document.createElement('div');
        drop.innerHTML= `<div class="col mb-5">
            <div class="card h-100">
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
        </div>`;

    ddrop.appendChild(drop);
});
}