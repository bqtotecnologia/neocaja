<?php 
$admitted_user_types = ['Cajero', 'Super', 'SENIAT'];
include_once '../../utils/validate_user_type.php';
include_once '../../utils/Validator.php';

$error = '';
$id = Validator::ValidateRecievedId();
if(is_string($id)){
    $error = $id;
}

if($error === ''){
    include_once '../../models/unknown_incomes_model.php';
    $unknown_model = new UnknownIncomesModel();
    
    $target_generation = $unknown_model->GetUnknownIncomesGeneration($id);
    if($target_generation === false)
        $error = 'Generación de ingresos no identificados no encontrada';
}

if($error !== ''){
    header("Location: $base_url/views/search_unknown_incomes_generations.php?error=$error");
    exit;
}

$incomes = $unknown_model->GetUnknownIncomesOfGeneration($id);

include '../../views/common/header.php';

?>

<div class="row justify-content-center">
    <div class="col-12 text-center">
        <h1 class="h1 text-black">Ingresos registrados del <?= date('d/m/Y', strtotime($target_generation['created_at'])) ?></h1>
    </div>    

    <div class="col-12 row justify-content-center px-4">
        <div class="col-12 row justify-content-center x_panel">
            <div class="table-responsive">
                <?php include '../common/tables/unknown_incomes_table.php'; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../common/footer.php'; ?>