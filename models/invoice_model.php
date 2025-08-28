<?php

include_once 'SQL_model.php';

class InvoiceModel extends SQLModel
{    
    public $SINGLE_SELECT_TEMPLATE = "SELECT 
        invoices.id,
        invoices.control_number,
        invoices.invoice_number,
        invoices.created_at,
        invoices.period,
        invoices.active,
        invoices.reason,
        invoices.observation,
        CONCAT(accounts.names, ' ', accounts.surnames) as account_fullname,
        accounts.cedula,
        accounts.id as account_id
        FROM
        invoices
        INNER JOIN accounts ON accounts.id = invoices.account 
    ";

    public function CreateInvoice(array $data){
        // TODO: terminar esta función y poner comillas en los campos de texto directamente en las variables para poder poner NULL
        $invoice_number = $data['invoice_number'];
        $control_number = $data['control_number'];
        $account = $data['account'];
        $reason = $data['reason'];
        $observation = $data['observation'];

        $sql = "INSERT INTO invoice
        (invoice_number,
        control_number,
        account,
        reason,
        observation
        )
        VALUES
        (
        $invoice_number,
        $control_number,
        $account,
        $reason,
        $observation,
        )";


        $created = parent::DoQuery("INSERT INTO payment_method_types (name) VALUES ('$name')");
        if($created === true)
            $result = $this->GetPaymentMethodTypeByName($name);
        else
            $result = false;

        return $result;
    }

    public function GetAllInvoices(){
        $sql = $this->SINGLE_SELECT_TEMPLATE . " ORDER BY invoices.created_at DESC";
        return parent::GetRows($sql, true);
    }

    public function GetInvoicesOfDate($date){
        $sql = $this->SINGLE_SELECT_TEMPLATE . " WHERE DATE(invoices.created_at) = $date ORDER BY invoices.created_at DESC";
        return parent::GetRows($sql, true);
    }

    public function GetInvoiceByInvoiceNumber($number){
        $sql = $this->SINGLE_SELECT_TEMPLATE . " WHERE invoices.invoice_number = $number";
        return parent::GetRow($sql);
    }

    public function GetInvoiceByControlNumber($number){
        $sql = $this->SINGLE_SELECT_TEMPLATE . " WHERE invoices.control_number = $number";
        return parent::GetRow($sql);
    }

    public function GetPaymentMethodType(string $id){
        return parent::GetRow("SELECT * FROM payment_method_types WHERE id = '$id'"); 
    }

    public function GetPaymentMethodTypeByName(string $name){
        return parent::GetRow("SELECT * FROM payment_method_types WHERE name = '$name'"); 
    }

    /**
     * Retorna los últimos números de control y de factura
     */
    public function GetLatestNumbers(){
        $sql = "SELECT 
            (MAX(invoice_number) + 1) as invoice_number, 
            (MAX(control_number) + 1) as control_number
            FROM
            invoices";
        
        $numbers = parent::GetRow($sql);
        if($numbers['invoice_number'] === NULL)
            $numbers['invoice_number'] = 1;

        if($numbers['control_number'] === NULL)
            $numbers['control_number'] = 1;

        return $numbers;
    }

    public function GetInvoicesOfAccountOfPeriod(string $account, string $period){
        $sql = "SELECT 
            inv.id,
            inv.created_at,
            inv.reason,
            SUM(ipm.total) as total
            FROM
            invoices inv
            INNER JOIN accounts ON accounts.id = inv.account 
            INNER JOIN (
                SELECT
                invoice_payment_method.invoice,
                invoice_payment_method.price * invoice_payment_method.rate as total
                FROM
                invoice_payment_method
            ) as ipm ON ipm.invoice = inv.id
            WHERE 
            accounts.id = $account AND 
            inv.period = $period AND
            inv.active = 1
            GROUP BY
            inv.id";


        return parent::GetRows($sql, true);
    }

    public function UpdatePaymentMethodType(string $id, array $data){
        $name = $data['name'];

        $sql = "UPDATE payment_method_types SET name = '$name' WHERE id = $id";
        return parent::DoQuery($sql);
    }
}