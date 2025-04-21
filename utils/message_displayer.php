<div class="row justify-content-center">
    <?php if (isset($_GET['error'])) { ?>
        <div class="col-12 mb-5">
            <h2 class="col-12 h3 text-center text-danger"><?= $_GET['error'] ?></h2>
        </div>
    <?php } ?>
    <?php if (isset($_GET['message'])) { ?>
        <div class="col-12 mb-5">
            <h2 class="col-12 h3 text-center text-success"><?= $_GET['message'] ?></h2>
        </div>
    <?php } ?>   
</div>