<?php
$companyFields = [
    [
        'name' => 'name',
        'display' => 'Nombre',
        'placeholder' => 'Nombre de la compañía',
        'id' => 'name',
        'type' => 'text',
        'size' => 8,
        'max' => 255,
        'min' => 1,
        'required' => true,
        'value' => $edit ? $target_company['name'] : ''
    ],
    [
        'name' => 'rif_letter',
        'display' => 'Letra del rif',
        'placeholder' => '',
        'id' => 'rif_letter',
        'type' => 'select',
        'min' => 1,
        'max' => 1,
        'size' => 3,
        'required' => true,
        'value' => $edit ? $target_company['rif_letter'] : 'V',
        'elements' => $display_rif_letters
    ],
    [
        'name' => 'rif_number',
        'display' => 'Rif',
        'placeholder' => '9 dígitos',
        'id' => 'rif_number',
        'type' => 'text',
        'min' => 9,
        'max' => 9,
        'size' => 5,
        'required' => true,
        'value' => $edit ? $target_company['rif_number'] : '',
    ],
    [
        'name' => 'address',
        'display' => 'Dirección',
        'placeholder' => 'Dirección de la empresa',
        'id' => 'address',
        'type' => 'textarea',
        'size' => 8,
        'required' => true,
        'value' => $edit ? $target_company['address'] : '',
    ],
];

if($edit && $form){
    $id_field = [
        'name' => 'id',
        'value' => $target_company['id']
    ];
    array_push($companyFields, $id_field);
}