
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
         drop.innerHTML= `<p><a href="articulo.html?id=${product.ID_PRODUCTOS}" target="_blank">${product.NOMBRE}</a></p>`

     ;

     
 
     ddrop.appendChild(drop);
 });
 }