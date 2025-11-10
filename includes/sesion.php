<?php
// includes/sesion.php

// Inicia sesión solo si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Si no existe un usuario logueado, redirige al login
if (!isset($_SESSION['id_usuario']) || empty($_SESSION['id_usuario'])) {
    header("Location: ../view/login.php");
    exit;
}

// Tiempo máximo de inactividad (2 minutos)
$inactividad_maxima = 120; // segundos

// Verifica si ha pasado el tiempo máximo de inactividad
if (isset($_SESSION['ultima_actividad']) && (time() - $_SESSION['ultima_actividad'] > $inactividad_maxima)) {
    // Cierra sesión por inactividad
    session_unset();
    session_destroy();
    header("Location: ../view/login.php?timeout=1");
    exit;
}

// Actualiza el tiempo de última actividad
$_SESSION['ultima_actividad'] = time();
?>
