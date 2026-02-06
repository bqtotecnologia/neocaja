<?php

$invoiceFields = [
    [
        'name' => 'invoice_number',
        'min' => 1,
        'max' => 11,
        'required' => true,
        'type' => 'integer',
        'suspicious' => true,
    ],
    [
        'name' => 'control_number',
        'min' => 1,
        'max' => 11,
        'required' => true,
        'type' => 'integer',
        'suspicious' => true,
    ],
    [
        'name' => 'account',
        'min' => 1,
        'max' => 11,
        'required' => true,
        'type' => 'integer',
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