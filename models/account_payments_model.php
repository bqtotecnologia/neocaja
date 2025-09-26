<?php
include_once 'SQL_model.php';

class AccountPaymentsModel extends SQLModel
{ 
    public $SELECT_TEMPLATE = "SELECT
        CONCAT(accounts.surnames, ' ', accounts.names) as fullname,
        accounts.cedula,
        accounts.id as account_id,
        account_payments.id,
        account_payments.payment_method_type,
        account_payments.payment_method,
        account_payments.price,
        account_payments.ref,
        account_payments.document,
        account_payments.state,
        account_payments.response,
        account_payments.created_at
        FROM
        account_payments
        INNER JOIN accounts ON accounts.id = account_payments.account";

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

    public function GetAccountPayment($id){
        $sql = $this->SELECT_TEMPLATE . " WHERE account_payments.id = $id";
        return parent::GetRow($sql);
    }

    public function GetProductsOfPayment($id){
        $sql = "SELECT * FROM account_payment_products WHERE payment = $id";
        return parent::GetRows($sql, true);
    }

    /**
     * Retorna el método de pago junto a sus datos del pago realizado por un estudiante
     * Recibe como parámetro el pago en cuestión
     */
    public function GetPaymentMethodOfPayment($target_payment){
        if($target_payment['payment_method_type'] === 'mobile_payment'){
            include_once 'mobile_payments_model.php';         
            $mobile_payments_model = new MobilePaymentsModel();
            $target_payment_method = $mobile_payments_model->GetMobilePayment($target_payment['payment_method']);
        }
        else if($target_payment['payment_method_type'] === 'transfer'){
            include_once 'transfers_model.php';   
            $transfer_model = new TransfersModel(); 
            $target_payment_method = $transfer_model->GetTransfer($target_payment['payment_method_type']);
        }

        return $target_payment_method;
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

    public function GetPaymentsOfAccount($cedula){
        $sql = $this->SELECT_TEMPLATE . " WHERE accounts.cedula = '$cedula'";
        return parent::GetRows($sql, true);
    }

    public function GetPaymentsOfState($state){
        $sql = $this->SELECT_TEMPLATE . " WHERE account_payments.state = '$state'";
        return parent::GetRows($sql, true);
    }

    public function GetPaymentsOfDate($date){
        $sql = $this->SELECT_TEMPLATE . " WHERE DATE(account_payments.created_at) = '$date'";
        return parent::GetRows($sql, true);
    }

    public function ProcessPayment($id, $data){
        $state = $data['state'];
        $response = $data['response'];

        $sql = "UPDATE account_payments SET state = '$state', response = '$response' WHERE id = $id";
        return parent::DoQuery($sql);
    }

    /**
     * Borra un pago realizado por un estudiante, solo se ejecuta cuando tras crearlo, ocurre un error al insertar los productos
     */
    public function DeletePayment($id){
        return parent::DoQuery("DELETE FROM account_payments WHERE id = $id");
    }
}