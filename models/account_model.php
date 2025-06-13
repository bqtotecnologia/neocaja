<?php
include_once 'SQL_model.php';

class AccountModel extends SQLModel
{ 
    private $SELECT_TEMPLATE = "SELECT
        accounts.id,
        accounts.names,
        accounts.surnames,
        accounts.cedula,
        accounts.address,
        accounts.is_student,
        accounts.scholarship,
        accounts.scholarship_coverage,
        accounts.created_at,
        companies.id as company_id,
        companies.name as company,
        companies.rif_letter,
        companies.rif_number,
        companies.address as company_address,
        companies.created_at as company_created_at
        FROM
        accounts
        LEFT JOIN companies ON companies.id = accounts.company ";
    public function CreateAccount($data){
        $names = $data['names'];
        $surnames = $data['surnames'];
        $cedula = $data['cedula'];
        $address = $data['address'];
        $is_student = $data['is_student'];
        $scholarship = $data['scholarship'];
        $scholarship_coverage = $data['scholarship_coverage'];
        $company = $data['company'];

        $sql = "INSERT INTO accounts 
            (names, surnames, cedula, address, is_student, scholarship, scholarship_coverage, company)
            VALUES
            ('$names', '$surnames', '$cedula', '$address', $is_student, $scholarship, $scholarship_coverage, $company)";

        return parent::DoQuery($sql);
    }

    public function GetAccounts(){
        $sql = $this->SELECT_TEMPLATE . " ORDER BY names, surnames";
        return parent::GetRows($sql);
    }

    public function GetAccount($id){
        $sql = $this->SELECT_TEMPLATE . " WHERE id = $id";
        return parent::GetRow($sql);
    }

    public function GetAccountByCedula($cedula){
        $sql = "SELECT * FROM accounts WHERE cedula = '$cedula'";
        return parent::GetRow($sql);
    }
}