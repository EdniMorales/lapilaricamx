
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
        drop.innerHTML= `<h3><a href="articulo.html?id=${product.ID_PRODUCTOS}" target="_blank">${product.NOMBRE}</a></h3>`;

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
        let nombreLimpio = product.NOMBRE.replace(/\s+/g, '').toLowerCase();
        let nombreCapitalizado = product.NOMBRE.toLowerCase().replace(/\b\w/g, char => char.toUpperCase());

        const drop = document.createElement('li');
        drop.innerHTML= `<a class="dropdown-item" href="../${nombreLimpio}/index">
            ${nombreCapitalizado}
            </a>`;

    ddrop.appendChild(drop);
});
}