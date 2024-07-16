<!-- header.php -->

<header class="container d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
    <div class="col-md-3 mb-2 mb-md-0">
        <a href="/" class="d-inline-flex link-body-emphasis text-decoration-none">
            <svg class="bi" width="40" height="32" role="img" aria-label="Bootstrap">
                <use xlink:href="#bootstrap"></use>
            </svg>
        </a>
    </div>

    <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
        <li><a href="/pagPeliculasBootstrap/frontend/index.html" class="nav-link px-2 link-secondary">Inicio</a></li>
        <li><a href="/pagPeliculasBootstrap/frontend/src/pages/peliculas.html" class="nav-link px-2">Películas</a></li>
        <li><a href="/pagPeliculasBootstrap/frontend/src/pages/peliculas.html" class="nav-link px-2">Series</a></li>
        <li><a href="/pagPeliculasBootstrap/frontend/src/pages/juegos.html" class="nav-link px-2">Juegos</a></li>
        <li><a href="/pagPeliculasBootstrap/frontend/src/pages/colabora.html" class="nav-link px-2">Colabora</a></li>
    </ul>

    <div class="col-md-3 text-end">
        <?php
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['user_email'])) {
            echo '<span class="me-2">Logueado como: ' . htmlspecialchars($_SESSION['user_email']) . ' (' . htmlspecialchars($_SESSION['user_rol']) . ')</span>';
            echo '<button type="button" class="btn btn-outline-primary me-2"><a href="/pagPeliculasBootstrap/backend/public/api/logout.php" class="no-underline">Desloguearse</a></button>';
        } else {
            echo '<button type="button" class="btn btn-outline-primary me-2"><a href="/pagPeliculasBootstrap/frontend/src/pages/inicioSesion.html" class="no-underline">Login</a></button>';
            echo '<button type="button" class="btn btn-outline-primary me-2"><a href="/pagPeliculasBootstrap/frontend/src/pages/registro.html" class="no-underline">Registrarse</a></button>';
        }
        ?>
    </div>
</header>