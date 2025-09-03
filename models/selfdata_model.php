<?php

include_once 'SQL_model.php';

class SelfDataModel extends SQLModel
{
    /**
     * Retorna la información de la empresa como un array asociativo
     */
    public function GetSelfData(){
        return parent::GetRow("SELECT * FROM self_data LIMIT 1");
    }    
}