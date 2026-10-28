<?php

include_once 'SQL_model.php';

class PeriodRangeModel extends SQLModel
{    
    public function CreatePeriodRange(array $data){
        $name = $data['name'];
        $active = $data['active'];

        return parent::DoQuery("INSERT INTO banks (name, active) VALUES ('$name', $active)");
    }

    public function GetPeriodRanges(){
        $sql = "SELECT 
            *
            FROM
            banks
            ORDER BY
            name";

        return parent::GetRows($sql, true);
    }

    public function UpdatePeriodRange(string $id, array $data){
        $name = $data['name'];
        $active = $data['active'];

        $sql = "UPDATE banks SET name = '$name', active = $active WHERE id = $id";
        return parent::DoQuery($sql);
    }
}