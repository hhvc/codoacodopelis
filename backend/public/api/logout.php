<?php
session_start();
session_unset();
session_destroy();
header('Location: /pagPeliculasBootstrap/frontend/index.html');
exit();
?>