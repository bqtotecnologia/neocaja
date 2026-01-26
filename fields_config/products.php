<?php

$productFields = [
    [
        'name' => 'name',
        'display' => 'Nombre',
        'placeholder' => 'Nombre del producto',
        'id' => 'name',
        'type' => 'text',
        'size' => 8,
        'max' => 255,
        'min' => 1,
        'required' => true,
        'suspicious' => true,
        'value' => $edit ? $target_product['name'] : ''
    ],
    [
        'name' => 'price',
        'display' => 'Precio',
        'placeholder' => 'Precio ($)',
        'id' => 'price',
        'type' => 'decimal',
        'size' => 4,
        'required' => true,
        'suspicious' => true,
        'value' => $edit ? $target_product['price'] : ''
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
        'value' => $edit ? [$target_product['active']] : ['1'],
        'elements' => [['display' => 'Activo','value' => '1']]
    ],
];

if($edit && $form){
    $id_field = [
        'name' => 'id',
        'value' => $target_product['id']
    ];
    array_push($productFields, $id_field);
}