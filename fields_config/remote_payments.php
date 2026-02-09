<?php

$remotePaymentFields = [
    [
        'name' => 'cedula',
        'type' => 'text',
        'max' => 11,
        'min' => 7,
        'required' => true,
        'suspicious' => true,
    ],
    [
        'name' => 'document',
        'type' => 'text',
        'max' => 20,
        'min' => 7,
        'required' => true,
        'suspicious' => true,
    ],
    [
        'name' => 'ref',
        'type' => 'text',
        'max' => 20,
        'min' => 6,
        'required' => true,
        'suspicious' => true,
    ],
    [
        'name' => 'price',
        'type' => 'decimal',
        'required' => true,
        'suspicious' => true,
    ],
    [
        'name' => 'payment_method',
        'type' => 'text',
        'max' => 11,
        'min' => 1,
        'required' => true,
        'suspicious' => true,
    ],
    [
        'name' => 'payment_method_type',
        'type' => 'text',
        'max' => 20,
        'min' => 5,
        'required' => true,
        'suspicious' => true,
    ],
];  