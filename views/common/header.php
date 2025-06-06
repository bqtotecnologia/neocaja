<!-- Neocaja system Made by Atlantox https://atlantox.pythonanywhere.com/ -->
<?php
  // Para evitar errores, si la sesión no está iniciada, la iniciamos
  if (session_status() === PHP_SESSION_NONE)
      session_start();

  include_once  $_SERVER['DOCUMENT_ROOT'] . '/neocaja/utils/base_url.php';

  include_once $_SERVER['DOCUMENT_ROOT'] . '/neocaja/utils/Auth.php';
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>IUJO | Sistema de caja</title>

    <?php include_once 'partials/styles_imports.php'; ?>  

  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col menu_fixed">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
            <figure class="rounded bg-white w-100 m-2 text-center">
                  <a href="<?= $base_url ?>/views/panel.php" class="site_title text-center p-0 m-0">
                    <img class="w-100" src="<?= $base_url ?>/images/iujo-transparent.png" alt="iujo logo png">
                </a>
            </figure>
            </div>

            <div class="clearfix"></div>

            <br />

            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">

              <div class="menu_section">
                <ul class="nav side-menu">
                  <?php if(isset($_SESSION['neocaja_rol'])){ ?>

                    <!-- Student -->
                    <?php if(Auth::UserLevelIn(['Estudiante', 'Super'])){ ?>
                      <li><a><i class="fa fa-fax"></i> Pagos <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                          <li><a href="<?= $base_url ?>/views/confirm_payment.php">Conciliar pago</a></li>
                          <li><a href="<?= $base_url ?>/views/my_payments.php">Historial de pagos</a></li>
                        </ul>
                      </li>
                    <?php } ?>

                    <!-- Cashier -->
                    <?php if(Auth::UserLevelIn(['Cajero', 'Super'])){ ?>
                      <li><a><i class="fa fa-fax"></i> Facturas <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                          <li><a href="<?= $base_url ?>/views/forms/invoice_form.php">Crear factura</a></li>
                          <li><a href="<?= $base_url ?>/views/searchers/search_invoice.php">Buscar factura</a></li>
                        </ul>
                      </li>

                      <li><a><i class="fa fa-cubes"></i> Productos <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                          <li><a href="<?= $base_url ?>/views/forms/product_form.php">Crear producto</a></li>
                          <li><a href="<?= $base_url ?>/views/tables/search_product.php">Ver productos</a></li>
                        </ul>
                      </li>
                    <?php } ?>

                    <!-- Supervisor -->
                    <?php if(Auth::UserLevelIn(['Supervisor', 'Cajero', 'Super'])){ ?>
                      <li><a><i class="fa fa-code"></i> Bitácora <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                          <li><a href="<?= $base_url ?>/views/forms/binnacle_by_date_range.php">Buscar por rango de fechas</a></li>
                          <li><a href="<?= $base_url ?>/views/forms/binnacle_by_admin.php">Buscar por usuario</a></li>
                        </ul>
                      </li>
                    <?php } ?>

                    <!-- Super Admin -->
                    <?php if(Auth::UserLevelIn(['Super'])){ ?>
                      <li><a><i class="fa fa-user"></i> Admins <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                          <li><a href="<?= $base_url ?>/views/forms/admin_form.php">Agragar admin</a></li>
                          <li><a href="<?= $base_url ?>/views/tables/search_admin.php">Ver admins</a></li>
                        </ul>
                      </li>
                    <?php } ?>

                  <?php } ?>
                  


                </ul>
              </div>
            </div>
            <!-- /sidebar menu -->
          </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">
          <div class="nav_menu">
              <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
              </div>
              <nav class="nav navbar-nav">
              <ul class=" navbar-right">

                <li class="nav-item dropdown open" style="padding-left: 15px;">
                  <a href="javascript:;" class="user-profile dropdown-toggle h6" aria-haspopup="true" id="navbarDropdown" data-toggle="dropdown" aria-expanded="false"
                  style="text-transform:uppercase"
                  >
                    Bienvenido(a) <?= $_SESSION['neocaja_name'] . ' ' . $_SESSION['neocaja_surname'] ?>
                  </a>
                  <div class="dropdown-menu dropdown-usermenu pull-right" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item"  href="<?= $base_url ?>/controllers/logout.php"> Cerrar sesión</a>
                  </div>
                </li>

              </ul>
            </nav>
          </div>
        </div>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">