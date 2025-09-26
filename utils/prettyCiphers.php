<?php

function GetPrettyCiphers($str){
    if(is_numeric($str)){
        if(strpos($str, '.') === false)
            $display = $str . '.00';
        else
            $display = round(floatval($str), 2);

        if(strpos($display, '.') === false)
            $display = $display . '.00';

        $splits = explode('.', $display);
        $intPart = $splits[0];
        $intPart = mb_str_split($intPart);
        $decimalPart = $splits[1];
        $finalResult = '';
        $digitCount = 0;
        for ($i=(count($intPart) - 1); $i >= 0 ; $i--) { 
            $currentChar = $intPart[$i];

            if($digitCount === 3){
                $currentChar .= '.';
                $digitCount = 0;
            }
            else{
                $digitCount++;
            }

            $finalResult = $currentChar . $finalResult;
        }
        $finalResult .= ',' . $decimalPart;
    }
    else
        $finalResult = $str;

    return $finalResult;
}
