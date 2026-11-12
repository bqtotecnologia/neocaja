<?php

include_once 'SQL_model.php';

class BankModel extends SQLModel
{    
    public function CreateBank(array $data){
        $name = $data['name'];
        $active = $data['active'];

        return parent::DoQuery("INSERT INTO banks (name, active) VALUES ('$name', $active)");
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
            banks.id,
            banks.name,
            banks.active,
            banks.created_at,
            sale_points.id as sale_point
            FROM
            banks
            LEFT JOIN sale_points ON sale_points.bank = banks.id
            WHERE
            active = 1
            GROUP BY
            banks.id
            ORDER BY
            name";

        return parent::GetRows($sql, true);
    }

    public function GetBankById(string $id){
        return parent::GetRow("SELECT * FROM banks WHERE id = '$id'"); 
    }

    public function GetBankByName(string $name){
        return parent::GetRow("SELECT * FROM banks WHERE name = '$name'"); 
    }

    public function UpdateBank(string $id, array $data){
        $name = $data['name'];
        $active = $data['active'];

        $sql = "UPDATE banks SET name = '$name', active = $active WHERE id = $id";
        return parent::DoQuery($sql);
    }
}