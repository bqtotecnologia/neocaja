<?php

include_once 'SQL_model.php';

class PaymentMethodModel extends SQLModel
{    
    public function CreatePaymentMethodType(array $data){
        $name = $data['name'];

        $created = parent::DoQuery("INSERT INTO payment_method_types (name) VALUES ('$name')");
        if($created === true)
            $result = $this->GetPaymentMethodTypeByName($name);
        else
            $result = false;

        return $result;
    }

    public function GetAllPaymentMethodTypes(){
        $sql = "SELECT 
            *
            FROM
            payment_method_types
            ORDER BY
            name";

        return parent::GetRows($sql, true);
    }

    public function GetPaymentMethodType(string $id){
        return parent::GetRow("SELECT * FROM payment_method_types WHERE id = '$id'"); 
    }

    public function GetPaymentMethodTypeByName(string $name){
        return parent::GetRow("SELECT * FROM payment_method_types WHERE name = '$name'"); 
    }

    public function UpdatePaymentMethodType(string $id, array $data){
        $name = $data['name'];

        $sql = "UPDATE payment_method_types SET name = '$name' WHERE id = $id";
        return parent::DoQuery($sql);
    }
}