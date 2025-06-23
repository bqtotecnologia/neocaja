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
        $name = $data['name'];

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