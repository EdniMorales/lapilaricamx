<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Lacteos | La Pilarica</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="<?= base_url ?>assets/logo-resplandor-bco.png" />
    <!-- Bootstrap icons-->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css"
        integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <!--   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/> -->
    <link href="<?= base_url ?>css/styles.css" rel="stylesheet" />
    <link href="<?= base_url ?>css/stilo.css" rel="stylesheet" />
    <!--  <link href="css/hover-zoom.css" rel="stylesheet" /> -->
    <link href="<?= base_url ?>css/animate.css" rel="stylesheet" />
    <!--  <link href="css/base.css" rel="stylesheet" /> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!--  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
    <script type="module" src='<?= base_url ?>js/scripts.js'></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <script src="<?= base_url ?>js/wow.min.js"></script>
    <script>
    new WOW().init();
    </script>
</head> 

<body class="d-flex flex-column h-100">
    <main class="flex-shrink-0">
        <nav class="navbar navbar-expand-lg fixed-top" id="navbar">
            <div class="container px-5">
                <a class="navbar-brand" href="<?= base_url ?>Principal/index">
                    <img src="<?= base_url ?>assets/logo-resplandor-bco.png" alt="Logo" width="150" height="100"
                        class="d-inline-block align-text-top" />
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                             <li class="nav-item">
                            <a class="nav-link" href="<?= base_url ?>Principal/index">Inicio |</a>
                        </li>
                        <li class="nav-item dropdown" id="dmenu">
                            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Especialidades |</a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownBlog"
                                id="Categorias"></ul>
                        </li>
                        <!--<li class="nav-item"><a class="nav-link" href="about.html">About</a></li>-->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" id="navbarDropdownBlog" href="#" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">Contactanos |</a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownBlog">
                                <li><a class="dropdown-item" href="../Bolsa/index">Bolsa de Trabajo</a></li>
                                <li><a class="dropdown-item" href="../Bolsa/index">Quiero ser cliente</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url ?>Solo/index">Solo el Amor Supera la Leche</a>
                        </li>
                    </ul>
                    <div class="searchbar">
                        <input class="search_input" id="buscador-prod-index" type="text" name="" placeholder="Buscar" />
                        <a href="#" class="search_icon" id="button-buscador-prod-index">
                            <i class="fa fa-search fa-1x text-left" style="font-size: 18px; position: fixed;" aria-hidden="true"></i>
                        </a>
                        <div id="dropdown-index"></div>
                    </div>
                </div>
            </div>
        </nav>