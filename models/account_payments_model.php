<?php
include_once 'SQL_model.php';

class AccountPaymentsModel extends SQLModel
{ 
    public function CreatePayment($data){
        $created = parent::SimpleInsert('account_payments', $data);
        if($created === true){
            $sql = "SELECT * FROM account_payments ORDER BY id DESC LIMIT 1";
            $result = parent::GetRow($sql);
        }
        else
            $result = false;

        return $result;
    }

    public function AddProductToPayment($product, $payment){
        $name = $product['name'];
        $price = $product['price'];

        $sql = "INSERT INTO 
            account_payment_products
            (product, price, payment) 
            VALUES
            ('$name', '$price', $payment)";

        return parent::DoQuery($sql);
    }    

    /**
     * Borra un pago realizado por un estudiante, solo se ejecuta cuando tras crearlo, ocurre un error al insertar los productos
     */
    public function DeletePayment($id){
        return parent::DoQuery("DELETE FROM account_payments WHERE id = $id");
    }
}