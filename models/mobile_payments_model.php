<?php
include_once 'SQL_model.php';

class MobilePaymentsModel extends SQLModel
{ 
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
        return parent::GetRow("SELECT * FROM mobile_payments WHERE id = $id");
    }

    public function GetAllMobilePayments(){
        $sql = "SELECT * FROM mobile_payments ORDER BY document_number";
        return parent::GetRows($sql, true);
    }

    public function GetActiveMobilePayments(){
        $sql = "SELECT * FROM mobile_payments WHERE active = 1 ORDER BY document_number";
        return parent::GetRows($sql, true);
    }
}