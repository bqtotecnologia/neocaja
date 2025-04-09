<?php
session_start();
if(!in_array($_SESSION['neocaja_tipo'], $admitted_user_types)){
    session_destroy();
    header("Location: $base_url");
    exit;
}