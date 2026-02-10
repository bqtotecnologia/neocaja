<?php
include_once 'SQL_model.php';

class AccountPaymentsModel extends SQLModel
{ 
    public $SELECT_TEMPLATE = "SELECT
        polymorph.fullname,
        polymorph.cedula,
        polymorph.account_id,
        polymorph.invoice_id,
        polymorph.invoice_number,
        account_payments.id,
        account_payments.payment_method_type,
        account_payments.payment_method,
        account_payments.price,
        account_payments.ref,
        account_payments.document,
        account_payments.state,
        account_payments.response,
        account_payments.created_at,
        DATE(account_payments.created_at) as date,
        account_payments.related_id,
        account_payments.capture,
        account_payments.related_with
        FROM
        account_payments
        INNER JOIN 
        (
            SELECT 
            CONCAT(accounts.surnames, ' ', accounts.names) as fullname,
            accounts.cedula,
            accounts.id as account_id,
            invoices.id as invoice_id,
            invoices.invoice_number
            FROM
            accounts
            LEFT JOIN invoices ON invoices.account = accounts.id
        )
        as polymorph ON
        (polymorph.account_id = account_payments.related_id AND account_payments.related_with = 'client') 
        OR
        (polymorph.invoice_id = account_payments.related_id AND account_payments.related_with = 'invoice') 
        -- Here yo need to place GROUP BY account_payments.id before writting a WHERE statement
        ";

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
        $sql = $this->SELECT_TEMPLATE . " WHERE account_payments.id = $id GROUP BY account_payments.id";
        return parent::GetRow($sql);
    }

    public function GetProductsOfPayment($id){
        $sql = "SELECT * FROM account_payment_products WHERE payment = $id";
        return parent::GetRows($sql, true);
    }

    public function GetAccountPaymentsBetweenDatesWihtoutInvoice($start_date, $end_date){
        $sql = $this->SELECT_TEMPLATE . " WHERE 
            DATE(account_payments.created_at) BETWEEN '$start_date' AND '$end_date' 
            AND            
            account_payments.state = 'Aprobado' AND
            account_payments.related_with != 'invoice'
            GROUP BY account_payments.id";
        return parent::GetRows($sql, true);
    }

    public function GetIncomesOfInvoice($id){
        $sql = $this->SELECT_TEMPLATE . " WHERE account_payments.related_with = 'client' AND account_payments.related_id = $id";
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
            $target_payment_method = $transfer_model->GetTransfer($target_payment['payment_method']);
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
        $sql = $this->SELECT_TEMPLATE . " WHERE polymorph.cedula = '$cedula' GROUP BY account_payments.id ORDER BY account_payments.created_at DESC";
        return parent::GetRows($sql, true);
    }

    public function GetPaymentsOfState($state){
        $sql = $this->SELECT_TEMPLATE . " WHERE account_payments.state = '$state' GROUP BY account_payments.id ORDER BY account_payments.created_at DESC";
        return parent::GetRows($sql, true);
    }

    public function GetPaymentsOfDate($date){
        $sql = $this->SELECT_TEMPLATE . " WHERE DATE(account_payments.created_at) = '$date' GROUP BY account_payments.id ORDER BY account_payments.created_at DESC";
        return parent::GetRows($sql, true);
    }

    public function ProcessPayment($id, $data){
        $state = $data['state'];
        $response = $data['response'];

        $sql = "UPDATE account_payments SET state = '$state', response = '$response' WHERE id = $id";
        return parent::DoQuery($sql);
    }

    /**
     * Borra un pago realizado por un estudiante, solo se ejecuta cuando ocurre un error en la creación
     */
    public function DeletePayment($id){
        return parent::DoQuery("DELETE FROM account_payments WHERE id = $id");
    }
}