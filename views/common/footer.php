<!-- Evadoc system Made by Atlantox https://atlantox.pythonanywhere.com/ -->
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

    <!-- jQuery -->
    <script src="<?= $my_url ?>/vendors/jquery/dist/jquery.min.js"></script>
    <script src="<?= $my_url ?>/vendors/jquery/dist/jQuery.print.min.js"></script>

    <!-- Bootstrap -->
   <script src="<?= $my_url ?>/vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

    <!-- FastClick -->
    <script src="<?= $my_url ?>/vendors/fastclick/lib/fastclick.js"></script>

    <!-- NProgress -->
    <script src="<?= $my_url ?>/vendors/nprogress/nprogress.js"></script>

    <!-- iCheck -->
	  <script src="<?= $my_url ?>/vendors/iCheck/icheck.min.js"></script>

    <!-- jQuery custom content scroller -->
    <script src="<?= $my_url ?>/vendors/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>

    <!-- select2 -->
    <script src="<?= $my_url ?>/vendors/select2/dist/js/select2.min.js" rel="stylesheet"></script>

    <!-- DataTable -->
    <script src="<?= $my_url ?>/vendors/datatable/js/jquery.dataTables.min.js"></script>
    <script src="<?= $my_url ?>/vendors/datatable/js/spanish.js"></script>
    <script src="<?= $my_url ?>/vendors/datatable/js/moment.min.js"></script>
    <script src="<?= $my_url ?>/vendors/datatable/js/datetime.js"></script>
    <script src="<?= $my_url ?>/vendors/datatable/js/dataTables.buttons.min.js"></script>
    <script src="<?= $my_url ?>/vendors/datatable/js/jszip.min.js"></script>
    <script src="<?= $my_url ?>/vendors/datatable/js/buttons.html5.min.js"></script>
    <script src="<?= $my_url ?>/vendors/datatable/js/buttons.print.min.js"></script>

    <!-- Pdfmake -->
    <script src="<?= $my_url ?>/vendors/pdfmake/build/pdfmake.js"></script>
    <script src="<?= $my_url ?>/vendors/pdfmake/build/vfs_fonts.js"></script>

    <!-- Sweetalert2 -->
    <script src="<?= $my_url ?>/vendors/sweetalert2/sweetalert2.min.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="<?= $my_url ?>/build/js/custom.min.js"></script>
    <script src="<?= $my_url ?>/build/js/mycustom.js"></script>
    <script src="<?= $my_url ?>/build/js/datatables_initializer.js"></script>
    
    <!-- Showing messages -->
    <?php if (isset($_GET['error'])) { ?>
        <script>
            Swal.fire({
              title: "Error",
              icon:'error',
              text: "<?= $_GET['error'] ?>",
            })
        </script>
    <?php } ?>

    <?php if (isset($_GET['message'])) { ?>
        <script>
            Swal.fire({
              title: "Mensaje",
              icon:'success',
              text: "<?= $_GET['message'] ?>",
            })
        </script>
    <?php } ?>
  </body>
</html>