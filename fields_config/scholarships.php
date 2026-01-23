<?php
$scholarshipFields = [
    [
        'name' => 'name',
        'display' => 'Nombre',
        'placeholder' => 'Nombre de la beca',
        'id' => 'name',
        'type' => 'text',
        'size' => 8,
        'max' => 100,
        'min' => 5,
        'required' => true,
        'value' => $edit ? $target_scholarship['name'] : ''
    ],
];

if($edit && $form){
    $id_field = [
        'name' => 'id',
        'value' => $target_scholarship['id']
    ];
    array_push($scholarshipFields, $id_field);
}