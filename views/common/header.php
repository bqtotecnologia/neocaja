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

  <body class="nav-md" id="page-body">
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
                    <?php if(Auth::UserLevelIn(['Estudiante'])){ ?>
                      <?php 
                        include_once $_SERVER['DOCUMENT_ROOT'] . '/neocaja/models/notification_model.php';
                        $pendingNotifications = $notification_model->GetPendingNotifications($_SESSION['neocaja_cedula']);
                        ?>
                      <li><a><i class="fa fa-bell"></i> Notificaciones 
                        <?php if(count($pendingNotifications) > 0) { ?>
                          <span class="mx-2 rounded-circle bg-danger px-2 py-1 text-center fw-bold"><?= count($pendingNotifications) ?></span>
                        <?php } ?>
                        <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                          <li><a href="<?= $base_url ?>/views/tables/search_notifications.php">Ver notificaciones</a></li>
                        </ul>
                      </li>

                      <li><a><i class="fa fa-usd"></i> Pagos <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                          <li><a href="<?= $base_url ?>/views/forms/pay_form.php">Pagar</a></li>
                          <li><a href="<?= $base_url ?>/views/tables/my_payments.php">Historial de pagos</a></li>
                        </ul>
                      </li>

                      <li><a><i class="fa fa-fax"></i> Mis facturas <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                          <li><a href="<?= $base_url ?>/views/tables/search_my_invoices.php">Este periodo</a></li>
                          <li><a href="<?= $base_url ?>/views/tables/search_invoices_by_period.php">Buscar por periodo</a></li>
                          <li><a href="<?= $base_url ?>/views/tables/search_all_my_invoices.php">Ver todas</a></li>
                          <li><a href="<?= $base_url ?>/views/panel.php">Estado de cuenta</a></li>
                        </ul>
                      </li>
                    <?php } ?>

                    <!-- Cashier -->
                    <?php if(Auth::UserLevelIn(['Cajero', 'Super', 'SENIAT'])){ ?>
                      <li><a><i class="fa fa-book"></i> Facturas <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                          <?php if(Auth::UserLevelIn(['Cajero', 'Super'])){ ?>
                            <li><a href="<?= $base_url ?>/views/forms/invoice_form.php">Crear factura</a></li>
                          <?php } ?>
                          <li><a href="<?= $base_url ?>/views/tables/search_invoices_of_today.php">Facturas de hoy</a></li>
                          <li><a href="<?= $base_url ?>/views/tables/search_invoices_by_period.php">Buscar por periodo</a></li>
                        </ul>
                      </li>

                      <li><a><i class="fa fa-users"></i> Clientes <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                          <?php if(Auth::UserLevelIn(['Cajero', 'Super'])){ ?>
                            <li><a href="<?= $base_url ?>/views/forms/account_form.php">Crear cliente</a></li>
                          <?php } ?>
                          <li><a href="<?= $base_url ?>/views/tables/search_account.php">Ver clientes</a></li>
                        </ul>
                      </li>

                      <li><a><i class="fa fa-plane"></i> Pagos remotos <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                          <li><a href="<?= $base_url ?>/views/tables/search_remote_payments.php?state=Por revisar">Por revisar</a></li>
                          <li><a href="<?= $base_url ?>/views/tables/search_remote_payments.php?state=Aprobado">Aprobados</a></li>
                          <li><a href="<?= $base_url ?>/views/tables/search_remote_payments.php?state=Rechazado">Rechazados</a></li>
                          <li><a href="<?= $base_url ?>/views/tables/search_remote_payments_by_date.php">Por fecha</a></li>
                        </ul>
                      </li>

                      <li><a><i class="fa fa-question"></i> Ingresos sin identificar <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                          <li><a href="<?= $base_url ?>/views/tables/search_unknown_income_generations.php">Generaciones</a></li>
                          <li><a href="<?= $base_url ?>/views/tables/search_unknown_incomes_by_date.php">Buscar por fecha</a></li>
                          <?php if(Auth::UserLevelIn(['Cajero', 'Super'])){ ?>
                            <li><a href="<?= $base_url ?>/views/forms/import_unknown_incomes.php">Importar</a></li>
                          <?php } ?>
                          <li><a href="<?= $base_url ?>/views/tables/search_identified_incomes.php">Ingresos identificados</a></li>                          
                        </ul>
                      </li>

                      <li><a><i class="fa fa-cubes"></i> Productos <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                          <?php if(Auth::UserLevelIn(['Cajero', 'Super'])){ ?>
                            <li><a href="<?= $base_url ?>/views/forms/product_form.php">Crear producto</a></li>
                          <?php } ?>
                          <li><a href="<?= $base_url ?>/views/tables/search_product.php">Ver productos</a></li>
                        </ul>
                      </li>

                      <li><a><i class="fa fa-building"></i> Empresas <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                          <?php if(Auth::UserLevelIn(['Cajero', 'Super'])){ ?>
                            <li><a href="<?= $base_url ?>/views/forms/company_form.php">Crear empresa</a></li>
                          <?php } ?>
                          <li><a href="<?= $base_url ?>/views/tables/search_company.php">Ver empresas</a></li>
                        </ul>
                      </li>

                      <li><a><i class="fa fa-fax"></i> Puntos de venta <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                          <?php if(Auth::UserLevelIn(['Cajero', 'Super'])){ ?>
                            <li><a href="<?= $base_url ?>/views/forms/sale_point_form.php">Crear punto de venta</a></li>
                          <?php } ?>
                          <li><a href="<?= $base_url ?>/views/tables/search_sale_point.php">Ver puntos de venta</a></li>
                        </ul>
                      </li>

                      <li><a><i class="fa fa-gear"></i> Variables globales <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                          <li><a href="<?= $base_url ?>/views/tables/search_global_var.php">Ver variables globales</a></li>
                        </ul>
                      </li>

                      <li><a><i class="fa fa-sitemap"></i> Métodos de pago <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                          <?php if(Auth::UserLevelIn(['Cajero', 'Super'])){ ?>
                            <li><a href="<?= $base_url ?>/views/forms/payment_method_form.php">Crear método de pago</a></li>
                          <?php } ?>
                          <li><a href="<?= $base_url ?>/views/tables/search_payment_method.php">Ver métodos de pago</a></li>
                        </ul>
                      </li>

                      <li><a><i class="fa fa-exchange"></i> Cuentas de depósito <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                          <?php if(Auth::UserLevelIn(['Cajero', 'Super'])){ ?>
                            <li><a href="<?= $base_url ?>/views/tables/search_mobile_payments.php">Pago móvil</a></li>
                          <?php } ?>
                          <li><a href="<?= $base_url ?>/views/tables/search_transfers.php">Transferencias</a></li>
                        </ul>
                      </li>

                      <li><a><i class="fa fa-bank"></i> Bancos <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                          <?php if(Auth::UserLevelIn(['Cajero', 'Super'])){ ?>
                            <li><a href="<?= $base_url ?>/views/forms/bank_form.php">Crear banco</a></li>
                          <?php } ?>
                          <li><a href="<?= $base_url ?>/views/tables/search_bank.php">Ver bancos</a></li>
                        </ul>
                      </li>

                      <li><a><i class="fa fa-leaf"></i> Becas <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                          <?php if(Auth::UserLevelIn(['Cajero', 'Super'])){ ?>
                            <li><a href="<?= $base_url ?>/views/forms/scholarship_form.php">Crear beca</a></li>
                          <?php } ?>
                          <li><a href="<?= $base_url ?>/views/tables/search_scholarship.php">Ver becas</a></li>
                        </ul>
                      </li>
                    <?php } ?>

                    <!-- Tecnología -->
                    <?php if(Auth::UserLevelIn(['Tecnologia', 'Super', 'Cajero', 'SENIAT'])){ ?>
                    <li><a><i class="fa fa-bell"></i> Notificaciones 
                        <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                          <li><a href="<?= $base_url ?>/views/tables/search_notifications_by_account.php">Buscar por cliente</a></li>
                        </ul>
                      </li>

                      <li><a><i class="fa fa-code"></i> Bitácora <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                          <li><a href="<?= $base_url ?>/views/tables/search_binnacle_by_date_range.php">Buscar por rango de fechas</a></li>
                          <li><a href="<?= $base_url ?>/views/tables/search_binnacle_by_admin.php">Buscar por usuario</a></li>
                        </ul>
                      </li>

                      <li><a><i class="fa fa-usd"></i> Monedas <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                          <?php if(Auth::UserLevelIn(['Tecnologia', 'Super'])){ ?>
                            <li><a href="<?= $base_url ?>/views/forms/coin_form.php">Crear moneda</a></li>
                          <?php } ?>

                          <li><a href="<?= $base_url ?>/views/tables/search_coin.php">Ver monedas</a></li>
                          <!-- 
                          La siguiente vista se usa para actualizar todas las monedas mediante su
                          respectiva API y que tengan marcado auto_update.
                          Está comentado porque actualmente esta función no se usa
                          <li><a href="<?= $base_url ?>/views/forms/refresh_coins.php">Consultar API</a></li> 
                          -->
                          
                          <?php if(Auth::UserLevelIn(['Cajero', 'Super'])){ ?>
                            <li><a href="<?= $base_url ?>/views/forms/update_coin_price.php">Cambiar tasa manualmente</a></li>
                          <?php } ?>
                        </ul>
                      </li>
                    <?php } ?>

                    <!-- Super Admin -->
                    <?php if(Auth::UserLevelIn(['Super', 'SENIAT'])){ ?>
                      <li><a><i class="fa fa-user"></i> Admins <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                          <?php if(Auth::UserLevelIn(['Super'])){ ?>
                            <li><a href="<?= $base_url ?>/views/forms/admin_form.php">Agragar admin</a></li>
                          <?php } ?>
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