<?php

$dateRangeFields = [
    [
        'name' => 'start_date',
        'id' => 'start_date',
        'display' => 'Fecha de inicio',
        'type' => 'date',
        'size' => 6,
        'placeholder' => '',
        'value' => empty($_POST) ? '' : $_POST['start_date'],
        'required' => true,
        'suspicious' => false,
    ],
    [
        'name' => 'end_date',
        'id' => 'end_date',
        'display' => 'Fecha de fin',
        'type' => 'date',
        'size' => 6,
        'placeholder' => '',
        'value' => empty($_POST) ? '' : $_POST['end_date'],
        'required' => true,
        'suspicious' => false,
    ],  
];
