<?php

$invoiceFields = [
    [
        'name' => 'invoice-period',
        'min' => 1,
        'max' => 15,
        'required' => true,
        'type' => 'string',
        'suspicious' => false,
    ],
    [
        'name' => 'cedula',
        'min' => 1,
        'max' => 11,
        'required' => true,
        'type' => 'integer',
        'suspicious' => true,
    ],
    [
        'name' => 'cedula_letter',
        'min' => 1,
        'max' => 1,
        'required' => true,
        'type' => 'string',
        'suspicious' => true,
    ],
    [
        'name' => 'rate-date',
        'min' => 8,
        'max' => 10,
        'required' => true,
        'type' => 'date',
        'suspicious' => false,
    ],
    [
        'name' => 'observation',
        'min' => 0,
        'max' => 255,
        'required' => false,
        'type' => 'string',
        'suspicious' => true,
    ],
    [
        'name' => 'known-income',
        'min' => 0,
        'max' => 11,
        'required' => false,
        'type' => 'integer',
        'suspicious' => true,
    ]
];