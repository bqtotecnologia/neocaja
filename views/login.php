<?php 
session_start();
if(isset($_SESSION['eva_user_id']) && isset($_SESSION['eva_tipo'])){
  if(in_array($_SESSION['eva_tipo'], ['Admin', 'Super', 'Administración'])){
    header('Location: views/panel.php');
    exit;
  }
}

$errors = [
  'Por favor, rellene todos los campos',
  'Credenciales inválidas',
  'Usted no puede evaluar aún',
  'Usted ya ha evaluado a todos sus docentes',
  'Inicio de sesión requerido',
  'No le quedan docentes por evaluar',
  'Usted intenta evaluar en una periodo y/o fecha inválida',
  'El usuario contiene caracteres sospechosos',
  'Usted no es un usuario activo en este periodo',
  'Sesión perdida. Utilice solo un sistema del IUJO a la vez.'
];

$messages = [
  'Muchas gracias por evaluar a sus docentes'
];

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Sistema de evaluación docente</title>

    <!-- Bootstrap -->
    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="../vendors/animate.css/animate.min.css" rel="stylesheet">

    <!-- Title icon -->
    <link rel="apple-touch-icon" href="../images/iujo.ico">
    <link rel="shortcut icon" href="../images/iujo.ico">

    <!-- Custom Theme Style -->
    <link href="../build/css/custom.min.css" rel="stylesheet">
    <link href="../build/css/mycustom.css" rel="stylesheet">
  </head>

  <body class="login">
    <div>
      <a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a>

      <div class="row justify-content-center m-0">
        <figure class="row col-12 justify-content-center m-0">
          <img class="col-12 col-md-4" src="../images/iujo-transparent.png" alt="iujo_logo">
        </figure>

      </div>
      <div class="login_wrapper">
        <div class="animate form login_form">
          <section class="login_content">
            <form method="POST" id="loginForm" action="../controllers/login.php">
              <?php if(isset($_GET['error'])) { ?>
                <div>
                  <span class="h5 text-danger"><?= $errors[$_GET['error']] ?></span>
                </div>
              <?php } ?>
  
              <?php if(isset($_GET['message'])) { ?>
                <div>
                  <span class="h5 text-success"><?= $messages[$_GET['message']] ?></span>
                </div>
              <?php } ?>
              <span class="h5">Sistema de evaluación docente</span>
              <div class="mt-3">
                <input type="text" name="user" class="form-control" placeholder="Usuario" required="" />
              </div>
              <div>
                <input type="password" name="password" class="form-control" placeholder="Contraseña" required="" />
              </div>
              <div class="text-danger my-2" id="error-displayer">
              </div>
              

              <div>
                <button type="submit" class="btn btn-primary">Ingresar</a>
              </div>

              <div class="clearfix"></div>

              <div class="separator">
                <div>
                <a class="no-hover" href="https://www.iujobarquisimeto.edu.ve/" target="_blank">
                  <span class="h3">IUJO Barquisimeto</span>
                </a>
                  <p>©2023 All Rights Reserved.</p>
                </div>
              </div>
            </form>
          </section>
        </div>
      </div>
    </div>
    <script>
      var myForm = document.getElementById("loginForm");
      myForm.addEventListener('submit', function(event){
          event.preventDefault()
          const suspiciousRegex = /[<>/;\"'\-(){}\[\]$\\|&\?\=\¿\¡!]/;
          var error = false

          if(myForm['user'].value === '' || myForm['password'].value === ''){
            error = true
            PrintError('Rellene todos los campos')
          }

          if (suspiciousRegex.test(myForm['user'].value)){
                error = true
                PrintError('Alguno de los campos contiene caracteres sospechosos: < > / \\ ; " ( ) { } [ ] $ & | ¿ ? ¡ ! = - ' + "'")
          }
          if(error === false) myForm.submit()
      });

      function PrintError(error_message){
          error_space = document.getElementById("error-displayer").innerHTML = error_message;
      }
    </script>
  </body>
</html>