<?php
session_start();
// Destruimos la sesión y redirigimos a login
session_unset();
session_destroy();
header('Location: ../view/principal.php');
exit;