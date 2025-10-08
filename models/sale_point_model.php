<?php

include_once 'SQL_model.php';

class SalePointModel extends SQLModel
{    
    public $SINGLE_SELECT_TEMPLATE = "SELECT
        sale_points.id,
        sale_points.code,
        sale_points.created_at,
        banks.id as bank_id,
        banks.name as bank
        FROM
        sale_points
        INNER JOIN banks ON banks.id = sale_points.bank ";

    public function CreateSalePoint(array $data){
        $code = $data['code'];
        $bank = $data['bank'];

        $created = parent::DoQuery("INSERT INTO sale_points (code, bank) VALUES ('$code', $bank)");

        if($created)
            $result = $this->GetSalePointByCode($code);
        else
            $result = false;
        
        return $result;
    }

    public function GetSalePoints(){
        $sql = $this->SINGLE_SELECT_TEMPLATE;
        return parent::GetRows($sql, true);
    }

    public function GetSalePoint(string $id){
        return parent::GetRow($this->SINGLE_SELECT_TEMPLATE . " WHERE sale_points.id = '$id'"); 
    }

    public function GetSalePointByCode(string $code){
        return parent::GetRow($this->SINGLE_SELECT_TEMPLATE . " WHERE sale_points.code = '$code'"); 
    }    

    public function UpdateSalePoint(string $id, array $data){
        $code = $data['code'];
        $bank = $data['bank'];

        $sql = "UPDATE sale_points SET code = '$code', bank = $bank WHERE sale_points.id = $id";
        return parent::DoQuery($sql);
    }
}