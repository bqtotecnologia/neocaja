<?php

$globalVarFields = [
    [
        'name' => 'name',
        'display' => 'Nombre',
        'placeholder' => 'Nombre',
        'id' => 'name',
        'type' => 'text',
        'size' => 8,
        'max' => 255,
        'min' => 5,
        'required' => false,
        'disabled' => true,
        'suspicious' => false,
        'value' => $target_global_var['name']
    ],
    [
        'name' => 'value',
        'display' => 'Valor',
        'placeholder' => 'Valor',
        'id' => 'value',
        'type' => 'decimal',
        'size' => 4,
        'max' => 11,
        'min' => 1,
        'required' => true,
        'suspicious' => true,
        'value' => $target_global_var['value']
    ],
];

if($form){
    array_push($globalVarFields, ['name' => 'id', 'value' => $target_global_var['id']]);
}