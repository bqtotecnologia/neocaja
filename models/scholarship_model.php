<?php

include_once 'SQL_model.php';

class ScholarshipModel extends SQLModel
{    
    public function CreateScholarship(array $data){
        $name = $data['name'];
        $created = parent::DoQuery("INSERT INTO scholarships (name) VALUES ('$name')");

        if($created)
            $result = $this->GetScholarshipByName($name);
        else
            $result = false;
        
        return $result;
    }

    public function GetScholarships(){
        $sql = "SELECT 
            *
            FROM
            scholarships
            ORDER BY
            name";

        return parent::GetRows($sql, true);
    }

    public function GetScholarship(string $id){
        return parent::GetRow("SELECT * FROM scholarships WHERE id = '$id'"); 
    }

    public function GetScholarshipByName(string $name){
        return parent::GetRow("SELECT * FROM scholarships WHERE name = '$name'"); 
    }    

    public function UpdateScholarship(string $id, array $data){
        $name = $data['name'];

        $sql = "UPDATE scholarships SET name = '$name' WHERE id = $id";
        return parent::DoQuery($sql);
    }
}