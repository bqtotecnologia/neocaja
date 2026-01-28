<?php 
$admitted_user_types = ['Estudiante'];
include_once '../../utils/validate_user_type.php';

include '../../views/common/header.php';

include_once '../../models/notification_model.php';
$notifications = $notification_model->GetNotificationsByCedula($_SESSION['neocaja_cedula']);

$notification_model->SeeAllNotifications($_SESSION['neocaja_cedula']);
?>

<div class="row justify-content-center">
    <div class="col-12 text-center">
        <h1 class="h1 text-black">Notificaciones recibidas</h1>
    </div>

    <div class="col-12 row justify-content-center px-4">
        <div class="col-12 row justify-content-center x_panel">
            <div class="table-responsive">
                <?php include '../common/tables/notification_table.php'; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../common/footer.php'; ?>