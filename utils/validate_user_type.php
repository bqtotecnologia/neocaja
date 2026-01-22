<?php
$base_url = 'http://' . $_SERVER['HTTP_HOST'] . '/neocaja';

if (session_status() === PHP_SESSION_NONE)
    session_start();

if(!in_array($_SESSION['neocaja_rol'], $admitted_user_types)){
    session_destroy();
    header("Location: $base_url");
    exit;
}