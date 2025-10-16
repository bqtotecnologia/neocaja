<?php
include_once 'SQL_model.php';

class TransfersModel extends SQLModel
{ 
    public $SELECT_TEMPLATE = "SELECT
        transfers.id,
        transfers.account_number,
        transfers.bank,
        transfers.document_letter,
        transfers.document_number,
        transfers.active,
        transfers.created_at,
        banks.name as bank,
        banks.id as bank_id
        FROM 
        transfers
        INNER JOIN banks ON banks.id = transfers.bank ";

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
        return parent::GetRow($this->SELECT_TEMPLATE . " WHERE transfers.id = $id");
    }

    public function GetAllTransfers(){
        $sql = $this->SELECT_TEMPLATE . " ORDER BY transfers.document_number";
        return parent::GetRows($sql, true);
    }

    public function GetActiveTransfers(){
        $sql = $this->SELECT_TEMPLATE . " WHERE transfers.active = 1 ORDER BY transfers.document_number";
        return parent::GetRows($sql, true);
    }
}