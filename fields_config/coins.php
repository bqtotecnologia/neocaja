<?php

$coinFields = [
    [
        'name' => 'name',
        'display' => 'Nombre',
        'placeholder' => 'Nombre de la moneda',
        'id' => 'name',
        'type' => 'text',
        'size' => 8,
        'max' => 50,
        'min' => 3,
        'required' => !$edit,
        'value' => $edit ? $target_coin['name'] : '',
        'disabled' => $edit
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
        'name' => 'active',
        'display' => 'Activo',
        'placeholder' => '',
        'id' => 'active',
        'type' => 'checkbox',
        'size' => 4,
        'required' => false,
        'value' => $edit ? [$target_coin['active']] : ['1'],
        'elements' => [['display' => 'Activo','value' => '1']]
    ],
];

if($edit && Auth::UserLevelIn(['Tecnologia', 'Super']) || Auth::UserLevelIn(['Super'])){
    array_push($coinFields, 
    // URL de la API que se consulta automáticamente para la tasa
    [
        'name' => 'url',
        'display' => 'URL',
        'placeholder' => 'URL que da como respuesta JSON la tasa actual de la moneda',
        'id' => 'url',
        'type' => 'text',
        'size' => 12,
        'required' => false,
        'value' => $edit ? $target_coin['url'] : ''
    ],
    // Checkbox que habilita la actualización automática de la tasa de la moneda mediante una API
    [
        'name' => 'auto_update',
        'display' => 'Actualizar automáticamente',
        'placeholder' => '',
        'id' => 'auto_update',
        'type' => 'checkbox',
        'size' => 4,
        'required' => false,
        'value' => $edit ? [$target_coin['auto_update']] : ['1'],
        'elements' => [['display' => 'Actualizar al ingresar al sistema','value' => '1']]
    ],);
}

if($edit && $form){
    $id_field = [
        'name' => 'id',
        'value' => $target_coin['id']
    ];
    array_push($coinFields, $id_field);
}