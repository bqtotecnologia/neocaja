<?php

$transferFields = [
    [
        'name' => 'account_number',
        'display' => 'Número de cuenta',
        'placeholder' => 'Número de cuenta',
        'id' => 'phone',
        'type' => 'text',
        'size' => 8,
        'max' => 20,
        'min' => 20,
        'required' => true,
        'suspicious' => true,
        'value' => $edit ? $target_transfer['account_number'] : ''
    ],
    [
        'name' => 'document_letter',
        'display' => 'Letra de documento',
        'placeholder' => '',
        'id' => 'document_letter',
        'type' => 'select',
        'min' => 1,
        'max' => 1,
        'size' => 3,
        'required' => true,
        'suspicious' => true,
        'value' => $edit ? $target_transfer['document_letter'] : 'V',
        'elements' => $display_rif_letters
    ],
    [
        'name' => 'document_number',
        'display' => 'Documento',
        'placeholder' => 'Número de Rif/Cédula',
        'id' => 'document-number',
        'type' => 'text',
        'min' => 7,
        'max' => 45,
        'size' => 5,
        'required' => true,
        'suspicious' => true,
        'value' => $edit ? $target_transfer['document_number'] : '',
    ],
    [
        'name' => 'bank',
        'display' => 'Banco',
        'placeholder' => '',
        'id' => 'bank',
        'type' => 'select',
        'min' => 1,
        'max' => 11,
        'size' => 6,
        'required' => true,
        'suspicious' => true,
        'value' => $edit ? $target_transfer['bank_id'] : '',
        'elements' => $display_banks
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
        'value' => $edit ? [$target_transfer['active']] : ['1'],
        'elements' => [['display' => 'Activo','value' => '1']]
    ],
];

if($edit && $form){
    $id_field = [
        'name' => 'id',
        'value' => $target_transfer['id']
    ];
    array_push($transferFields, $id_field);
}