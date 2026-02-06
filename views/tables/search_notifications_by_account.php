<?php
$admitted_user_types = ['Super', 'Tecnologia', 'Cajero', 'SENIAT'];
include_once '../../utils/validate_user_type.php';
include_once '../../utils/Validator.php';
include_once '../../utils/Auth.php';

$target_student = null;
if(!empty($_POST)){
    $error = '';
    $id = Validator::ValidateRecievedId('student', 'POST');
    if(is_string($id))
        $error = $id;

    if($error === ''){
        include_once '../../models/account_model.php';
        $account_model = new AccountModel();        
        $target_student = $account_model->GetAccount($id);
        if($target_student === false)
            $error = 'cliente no encontrado';
    }

    if($error !== ''){
        header("Location: $base_url/views/panel.php?error=$error");
        exit;
    }
}

include_once '../../models/notification_model.php';
$notification_model = new NotificationModel();

$students = $notification_model->GetAccountsWithNotifications();

$display_students = [];
foreach($students as $s){
    $to_add = [
        'display' => $s['fullname'],
        'value' => $s['account_id']
    ];
    array_push($display_students, $to_add);
}

$formFields = [
    [
        'name' => 'student',
        'display' => 'Estudiante',
        'placeholder' => '',
        'id' => 'student',
        'type' => 'select',
        'size' => 9,
        'max' => 11,
        'min' => 0,
        'required' => true,
        'suspicious' => true,
        'value' => $target_student === null ? '' : $target_student['id'],
        'elements' => $display_students,
    ],
];


include_once '../../utils/FormBuilder.php';

$formBuilder = new FormBuilder(
    '',
    'POST',
    '',
    'Buscar',
    '',
    $formFields,
    false
);

include '../common/header.php';

?>

<div class="row justify-content-center">
    <div class="row col-12 my-3 p-0">
        <div class="col-12">
            <h1 class="w-100 text-center h1">
                Buscar notificaciones por cliente
            </h1>
        </div>
    </div>

    <div class="row col-12 align-items-center justify-content-center">
        <?=  $formBuilder->DrawForm() ?>
    </div>
    
    <?php if($target_student !== null) {  ?>
        <?php 
            include_once '../../models/notification_model.php';
            $notificacion_model = new NotificationModel();    
            $notifications = $notificacion_model->GetNotificationsByCedula($target_student['cedula']);
        ?>
    
        <div class="x_panel row col-12 my-3 p-0">
            <div class="col-12">
                <h1 class="w-100 text-center h1">
                    Notificaciones de <?= $target_student['names'] . ' ' . $target_student['surnames'] ?>
                </h1>
            </div>

            <div class="col-12">
                <div class="table-responsive">
                    <?php include_once '../common/tables/notification_table.php'; ?>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<?php include '../common/footer.php'; ?>