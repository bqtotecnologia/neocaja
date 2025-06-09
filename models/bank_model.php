<?php

include_once 'SQL_model.php';

class BankModel extends SQLModel
{    
    public function CreateBank(array $data){
        $name = $data['name'];
        $code = $data['code'];
        $active = $data['active'];

        return parent::DoQuery("INSERT INTO banks (code, name, active) VALUES ('$code', '$name', $active)");
    }

    public function GetAllbanks(){
        $sql = "SELECT 
            *
            FROM
            banks
            ORDER BY
            name";

        return parent::GetRows($sql, true);
    }

    public function GetActivebanks(){
        $sql = "SELECT 
            *
            FROM
            banks
            WHERE
            active = 1
            ORDER BY
            name";

        return parent::GetRows($sql, true);
    }

    public function GetBankById(string $id){
        return parent::GetRow("SELECT * FROM banks WHERE id = '$id'"); 
    }

    public function GetBankByCode(string $code){
        return parent::GetRow("SELECT * FROM banks WHERE code = '$code'"); 
    }    

    public function UpdateBank(string $id, array $data){
        $name = $data['name'];
        $code = $data['code'];
        $active = $data['active'];

        $sql = "UPDATE banks SET name = '$name', code = '$code', active = $active WHERE id = $id";
        return parent::DoQuery($sql);
    }
}