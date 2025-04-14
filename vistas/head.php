<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Lacteos | La Pilarica</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/logo-resplandor-bco.png"/>
    <!-- Bootstrap icons-->
    <link
      rel="stylesheet"
      href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
      integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO"
      crossorigin="anonymous"
    />
    <link
      rel="stylesheet"
      href="https://use.fontawesome.com/releases/v5.5.0/css/all.css"
      integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU"
      crossorigin="anonymous"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css"
      rel="stylesheet"
    />
    <!--   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/> -->
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/styles.css" rel="stylesheet" />
    <link href="css/stilo.css" rel="stylesheet" />
   <!--  <link href="css/hover-zoom.css" rel="stylesheet" /> -->
    <link href="css/animate.css" rel="stylesheet" />
   <!--  <link href="css/base.css" rel="stylesheet" /> -->
   
   
  </head>
  <body class="d-flex flex-column h-100">
    <main class="flex-shrink-0">
      <!-- Navigation-->
      <nav
        class="navbar navbar-expand-lg fixed-top"
        style="background-color: #1b1295" 
        id="navbar"
      >
        <!-- sticky-top -->
        <div class="container px-5">
          <a class="navbar-brand" href="index.php">
            <img
              src="assets/logo-resplandor-bco.png"
              alt="Logo"
              width="150"
              height="100"
              class="d-inline-block align-text-top"
            />
          </a>
          <!--  <a class="navbar-brand" href="index.html">Lacteos La Pilarica</a> -->
          <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent"
            aria-expanded="false"
            aria-label="Toggle navigation"
          >
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
              <li class="nav-item dropdown" id="dmenu">
                <a
                  class="nav-link dropdown-toggle"
                  id="navbarDropdown"
                  href="#"
                  role="button"
                  data-bs-toggle="dropdown"
                  aria-haspopup="true"
                  aria-expanded="false"
                  >Productos</a
                >
                <ul
                  class="dropdown-menu dropdown-menu-end"
                  aria-labelledby="navbarDropdownBlog"
                >
                  <li>
                    <a class="dropdown-item" href="blog-home.html"
                      >Quesos Blancos</a
                    >
                  </li>
                  <li>
                    <a class="dropdown-item" href="blog-post.html"
                      >Quesos Amarillos</a
                    >
                  </li>
                  <li>
                    <a class="dropdown-item" href="blog-post.html"
                      >Quesos Gourmet</a
                    >
                  </li>
                  <li>
                    <a class="dropdown-item" href="blog-post.html">Cremas</a>
                  </li>
                  <li>
                    <a class="dropdown-item" href="blog-post.html"
                      >Mantequillas</a
                    >
                  </li>
                  <li>
                    <a class="dropdown-item" href="blog-post.html">Postres</a>
                  </li>
                  <li>
                    <a class="dropdown-item" href="blog-post.html">Yogurth</a>
                  </li>
                  <li>
                    <a class="dropdown-item" href="blog-post.html">Ricottin</a>
                  </li>
                </ul>
                <!-- <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                  <a class="dropdown-item" href="#">Action</a>
                                  <a class="dropdown-item" href="#">Another action</a>
                                  <div class="dropdown-divider"></div>sfd{gkzdfklñhjvzsdikoñvjnxfkl}
                                  <a class="dropdown-item" href="#">Something else here</a>
                                </div> -->
                <!-- PRUEBA -->
              </li>
              <!--<li class="nav-item"><a class="nav-link" href="about.html">About</a></li>-->
              <li class="nav-item dropdown">
                <a
                  class="nav-link dropdown-toggle"
                  id="navbarDropdownBlog"
                  href="#"
                  role="button"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                  >Contactanos</a
                >
                <ul
                  class="dropdown-menu dropdown-menu-end"
                  aria-labelledby="navbarDropdownBlog"
                >
                  <li>
                    <a class="dropdown-item" href="about.html"
                      >Bolsa de Trabajo</a
                    >
                  </li>
                </ul>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="amor.php"
                  >Solo el Amor Supera la Leche</a
                >
              </li>
              <div class="searchbar">
                <input
                  class="search_input"
                  id="buscador-prod-index"
                  type="text"
                  name=""
                  placeholder="Buscar ..."
                />
                <a href="#" class="search_icon" id="button-buscador-prod-index">
                  <i class="fa fa-search" aria-hidden="true"></i>
                </a>
                <div id="dropdown-index"></div>
              </div>
              <!--     <li class="nav-item"><a class="nav-link" href="pricing.html">Pricing</a></li>
                            <li class="nav-item"><a class="nav-link" href="faq.html">FAQ</a></li> -->
              <!--   <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" id="navbarDropdownBlog" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Blog</a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownBlog">
                                    <li><a class="dropdown-item" href="blog-home.html">Blog Home</a></li>
                                    <li><a class="dropdown-item" href="blog-post.html">Blog Post</a></li>
                                </ul>
                            </li> -->
              <!--      <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" id="navbarDropdownPortfolio" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Portfolio</a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownPortfolio">
                                    <li><a class="dropdown-item" href="portfolio-overview.html">Portfolio Overview</a></li>
                                    <li><a class="dropdown-item" href="portfolio-item.html">Portfolio Item</a></li>
                                </ul>
                            </li> -->
            </ul>
          </div>
        </div>
      </nav>