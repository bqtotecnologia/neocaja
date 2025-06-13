<!-- Neocaja system Made by Atlantox https://atlantox.pythonanywhere.com/ -->
  </div>
</div>
        <!-- /page content -->

        <!-- footer content -->
        <footer>
          <div class="pull-right">
             <a target="_blank" href="https://www.iujobarquisimeto.edu.ve/">IUJO Barquisimeto</a>
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
      </div>
    </div>

    <?php include_once 'partials/scripts_imports.php'; ?>
    
    <!-- Showing messages -->
    <?php if (isset($_GET['error'])) { ?>
        <script>
            Swal.fire({
              title: "Error",
              icon:'error',
              html: '<?= $_GET['error'] ?>',
            })
        </script>
    <?php } ?>

    <?php if (isset($_GET['message'])) { ?>
        <script>
            Swal.fire({
              title: "Mensaje",
              icon:'success',
              html: '<?= $_GET['message'] ?>',
            })
        </script>
    <?php } ?>
  </body>
</html>