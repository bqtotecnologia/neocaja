<?php
$admitted_user_types = ['Super', 'Cajero', 'Administrador'];
include_once '../../utils/validate_user_type.php';
include_once '../../utils/months_data.php';

$edit = isset($_GET['id']);
$form = true;
$error = '';

if ($edit) {
    include_once '../../utils/Validator.php';
    $id = Validator::ValidateRecievedId();

    if (is_string($id)) {
        $error = $id;
    }
}


if ($error === '' && $edit) {
    include_once '../../models/absolution_model.php';
    $absolution_model = new AbsolutionModel();
    
    $target_absolution = $absolution_model->GetAbsolutionById($id);
    if ($target_absolution === false) 
        $error = 'Absolución no encontrada';
    else
        $concepts = $absolution_model->GetAbsolutionConcepts($id);
}

if ($error !== '') {
    header("Location: $base_url/views/tables/search_absolutions.php?error=$error");
    exit;
}

include_once '../../models/account_model.php';
include_once '../../models/siacad_model.php';

$account_model = new AccountModel();
$siacad = new SiacadModel();

$students = $account_model->GetStudents();
$periods = $siacad->GetPeriods();

include_once '../common/header.php';

?>

<div class="row justify-content-center">
    <div class="col-12 row justify-content-center x_panel">
        <?php $btn_url = '../tables/search_absolutions.php';
        include_once '../layouts/backButton.php'; ?>
    </div>

    <div class="col-12 justify-content-center px-5 mt-4">
        <div class="col-12 justify-content-center px-5 mt-4">
            <form action="../../controllers/handle_absolution.php" method="POST" id="" class="d-flex justify-content-center align-items-center flex-column x_panel confirm-form ">
                <?php if ($edit) { ?>
                    <input type="hidden" name="id" value="<?= $target_absolution['id'] ?>">
                <?php } ?>
                <div class="col-12 text-center">
                    <h1 class="h1 text-black"><?= $edit ? 'Editar' : 'Registrar' ?> Absolución</h1>
                </div>
                <div class=" row col-12 col-lg-8 my-2 justify-content-start ">
                    <div class=" row m-0 p-0 align-items-center justify-content-center justify-content-lg-end col-12 col-lg-4 ">
                        <label class=" h6 m-0 fw-bold px-2 " for="account">Estudiante</label>
                    </div>
                    <div class=" row col-12 col-lg-6 m-0 p-0 justify-content-center justify-content-lg-start ">
                        <select name="account" id="account" required class="form-control col-10 col-md-5 select2">
                            <option value="">&nbsp;</option>
                            <?php foreach ($students as $student) { ?>
                                <option 
                                    value="<?= $student['id'] ?>"
                                    <?php if ($edit) { if (intval($student['id']) === intval($target_absolution['account_id'])) echo 'selected'; } ?>
                                    >
                                    <?= $student['surnames'] . ' ' . $student['names'] . ' (' . $student['cedula'] . ')' ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class=" row col-12 col-lg-8 my-2 justify-content-start ">
                    <div class=" row m-0 p-0 align-items-center justify-content-center justify-content-lg-end col-12 col-lg-4 ">
                        <label class=" h6 m-0 fw-bold px-2 " for="period">Periodo</label>
                    </div>
                    <div class=" row col-12 col-lg-6 m-0 p-0 justify-content-center justify-content-lg-start ">
                        <select name="period" id="period" required class="form-control col-10 col-md-5 select2">
                            <option value="">&nbsp;</option>
                            <?php foreach ($periods as $period) { ?>
                                <option
                                    value="<?= $period['idperiodo'] ?>"
                                    <?php if ($edit) {
                                        if (intval($period['idperiodo']) === intval($target_absolution['period'])) echo 'selected'; ?>
                                    <?php } else if ($period['actual'] === true) {
                                        echo 'selected';
                                    } ?>>
                                    <?= $period['nombreperiodo'] ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>


                <div class=" row col-12 col-lg-8 my-2 justify-content-start ">
                    <div class=" row m-0 p-0 align-items-center justify-content-center justify-content-lg-end col-12 col-lg-4 ">
                        <label class=" h6 m-0 fw-bold px-2 " for="">Conceptos</label>
                    </div>
                    <div class=" row col-12 col-lg-8 m-0 p-0 justify-content-center justify-content-lg-start ">
                        <div class="row col-12 m-0 p-0 mx-2 my-1 align-items-center">
                            <input 
                            id="FOC" 
                            type="checkbox" 
                            name="concepts[]" 
                            value="FOC" 
                            <?php if($edit) { if(in_array('FOC', $concepts)) echo 'checked'; } ?>
                            class="form-control col-10 col-md-5 flat align-middle" 
                            >
                            <label for="FOC" class="text-left mx-2 my-0 h6">
                                FOC
                            </label>
                        </div>

                        <?php foreach ($month_translate as $number => $month) { ?>
                            <div class="row col-12 m-0 p-0 mx-2 my-1 align-items-center">
                                <input 
                                id="<?= $number ?>" 
                                type="checkbox" 
                                name="concepts[]" 
                                value="<?= $number ?>" 
                                <?php if($edit) { if(in_array($number, $concepts)) echo 'checked'; } ?>
                                class="form-control col-10 col-md-5 flat align-middle">
                                <label for="<?= $number ?>" class="text-left mx-2 my-0 h6">
                                    <?= $month ?>
                                </label>
                            </div>
                        <?php } ?>
                        
                    </div>
                </div>

                <div class=" row col-12 col-lg-8 my-2 justify-content-start ">
                    <div class=" row m-0 p-0 align-items-center justify-content-center justify-content-lg-end col-12 col-lg-4 ">
                        <label class=" h6 m-0 fw-bold px-2 " for="reason">Motivo</label>
                    </div>
                    <div class="row col-12 col-lg-6 m-0 p-0 justify-content-center justify-content-lg-start">
                        <textarea
                            name="reason"
                            id="reason"
                            required
                            maxlength="255"
                            class="form-control"><?= $edit ? $target_absolution['reason'] : '' ?></textarea>
                    </div>
                </div>

                <div>
                    <h3 class="text-danger" id="error-displayer"></h3>
                </div>
                <div class="row col-12 m-0 p-0 justify-content-center">
                    <button type="submit" class="btn btn-success form-builder-submit">
                        <?= $edit ? 'Editar' : 'Registrar' ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once '../common/footer.php'; ?>