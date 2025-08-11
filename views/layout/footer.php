</main>
<footer class="text-center bg-body-tertiary text-muted">
    <section class="d-flex justify-content-center justify-content-lg-between p-5 border-bottom container">
        <div class="me-3 d-none d-lg-block text-center tam-l">
            <span class="title-3">¡Siguenos en nuestras redes sociales!</span>
        </div>
        <div>
            <a href="" class="me-4">
                <i class="fab fa-facebook-square fa-3x"></i>
            </a>
            <a href="" class="me-4">
                <i class="fab fa-instagram fa-3x"></i>
            </a>
            <a href="" class="me-4">
                <i class="fab fa-linkedin-square fa-3x"></i>
            </a>
        </div>
    </section>
    <section class="">
        <div class="container text-center text-md-start mt-5">
            <div class="row mt-3">
                <div class="col-md-3 col-lg-4 col-xl-3 mx-auto mb-4">
                    <h6 class="text-uppercase fw-bold mb-4">
                        <i class="fas fa-gem me-3"></i>Lacteos La Pilarica
                    </h6>
                    <p class="text-text-left">
                        En Lácteos la Pilarica sabemos que todos tenemos preferencias
                        diferentes, por eso contamos con una extensa gama de productos,
                        elaborados con el mismo amor de siempre.
                    </p>
                </div>
                <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mb-4">
                    <!-- Links -->
                    <h6 class="text-uppercase fw-bold mb-4">Productos</h6>
                    <p>
                        <a href="../quesosblancos/index" class="text-reset">Quesos Blancos</a>
                    </p>
                    <p>
                        <a href="../quesosamarillos/index" class="text-reset">Quesos Amarillos</a>
                    </p>
                    <p>
                        <a href="../quesoslineagourmet/index" class="text-reset">Quesos Linea Gourmet</a>
                    </p>
                    <p>
                        <a href="../crema/index" class="text-reset">Cremas</a>
                    </p>
                    <p>
                        <a href="../canastas/index" class="text-reset">Regala una canasta</a>
                    </p>
                </div>
                <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mb-4">
                    <!-- Links -->
                    <h6 class="text-uppercase fw-bold mb-4">Disfruta con</h6>
                    <p>
                        <a href="../postres/index" class="text-reset">Postres</a>
                    </p>
                    <p>
                        <a href="../ricottines/index" class="text-reset">Ricottines</a>
                    </p>
                    <p>
                        <a href="../yogurth/index" class="text-reset">Yogures</a>
                    </p>
                    <p>
                        <a href="../congelados/index" class="text-reset">Congelados</a>
                    </p>
                    <p>
                        <a href="../complementos/index" class="text-reset">Complementos</a>
                    </p>
                </div>
                <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
                    <h6 class="text-uppercase fw-bold mb-4">Ubicanos</h6>
                    <p>
                        <i class="fas fa-home me-3"></i> Benito Juárez Sur N 46, Centro,
                        56530 Ixtapaluca, Méx.
                    </p>
                    <p>
                        <i class="fas fa-envelope me-3"></i>
                        soporte@pilarica.mx
                    </p>
                    <p><i class="fas fa-phone me-3"></i>(55) 5972 0102</p>
                    <p><i class="fab fa-whatsapp me-3"></i>(56) 4543 8166</p>
                </div>
            </div>
        </div>
    </section>
    <aside class="bg-primary bg-gradient rounded-3 p-4 p-sm-5 mt-5">
        <div
            class="d-flex align-items-center justify-content-between flex-column flex-xl-row text-center text-xl-start">
            <div class="mb-4 mb-xl-0 container">
                <div class="title-6">
                    ¡ Suscríbete gratis para recibir ofertas, noticias, promociones, recetas y más...!
                </div>
            </div>
            <div class="ms-xl-4">
                <div class="input-group">
                    <input class="form-control" id="CasillaFooterSuscripcionCorreo" type="text"
                        placeholder="¡Ingresa tu correo!" aria-label="Correo Electronico..."
                        aria-describedby="button-newsletter" />
                    <button class="btn btn-outline-light" id="BotonFooterSuscribirse" type="button">
                        Registrarse
                    </button>
                </div>
                <div class="small text-white-50">
                    Nos preocupamos por la privacidad y nunca compartiremos sus datos.
                </div>
            </div>
        </div>
    </aside>
    <div class="text-center text-white p-4" style="background-color: #380000">
        © 2025 Copyright:
        <a class="" href="https://teampcmx.com/">Lacteos La Pilarica</a>
    </div>
</footer>
<script>
var prevScrollpos = window.pageYOffset;
window.onscroll = function() {
    var currentScrollPos = window.pageYOffset;
    if (prevScrollpos > currentScrollPos) {
        document.getElementById("navbar").style.top = "0";
    } else {
        document.getElementById("navbar").style.top = "-150px";
    }
    prevScrollpos = currentScrollPos;
};
</script>
<script>
$(".dropdown").on("show.bs.dropdown", function() {
    $(this)
        .find(".dropdown-menu")
        .first()
        .stop(true, true)
        .slideToggle(1000);
});
$(".dropdown").on("hide.bs.dropdown", function() {
    $(this)
        .find(".dropdown-menu")
        .first()
        .stop(true, true)
        .slideToggle(1000);
});
</script>
<script type="module">
import * as trriggers from '<?= base_url ?>backend/trigger.js'; // Importar las funciones desde el módulo
// Asocia los eventos con los elementos del HTML
document.getElementById("buscador-prod-index").addEventListener("input", function() {
    const buttonString = "buscador-prod-index";
    const pageString = "index";
    const widgetString = "dropdown-index";
    trriggers.ProductSearch(buttonString, pageString, widgetString);
}); // Barra de navegación
document.getElementById("button-buscador-prod-index").addEventListener("click", function() {
    const buttonString = "buscador-prod-index"; // El item con informacion
    const pageString = "index";
    const widgetString = "dropdown-index";
    trriggers.ProductSearch(buttonString, pageString, widgetString); // Pasa el string a la función
}); // Formulario de quejas y sugerencias
document.getElementById("BotonEnviarQS").addEventListener("click", function() {
    const Formulario = "FormularioQS";
    trriggers.EnviarDatosDelFormulario(Formulario); // Ejecutar la funcion que envia los datos al servidor
}); // Suscripcion del correo
document.getElementById("BotonFooterSuscribirse").addEventListener("click", function() {
    const casillaCorreo = "CasillaFooterSuscripcionCorreo";
    const casillaUsuario = "NombreFormQS";
    const casillaApellido = "ApellidoFormQS";
    trriggers.SuscribirCorreoPilaricaNews(casillaCorreo, casillaUsuario, casillaApellido);
}); // Botón de búsqueda
//document.getElementById("navbarSupportedContent").addEventListener("click", function(){
//  const pageString = "index";
//  const widgetString = "Categorias";// El dropdown con las categorias
//  trriggers.CategoriasSearch(pageString, widgetString)
//});// Boton de las categorias
</script>
</body>

</html>