<?php

$adminFields = [
    [
        'name' => 'cedula',
        'id' => 'cedula',
        'display' => 'Cedula',
        'placeholder' => 'Cédula del administrador',
        'type' => 'text',
        'size' => 5,
        'max' => 11,
        'min' => 7,
        'required' => true,
        'suspicious' => true,
        'value' => $edit ? $target_admin['cedula'] : ''
    ],
    [
        'name' => 'name',
        'id' => 'name',
        'display' => 'Nombre',
        'placeholder' => 'Nombre del administrador',
        'type' => 'text',
        'size' => 8,
        'max' => 50,
        'min' => 5,
        'required' => true,
        'suspicious' => true,
        'value' => $edit ? $target_admin['name'] : ''
    ],
    [
        'name' => 'role',
        'id' => 'role',
        'display' => 'Rol',
        'placeholder' => '',
        'type' => 'select',
        'size' => 5,
        'required' => true,
        'suspicious' => true,
        'value' => $edit ? $target_admin['role_id'] : '',
        'elements' => $form ? $display_roles : []
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
        'value' => $edit ? [$target_admin['active']] : ['1'],
        'elements' => [['display' => 'Activo','value' => '1']]
    ],
    [
        'name' => 'allow_credit_note',
        'id' => 'allow_credit_note',
        'display' => 'Permitir crear nota de crédito',
        'placeholder' => '',
        'type' => 'checkbox',
        'size' => 4,
        'required' => false,
        'suspicious' => true,
        'value' => $edit ? [$target_admin['allow_credit_note']] : ['0'],
        'elements' => [['display' => 'Permitir','value' => '1']]
    ],
    [
        'name' => 'allow_annull_invoice',
        'id' => 'allow_annull_invoice',
        'display' => 'Permitir anular factura',
        'placeholder' => '',
        'type' => 'checkbox',
        'size' => 4,
        'required' => false,
        'suspicious' => true,
        'value' => $edit ? [$target_admin['allow_annull_invoice']] : ['0'],
        'elements' => [['display' => 'Permitir','value' => '1']]
    ]
];

if($edit && $form){
    $id_field = [
        'name' => 'id',
        'value' => $target_admin['admin_id']
    ];
    array_push($adminFields, $id_field);
}