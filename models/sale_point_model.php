<?php

include_once 'SQL_model.php';

class SalePointModel extends SQLModel
{    
    public function CreateSalePoint(array $data){
        $code = $data['code'];
        $created = parent::DoQuery("INSERT INTO sale_points (code) VALUES ('$code')");

        if($created)
            $result = $this->GetSalePointByCode($code);
        else
            $result = false;
        
        return $result;
    }

    public function GetSalePoints(){
        $sql = "SELECT 
            *
            FROM
            sale_points
            ORDER BY
            code";

        return parent::GetRows($sql, true);
    }

    public function GetSalePoint(string $id){
        return parent::GetRow("SELECT * FROM sale_points WHERE id = '$id'"); 
    }

    public function GetSalePointByCode(string $code){
        return parent::GetRow("SELECT * FROM sale_points WHERE code = '$code'"); 
    }    

    public function UpdateSalePoint(string $id, array $data){
        $code = $data['code'];

        $sql = "UPDATE sale_points SET code = '$code' WHERE id = $id";
        return parent::DoQuery($sql);
    }
}