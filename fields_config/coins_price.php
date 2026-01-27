<?php

$coinPriceFields = [
    [
        'name' => 'coin',
        'display' => 'Moneda',
        'placeholder' => '',
        'id' => 'coin',
        'type' => 'select',
        'size' => 4,
        'max' => 11,
        'min' => 1,
        'required' => true,
        'value' => isset($_GET['id']) ? $_GET['id'] : '',
        'elements' => $display_coins
    ],
    [
        'name' => 'price',
        'display' => 'Tasa',
        'placeholder' => '',
        'id' => 'price',
        'type' => 'decimal',
        'size' => 4,
        'required' => true,
        'value' => '0',
    ],
    [
        'name' => 'date',
        'display' => 'Fecha',
        'placeholder' => '',
        'id' => 'date',
        'type' => 'date',
        'size' => 4,
        'required' => true,
        'value' => date('Y-m-d'),
        'max' => date('Y-m-d'),
    ],
];