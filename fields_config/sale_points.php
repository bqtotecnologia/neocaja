<?php

$salePointFields = [
    [
        'name' => 'code',
        'id' => 'code',
        'display' => 'Código',
        'placeholder' => 'Código del punto de venta',
        'type' => 'text',
        'size' => 5,
        'min' => 1,
        'max' => 9,
        'required' => true,
        'suspicious' => true,
        'value' => $edit ? $target_sale_point['code'] : ''
    ],    
    [
        'name' => 'bank',
        'id' => 'bank',
        'display' => 'Banco',
        'placeholder' => '',
        'type' => 'select',
        'size' => 8,
        'required' => true,
        'suspicious' => true,
        'value' => $edit ? $target_sale_point['bank_id'] : '',
        'elements' => $display_banks
    ],
];

if($edit && $form){
    $id_field = [
        'name' => 'id',
        'value' => $target_sale_point['id']
    ];
    array_push($salePointFields, $id_field);
}