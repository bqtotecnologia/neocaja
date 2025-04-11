<?php
include_once 'SQL_model.php';

class ProductModel extends SQLModel
{ 
    public function CreateProduct($data){
        $name = $data['name'];
        $sql = "INSERT INTO products (name) VALUES ('$name')";
        return parent::DoQuery($sql);
    }

    public function GetProduct($id){
        $sql = "SELECT * FROM products WHERE id = '$id'";
        return parent::GetRow($sql);
    }
}