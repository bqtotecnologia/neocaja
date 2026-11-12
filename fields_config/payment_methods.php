<?php

$paymentMethodFields = [
    [
        'name' => 'name',
        'id' => 'name',
        'display' => 'Nombre',
        'placeholder' => '',
        'type' => 'text',
        'size' => 8,
        'max' => 255,
        'min' => 5,
        'required' => true,
        'suspicious' => true,
        'value' => $edit & $form ? $target_payment_method['name'] : ''
    ],    
    [
        'name' => 'document_number',
        'id' => 'document_number',
        'display' => 'Requiere número de documento',
        'placeholder' => '',
        'type' => 'checkbox',
        'size' => 8,
        'required' => false,
        'suspicious' => true,
        'value' => $edit & $form ? [intval($target_payment_method['document_number'])] : [],
        'elements' => [['value' => 1, 'display' => '']]
    ],
    [
        'name' => 'bank',
        'id' => 'bank',
        'display' => 'Requiere banco',
        'placeholder' => '',
        'type' => 'checkbox',
        'size' => 8,
        'required' => false,
        'suspicious' => true,
        'value' => $edit & $form ? [intval($target_payment_method['bank'])] : [],
        'elements' => [['value' => 1, 'display' => '']]
    ],
    [
        'name' => 'sale_point',
        'id' => 'sale_point',
        'display' => 'Requiere punto de venta',
        'placeholder' => '',
        'type' => 'checkbox',
        'size' => 8,
        'required' => false,
        'suspicious' => true,
        'value' => $edit & $form ? [intval($target_payment_method['sale_point'])] : [],
        'elements' => [['value' => 1, 'display' => '']]
    ],
];

if($edit && $form){
    $id_field = [
        'name' => 'id',
        'value' => $target_payment_method['id']
    ];
    array_push($paymentMethodFields, $id_field);
}