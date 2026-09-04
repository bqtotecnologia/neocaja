<?php

$unknownIncomeFields = [
    [
        'name' => 'price',
        'display' => 'Monto',
        'placeholder' => '',
        'id' => 'price',
        'type' => 'decimal',
        'size' => 4,
        'max' => 11,
        'min' => 1,
        'required' => false,
        'value' => $target_income['price']
    ],
    [
        'name' => 'date',
        'display' => 'Fecha',
        'placeholder' => '',
        'id' => 'date',
        'type' => 'date',
        'size' => 4,
        'required' => false,
        'value' => $target_income['date'],
    ],
    [
        'name' => 'ref',
        'display' => 'Referencia',
        'placeholder' => '',
        'id' => 'ref',
        'type' => 'text',
        'size' => 4,
        'max' => 100,
        'min' => 1,
        'required' => false,
        'value' => $target_income['ref'],
    ],
    [
        'name' => 'description',
        'display' => 'Descripción',
        'placeholder' => '',
        'id' => 'description',
        'type' => 'textarea',
        'size' => 10,
        'max' => 100,
        'min' => 1,
        'required' => false,
        'value' => $target_income['description'],
    ],
    [
        'name' => 'remote_payment',
        'display' => 'Pago remoto',
        'placeholder' => '',
        'id' => 'remote_payment',
        'type' => 'select',
        'size' => 12,
        'max' => 11,
        'min' => 0,
        'required' => false,
        'value' => $target_income['remote_payment'],
        'elements' => $form ? $display_remote_payments : []
    ],
];


if($form){
    array_push($unknownIncomeFields, ['name' => 'id', 'value' => $target_income['id']]);
}