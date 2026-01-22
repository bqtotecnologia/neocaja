<?php

$bankFields = [
    [
        'name' => 'name',
        'display' => 'Nombre y código',
        'placeholder' => 'Nombre y código del banco',
        'id' => 'name',
        'type' => 'text',
        'size' => 8,
        'max' => 255,
        'min' => 5,
        'required' => true,
        'suspicious' => true,
        'value' => $edit ? $target_bank['name'] : ''
    ],
    [
        'name' => 'active',
        'display' => 'Activo',
        'placeholder' => '',
        'id' => 'active',
        'type' => 'checkbox',
        'size' => 4,
        'required' => false,
        'suspicious' => true,
        'value' => $edit ? [$target_bank['active']] : ['1'],
        'elements' => [['display' => 'Activo', 'value' => '1']]
    ],
];

if($edit && $form){
    $id_field = [
        'name' => 'id',
        'value' => $target_bank['id']
    ];
    array_push($bankFields, $id_field);
}