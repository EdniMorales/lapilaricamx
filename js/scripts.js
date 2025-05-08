/*!
* Start Bootstrap - Modern Business v5.0.7 (https://startbootstrap.com/template-overviews/modern-business)
* Copyright 2013-2023 Start Bootstrap
* Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-modern-business/blob/master/LICENSE)
*/
// This file is intentionally blank
// Use this file to add JavaScript to your project

/* Ejecucion de animaciones en la pagina web*/

/*!
* Start Bootstrap - Stylish Portfolio v6.0.6 (https://startbootstrap.com/theme/stylish-portfolio)
* Copyright 2013-2023 Start Bootstrap
* Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-stylish-portfolio/blob/master/LICENSE)
*/

import * as trriggers from '../backend/trigger.js';

window.addEventListener('DOMContentLoaded', event => {

    // Funcion que carga las categorias
    TraerCategorias();
    TaerDatosDependiendoLaRutaDelDOM();

    const sidebarWrapper = document.getElementById('sidebar-wrapper');
    const menuToggle = document.body.querySelector('.menu-toggle');
    let scrollToTopVisible = false;

    // Verificamos si existen los elementos requeridos antes de trabajar con ellos
    if (!sidebarWrapper || !menuToggle) return;

    // Closes the sidebar menu
    menuToggle.addEventListener('click', event => {
        event.preventDefault();
        sidebarWrapper.classList.toggle('active');
        _toggleMenuIcon();
        menuToggle.classList.toggle('active');
    });

    // Closes responsive menu when a scroll trigger link is clicked
    const scrollTriggerList = [].slice.call(document.querySelectorAll('#sidebar-wrapper .js-scroll-trigger'));
    scrollTriggerList.forEach(scrollTrigger => {
        scrollTrigger.addEventListener('click', () => {
            sidebarWrapper.classList.remove('active');
            menuToggle.classList.remove('active');
            _toggleMenuIcon();
        });
    });

    // Toggle menu icon (hamburger ↔ X)
    function _toggleMenuIcon() {
        const menuToggleBars = document.body.querySelector('.menu-toggle > .fa-bars');
        const menuToggleTimes = document.body.querySelector('.menu-toggle > .fa-xmark');

        if (menuToggleBars) {
            menuToggleBars.classList.remove('fa-bars');
            menuToggleBars.classList.add('fa-xmark');
        } else if (menuToggleTimes) {
            menuToggleTimes.classList.remove('fa-xmark');
            menuToggleTimes.classList.add('fa-bars');
        }
    }

    // Scroll to top button appear
    document.addEventListener('scroll', () => {
        const scrollToTop = document.body.querySelector('.scroll-to-top');
        if (!scrollToTop) return;

        if (document.documentElement.scrollTop > 100) {
            if (!scrollToTopVisible) {
                fadeIn(scrollToTop);
                scrollToTopVisible = true;
            }
        } else {
            if (scrollToTopVisible) {
                fadeOut(scrollToTop);
                scrollToTopVisible = false;
            }
        }
    });

    // Simple fadeIn implementation
    function fadeIn(element) {
        element.style.display = 'block';
        element.style.opacity = 0;
        let op = 0;
        const timer = setInterval(() => {
            if (op >= 1) {
                clearInterval(timer);
            }
            element.style.opacity = op;
            op += 0.1;
        }, 30);
    }

    // Simple fadeOut implementation
    function fadeOut(element) {
        let op = 1;
        const timer = setInterval(() => {
            if (op <= 0) {
                clearInterval(timer);
                element.style.display = 'none';
            }
            element.style.opacity = op;
            op -= 0.1;
        }, 30);
    }

});


function fadeOut(el) {
    el.style.opacity = 1;
    (function fade() {
        if ((el.style.opacity -= .1) < 0) {
            el.style.display = "none";
        } else {
            requestAnimationFrame(fade);
        }
    })();
};

function fadeIn(el, display) {
    el.style.opacity = 0;
    el.style.display = display || "block";
    (function fade() {
        var val = parseFloat(el.style.opacity);
        if (!((val += .1) > 1)) {
            el.style.opacity = val;
            requestAnimationFrame(fade);
        }
    })();
};

// FUNCION PARA TRAER LAS CATEGORIAS AL MENU DESPLEGABLE
function TraerCategorias(){
    console.log("Categorias creadas");
    trriggers.CategoriasSearch("index","Categorias");
}

// FUNCION PARA COLOCAR LOS DATOS SEGUN LA RUTA EN LA QUE SE ENCUENTRE EL USUARIO
function TaerDatosDependiendoLaRutaDelDOM(){
    const ruta = window.location.pathname; // Ruta Completa
    const partesRuta = ruta.split('/'); // Separar por /
    const ultimaCarpeta = partesRuta.length >= 2 ? partesRuta[partesRuta.length - 2] : null; // Traer la penultima seccion en este caso la ruta de la carpeta
    //console.log("Estás en:", ultimaCarpeta);

    // Variables con las que se pasara la informacion a el modelo
    let carpeta = null;

    // Dependiendo de la ruta es lo que se va a mostar
    switch(ultimaCarpeta){
        case "quesosblancos":
            carpeta = ["quesosblancos","Contenedor-Productos-Quesos-Blancos", "2"];
            trriggers.ProductosPorCategoriaSearch(carpeta);
            break
        case "quesosamarillos":
            carpeta = ["quesosamarillos","Contenedor-Productos-Quesos-Amarillos","3"];
            trriggers.ProductosPorCategoriaSearch(carpeta);
            break
        case "quesoslineagourmet":
            carpeta = ["quesoslineagourmet","Contenedor-Productos-Quesos-Linea-Gourmet","4"];
            trriggers.ProductosPorCategoriaSearch(carpeta);
            break
        case "crema":
            carpeta = ["crema","Contenedor-Productos-Crema","5"];
            trriggers.ProductosPorCategoriaSearch(carpeta);
            break
        case "postres":
            carpeta = ["postres","Contenedor-Productos-Postres","6"];
            trriggers.ProductosPorCategoriaSearch(carpeta);
            break
        case "ricottines":
            carpeta = ["ricottines","Contenedor-Productos-Ricottines","7"];
            trriggers.ProductosPorCategoriaSearch(carpeta);
            break
        case "congelados":
            carpeta = ["congelados","Contenedor-Productos-Congelados","8"];
            trriggers.ProductosPorCategoriaSearch(carpeta);
            break
        case "complementos":
            carpeta = ["complementos","Contenedor-Productos-Complementos","9"];
            trriggers.ProductosPorCategoriaSearch(carpeta);
            break
        case "canastas":
            carpeta = ["canastas","Contenedor-Productos-Canastas","10"];
            trriggers.ProductosPorCategoriaSearch(carpeta);
            break
        case "yogurth":
            carpeta = ["yogurth", "Contenedor-Productos-Yogurth", "11"];
            trriggers.ProductosPorCategoriaSearch(carpeta);
            break
        default:
            const params = new URLSearchParams(window.location.search);
            trriggers.MostrarDatosPorProductoIndividual(params.get('Id'))
            break
    }
}