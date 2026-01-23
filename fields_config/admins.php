<?php

$adminFields = [
    [
        'name' => 'cedula',
        'display' => 'Cedula',
        'placeholder' => 'Cédula del administrador',
        'id' => 'cedula',
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
        'display' => 'Nombre',
        'placeholder' => 'Nombre del administrador',
        'id' => 'name',
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
        'display' => 'Rol',
        'placeholder' => '',
        'id' => 'role',
        'type' => 'select',
        'size' => 3,
        'required' => true,
        'suspicious' => true,
        'value' => $edit ? $target_admin['role_id'] : '',
        'elements' => $form ? $display_roles : []
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
        'value' => $edit ? [$target_admin['active']] : ['1'],
        'elements' => [['display' => 'Activo','value' => '1']]
    ],
];

if($edit && $form){
    $id_field = [
        'name' => 'id',
        'value' => $target_admin['admin_id']
    ];
    array_push($adminFields, $id_field);
}