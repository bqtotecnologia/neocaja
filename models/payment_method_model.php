<?php

include_once 'SQL_model.php';

class PaymentMethodModel extends SQLModel
{    
    public function CreatePaymentMethodType(array $data){
        $name = $data['name'];
        $document_number = $data['document_number'];
        $bank = $data['bank'];
        $sale_point = $data['sale_point'];

        $created = parent::DoQuery("INSERT INTO payment_method_types (name, document_number, bank, sale_point) VALUES ('$name', $document_number, $bank, $sale_point)");
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

    /**
     * Obtiene los métodos de pago dados por la función GetAllPaymentMethodTypes() y retorna un array asociativo con los bloqueos de cada método de pago
     */
    public function GetBlockedFields($payment_methods){
        $result = [];

        foreach($payment_methods as $p_method){
            $result[$p_method['name']] = [];

            if(intval($p_method['bank'] === 0))
                array_push($result[$p_method['name']], 'bank');

            if(intval($p_method['document_number'] === 0))
                array_push($result[$p_method['name']], 'document');

            if(intval($p_method['sale_point'] === 0))
                array_push($result[$p_method['name']], 'salepoint');
        }

        return $result;
    }

    public function GetPaymentMethodType(string $id){
        return parent::GetRow("SELECT * FROM payment_method_types WHERE id = '$id'"); 
    }

    public function GetPaymentMethodTypeByName(string $name){
        return parent::GetRow("SELECT * FROM payment_method_types WHERE name = '$name'"); 
    }

    /**
     * Un método de pago solo será borrable si no está ligado a ninguna factura
     */
    public function PaymentMethodIsDeletable($id){
        $sql = "SELECT * FROM 
            invoice_payment_method
            INNER JOIN payment_method_types ON payment_method_types.id = invoice_payment_method.type
            WHERE
            payment_method_types.id = $id";

        $found = parent::GetRow($sql);
        return $found === false;
    }

    public function UpdatePaymentMethodType(string $id, array $data){
        $name = $data['name'];
        $document_number = $data['document_number'];
        $bank = $data['bank'];
        $sale_point = $data['sale_point'];

        $sql = "UPDATE payment_method_types SET name = '$name', document_number = $document_number, bank = $bank, sale_point = $sale_point WHERE id = $id";
        return parent::DoQuery($sql);
    }

    public function DeletePaymentMethod($id){
        return parent::DoQuery("DELETE FROM payment_method_types WHERE id = $id");
    }
}