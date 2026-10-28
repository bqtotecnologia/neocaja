<?php
$base_url = 'http://' . $_SERVER['HTTP_HOST'] . '/neocaja';
$logout_user = false;

if (session_status() === PHP_SESSION_NONE)
    session_start();

$logout_user = !isset($_SESSION['neocaja_rol']);

if($logout_user === false){
    $logout_user = !in_array($_SESSION['neocaja_rol'], $admitted_user_types);
}

if($logout_user){
    session_destroy();
    header("Location: $base_url");
    exit;
}