<?php
include_once 'SQL_model.php';

class ProductModel extends SQLModel
{ 
    private $SELECT_TEMPLATE = "SELECT 
        pr.id,
        pr.name,
        pr.active,
        pr.created_at as product_created_at,
        product_history.price,
        product_history.created_at as price_created_at
        FROM
        products pr
        INNER JOIN product_history
            ON product_history.product = pr.id
            AND product_history.created_at = (
                SELECT MAX(created_at)
                FROM product_history
                WHERE product = pr.id
        ) ";
    public function CreateProduct($data){
        $name = $data['name'];
        $sql = "INSERT INTO products (name) VALUES ('$name')";
        $created = parent::DoQuery($sql);

        if($created === false) return $created;
        return $this->GetProductByName($name);
    }

    public function GetAllProducts(){
        $sql = $this->SELECT_TEMPLATE . " ORDER BY pr.name";
        return parent::GetRows($sql, true);
    }

    public function GetProduct($id){
        $sql = "SELECT * FROM products WHERE id = '$id'";
        return parent::GetRow($sql);
    }

    public function GetProductByName($name){
        $sql = "SELECT * FROM products WHERE name = '$name'";
        return parent::GetRow($sql);
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