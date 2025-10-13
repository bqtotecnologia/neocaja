<?php
$admitted_user_types = ['Tecnología', 'Super'];
include_once '../../utils/validate_user_type.php';
include_once '../../utils/base_url.php';


$edit = isset($_GET['id']);
if($edit){
    include_once '../../utils/Validator.php';
    $id = Validator::ValidateRecievedId();

    if(is_string($id)){
        header("Location: $base_url/views/tables/search_coin.php?error=$id");
        exit;
    }   

    include_once '../../models/coin_model.php';
    $coin_model = new CoinModel();
    $target_coin = $coin_model->GetCoin($id);
    if($target_coin === false){
        header("Location: $base_url/views/tables/search_coin.php?error=Moneda no encontrada");
        exit;
    }
}

include_once '../common/header.php';
include_once '../../utils/FormBuilder.php';

$fields = [
    [
        'name' => 'name',
        'display' => 'Nombre',
        'placeholder' => 'Nombre de la moneda',
        'id' => 'name',
        'type' => 'text',
        'size' => 8,
        'max' => 50,
        'min' => 3,
        'required' => true,
        'value' => $edit ? $target_coin['name'] : ''
    ],
    [
        'name' => 'price',
        'display' => 'Tasa',
        'placeholder' => '',
        'id' => 'price',
        'type' => 'decimal',
        'size' => 4,
        'required' => false,
        'value' => $edit ? $target_coin['price'] : '0',
        'hidden' => !$edit,
        'disabled' => $edit
    ],
    [
        'name' => 'url',
        'display' => 'URL',
        'placeholder' => 'URL que da como respuesta JSON la tasa actual de la moneda',
        'id' => 'url',
        'type' => 'text',
        'size' => 12,
        'required' => true,
        'value' => $edit ? $target_coin['url'] : ''
    ],
    [
        'name' => 'active',
        'display' => 'Activo',
        'placeholder' => '',
        'id' => 'active',
        'type' => 'checkbox',
        'size' => 4,
        'required' => false,
        'value' => $edit ? [$target_coin['active']] : ['1'],
        'elements' => [
            [
                'display' => 'Activo',
                'value' => '1'
            ]
        ]
    ],
    [
        'name' => 'auto_update',
        'display' => 'Actualizar automáticamente',
        'placeholder' => '',
        'id' => 'auto_update',
        'type' => 'checkbox',
        'size' => 4,
        'required' => false,
        'value' => $edit ? [$target_coin['auto_update']] : ['1'],
        'elements' => [
            [
                'display' => 'Actualizar al ingresar al sistema',
                'value' => '1'
            ]
        ]
    ],
];

if($edit){
    $id_field = [
        'name' => 'id',
        'value' => $target_coin['id']
    ];
    array_push($fields, $id_field);
}

$formBuilder = new FormBuilder(
    '../../controllers/handle_coin.php',    
    'POST',
    ($edit ? 'Editar' : 'Registrar nueva') . ' moneda',
    ($edit ? 'Editar' : 'Registrar'),
    '',
    $fields
);

?>

<div class="row justify-content-center">
    <div class="col-12 row justify-content-center x_panel">
        <?php $btn_url = '../tables/search_coin.php'; include_once '../layouts/backButton.php'; ?>
    </div>
    
    <div class="col-12 justify-content-center px-5 mt-4">            
        <?php $formBuilder->DrawForm(); ?>
    </div>
</div>

<?php if($edit) { ?>
    <script>
        document.getElementById('name').readOnly = true
    </script>
<?php } ?>


<?php include_once '../common/footer.php'; ?>