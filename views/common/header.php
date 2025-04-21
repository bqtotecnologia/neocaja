<!-- Evadoc system Made by Atlantox https://atlantox.pythonanywhere.com/ -->
<?php
// Para evitar errores, si la sesión no está iniciada, la iniciamos
if (session_status() === PHP_SESSION_NONE)
    session_start();

    $my_url = 'http://localhost/neocaja';
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>IUJO | Sistema de caja</title>

    <!-- Bootstrap -->
    <link href="<?= $my_url ?>/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?= $my_url ?>/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="<?= $my_url ?>/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="<?= $my_url ?>/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <!-- jQuery custom content scroller -->
    <link href="<?= $my_url ?>/vendors/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css" rel="stylesheet"/>
    <!-- select2 -->
    <link href="<?= $my_url ?>/vendors/select2/dist/css/select2.min.css" rel="stylesheet" />

    <link rel="apple-touch-icon" href="<?= $my_url ?>/images/iujo.ico">
    <link rel="shortcut icon" href="<?= $my_url ?>/images/iujo.ico">

    <link href="<?= $my_url ?>/vendors/select2/dist/css/select2.min.css" rel="stylesheet" />
    <link href="<?= $my_url ?>/vendors/datatable/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="<?= $my_url ?>/vendors/datatable/css/jquery.dataTables.css" rel="stylesheet" />

    <!-- Custom Theme Style -->
    <link href="<?= $my_url ?>/build/css/custom.min.css" rel="stylesheet">
    <link href="<?= $my_url ?>/build/css/mycustom.css" rel="stylesheet">

  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col menu_fixed">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
            <figure class="rounded bg-white w-100 m-2 text-center">
                  <a href="<?= $my_url ?>/views/panel.php" class="site_title text-center p-0 m-0">
                    <img class="w-100" src="<?= $my_url ?>/images/iujo-transparent.png" alt="iujo logo png">
                </a>
            </figure>
            </div>

            <div class="clearfix"></div>

            <br />

            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">

              <div class="menu_section">
                <ul class="nav side-menu">
                  <?php if(isset($_SESSION['neocaja_tipo'])){ ?>

                    <!-- Estudiantes -->
                    <?php if(in_array($_SESSION['neocaja_tipo'], ['Estudiante', 'Super'])){ ?>
                      <li><a><i class="fa fa-fax"></i> Pagos <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                          <li><a href="<?= $my_url ?>/views/confirm_payment.php">Conciliar pago</a></li>
                          <li><a href="<?= $my_url ?>/views/my_payments.php">Historial de pagos</a></li>
                        </ul>
                      </li>
                    <?php } ?>

                    <!-- Cajero -->
                    <?php if(in_array($_SESSION['neocaja_tipo'], ['Cajero', 'Super'])){ ?>
                      <li><a><i class="fa fa-fax"></i> Facturas <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                          <li><a href="<?= $my_url ?>/views/forms/invoice_form.php">Crear factura</a></li>
                          <li><a href="<?= $my_url ?>/views/searchers/search_invoice.php">Buscar factura</a></li>
                        </ul>
                      </li>

                      <li><a><i class="fa fa-cubes"></i> Productos <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                          <li><a href="<?= $my_url ?>/views/forms/product_form.php">Crear producto</a></li>
                          <li><a href="<?= $my_url ?>/views/tables/search_product.php">Ver productos</a></li>
                        </ul>
                      </li>
                    <?php } ?>

                    <!-- Coordinador -->
                    <?php if(in_array($_SESSION['neocaja_tipo'], ['coord'])){ ?>
                      <li><a><i class="fa  fa-check-square-o"></i> Evaluaciones <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                          <li><a href="evaluar.php">Evaluar docente</a></li>
                          <li><a href="made_evaluations.php">Evaluaciones realizadas</a></li>
                          <li>
                            <form class="h-100" style="padding:9px;" action="search_docente.php" method="POST">
                              <input name="docente" value="<?= $_SESSION['neocaja_user_id'] ?>" type="hidden">
                              <button class="button-none" href="recieved_evaluations.php">Evaluaciones recibidas</button>
                            </form>
                          </li>
                        </ul>
                      </li>
                      <li><a><i class="fa fa-bar-chart"></i> Estadísticas <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                          <li><a href="search_docente.php">Docentes de la carrera</a></li>
                        </ul>
                      </li>
                    <?php } ?>

                    <!-- Docente -->
                    <?php if(in_array($_SESSION['neocaja_tipo'], ['teacher'])){ ?>
                      <li><a><i class="fa fa-check-square-o"></i> Evaluaciones <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                          <li><a href="evaluar.php">Evaluar coordinador</a></li>
                          <li><a href="made_evaluations.php">Evaluaciones realizadas</a></li>
                          <li>
                            <form class="h-100" style="padding:9px;" action="search_docente.php" method="POST">
                              <input name="docente" value="<?= $_SESSION['neocaja_user_id'] ?>" type="hidden">
                              <button class="button-none" href="recieved_evaluations.php">Evaluaciones recibidas</button>
                            </form>
                          </li>
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
                    <a class="dropdown-item"  href="../controllers/logout.php"> Cerrar sesión</a>
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