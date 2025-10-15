<?php
include_once 'SQL_model.php';

class MobilePaymentsModel extends SQLModel
{ 
    public $SELECT_TEMPLATE = "SELECT
        mobile_payments.id,
        mobile_payments.phone,
        mobile_payments.bank,
        mobile_payments.document_letter,
        mobile_payments.document_number,
        mobile_payments.active,
        mobile_payments.created_at,
        banks.name as bank,
        banks.id as bank_id
        FROM 
        mobile_payments
        INNER JOIN banks ON banks.id = mobile_payments.bank ";

    public function CreateMobilePayment($data){
        $created = parent::SimpleInsert('mobile_payments', $data);
        if($created === true){
            $sql = "SELECT * FROM mobile_payments ORDER BY id DESC LIMIT 1";
            $result = parent::GetRow($sql);
        }
        else
            $result = false;

        return $result;
    }

    public function GetMobilePayment($id){
        return parent::GetRow($this->SELECT_TEMPLATE . " WHERE mobile_payments.id = $id");
    }

    public function GetAllMobilePayments(){
        $sql = $this->SELECT_TEMPLATE . " ORDER BY mobile_payments.document_number";
        return parent::GetRows($sql, true);
    }

    public function GetActiveMobilePayments(){
        $sql = $this->SELECT_TEMPLATE . " WHERE mobile_payments.active = 1 ORDER BY mobile_payments.document_number";
        return parent::GetRows($sql, true);
    }
}