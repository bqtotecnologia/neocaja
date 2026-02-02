<?php

$invoiceFields = [
    'invoice_number' => [
        'min' => 1,
        'max' => 11,
        'required' => true,
        'type' => 'integer',
        'suspicious' => true,
    ],
    'control_number' => [
        'min' => 1,
        'max' => 11,
        'required' => true,
        'type' => 'integer',
        'suspicious' => true,
    ],
    'account' => [
        'min' => 1,
        'max' => 11,
        'required' => true,
        'type' => 'integer',
        'suspicious' => true,
    ],
    'rate-date' => [
        'min' => 8,
        'max' => 10,
        'required' => true,
        'type' => 'date',
        'suspicious' => false,
    ],
    'observation' => [
        'min' => 0,
        'max' => 255,
        'required' => false,
        'type' => 'string',
        'suspicious' => true,
    ],
    'known-income' => [
        'min' => 0,
        'max' => 11,
        'required' => false,
        'type' => 'integer',
        'suspicious' => true,
    ]
];