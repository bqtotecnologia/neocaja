<?php

session_start();

include_once '../utils/Auth.php';
include_once '../models/account_model.php';
$account_model = new AccountModel();

if(Auth::UserLevelIn(['Estudiante']))
    $account_model->CreateBinnacle('NULL', $_SESSION['neocaja_fullname'] . ' cerró sesión');
else
    $account_model->CreateBinnacle($_SESSION['neocaja_id'], 'Cerró sesión');

session_destroy();
header('Location: ../index.php');