<?php
include_once 'SQL_model.php';

class TransfersModel extends SQLModel
{ 
    public function CreateTransfer($data){
        $created = parent::SimpleInsert('transfers', $data);
        if($created === true){
            $sql = "SELECT * FROM transfers ORDER BY id DESC LIMIT 1";
            $result = parent::GetRow($sql);
        }
        else
            $result = false;

        return $result;
    }
    
    public function GetTransfer($id){
        return parent::GetRow("SELECT * FROM transfers WHERE id = $id");
    }

    public function GetAllTransfers(){
        $sql = "SELECT * FROM transfers ORDER BY document_number";
        return parent::GetRows($sql, true);
    }

    public function GetActiveTransfers(){
        $sql = "SELECT * FROM transfers WHERE active = 1 ORDER BY document_number";
        return parent::GetRows($sql, true);
    }
}