<?php
include_once 'SQL_model.php';

class ProductModel extends SQLModel
{ 
    private $SELECT_TEMPLATE = "SELECT 
        pr.id,
        pr.name,
        pr.active,
        pr.created_at as product_created_at,
        ph.price,
        ph.created_at as price_created_at
        FROM
        products pr
        LEFT JOIN product_history ph ON ph.product = pr.id AND ph.current = 1 ";

    public function CreateProduct($data){
        $name = $data['name'];
        $sql = "INSERT INTO products (name) VALUES ('$name')";
        $created = parent::DoQuery($sql);

        if($created === false) 
            $result = false;
        else
            $result = $this->GetProductByName($name);

        return $result;
    }

    public function GetAllProducts(){
        $sql = $this->SELECT_TEMPLATE . " ORDER BY pr.name";
        return parent::GetRows($sql, true);
    }

    public function GetProduct($id){
        $sql = $this->SELECT_TEMPLATE . " WHERE pr.id = '$id'";
        return parent::GetRow($sql);
    }

    public function GetProductByName($name){
        $sql = $this->SELECT_TEMPLATE . " WHERE pr.name = '$name'";
        return parent::GetRow($sql);
    }

    public function GetProductHistory($productId){
        return parent::GetRows("SELECT * FROM product_history WHERE product = $productId ORDER BY created_at DESC", true);
    }

    public function UpdateProduct($data, $id){
        $name = $data['name'];
        $sql = "UPDATE products SET
            name = '$name'
            WHERE
            id = $id";
        
        return parent::DoQuery($sql);
    }

    public function UpdateProductPrice($price, $id){
        $sql = "INSERT INTO product_history (product, price) VALUES ($id, '$price')";
        return parent::DoQuery($sql);
    }
}