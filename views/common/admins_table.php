<table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr class="text-center">
            <th class="" style="padding-right:15px !important;">Nº</th>
            <th class="">Cedula</th>
            <th class="">Nivel</th>
            <th class="">Acción</th>
        </tr>
    </thead>

    <tbody>
        <?php
        $count = 1;
        ?>
        <?php foreach($admins as $admin) {  ?>
            <tr class="h6">
                <td class="align-middle"><?php echo $count; $count++; ?></td>
                <td class="align-middle"><?= $admin['cedula'] ?></td>
                <td class="align-middle"><?= ucfirst($admin['nivel']) ?></td>
                <td class="">
                    <div class="row justify-content-center">
                        <div class="col-3 text-center">
                            <a href="add_admin.php?edit=1&id= <?= $admin['id'] ?>" class="btn btn-success">
                                <i class="fa fa-pencil"></i>
                            </a>
                        </div>
                        <form class="col-3 text-center confirm-form" method="POST" action="../controllers/handle_admin.php?delete=1&id=<?= $admin['id'] ?>">
                            <input type="hidden" name="id" value="<?= $admin['id'] ?>">
                            <input type="hidden" name="cedula" value="<?= $admin['cedula'] ?>">
                            <input type="hidden" name="nivel" value="<?= $admin['nivel'] ?>">
                            <input type="hidden" name="delete" value="1">
                            <button class="btn btn-danger">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>