<?php

$bankFields = [
    [
        'name' => 'name',
        'id' => 'name',
        'display' => 'Nombre y código',
        'placeholder' => 'Nombre y código del banco',
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
        'id' => 'active',
        'display' => 'Activo',
        'placeholder' => '',
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