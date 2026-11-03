<?php

$periodRangeFields = [
    [
        'name' => 'period',
        'id' => 'period',
        'display' => 'Periodo',
        'placeholder' => '',
        'type' => 'select',
        'size' => 8,
        'required' => true,
        'suspicious' => true,
        'value' => $edit && $form ? $target_period_range['period'] : '',
        'elements' => $form ? $display_periods : []
    ],
    [
        'name' => 'start_month',
        'id' => 'start_month',
        'display' => 'Desde',
        'placeholder' => '',
        'type' => 'select',
        'size' => 8,
        'required' => true,
        'suspicious' => true,
        'value' => $edit && $form ? $target_period_range['start_month'] : '',
        'elements' => $form ? $display_months : []
    ],
    [
        'name' => 'end_month',
        'id' => 'end_month',
        'display' => 'Hasta',
        'placeholder' => '',
        'type' => 'select',
        'size' => 8,
        'required' => true,
        'suspicious' => true,
        'value' => $edit && $form ? $target_period_range['end_month'] : '',
        'elements' => $form ? $display_months : []
    ],
];

if($edit && $form){
    $id_field = [
        'name' => 'id',
        'value' => $target_period_range['id']
    ];
    array_push($periodRangeFields, $id_field);
}