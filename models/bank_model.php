<?php

include_once 'SQL_model.php';

class BankModel extends SQLModel
{    
    public function CreateBank(array $data){
        $name = $data['name'];
        $code = $data['code'];
        return parent::DoQuery("INSERT INTO banks (code, name) VALUES ('$code', '$name')");
    }

    public function Getbanks(){
        $sql = "SELECT 
            *
            FROM
            banks
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
        $sql = "UPDATE banks SET name = '$name', code = '$code' WHERE id = $id";
        return parent::DoQuery($sql);
    }

    /**
     * Alterna entre activar y desactivar un bando
     */
    public function ToggleActiveBank(string $id){
        $targetBank = $this->GetBankById($id);
        $sql = "UPDATE banks SET active = ";
        if($targetBank['active'] === '1')
            $sql .= '0 ';
        else
            $sql .= '1 ';

        $sql .= "WHERE id = $id";
        return parent::DoQuery($sql);
    }
}