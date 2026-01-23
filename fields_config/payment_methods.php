<?php

$paymentMethodFields = [
    [
        'name' => 'name',
        'display' => 'Nombre',
        'placeholder' => '',
        'id' => 'name',
        'type' => 'text',
        'size' => 8,
        'max' => 255,
        'min' => 5,
        'required' => true,
        'suspicious' => true,
        'value' => $edit ? $target_payment_method['name'] : ''
    ],    
];

if($edit && $form){
    $id_field = [
        'name' => 'id',
        'value' => $target_payment_method['id']
    ];
    array_push($paymentMethodFields, $id_field);
}