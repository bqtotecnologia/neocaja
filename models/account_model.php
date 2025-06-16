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
        accounts.scholarship_coverage,
        accounts.created_at,
        scholarships.id as scholarship_id,
        scholarships.name as scholarship,
        companies.id as company_id,
        companies.name as company,
        companies.rif_letter,
        companies.rif_number,
        companies.address as company_address,
        companies.created_at as company_created_at
        FROM
        accounts
        LEFT JOIN companies ON companies.id = accounts.company 
        LEFT JOIN scholarships ON scholarships.id = accounts.scholarship";

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

        $created = parent::DoQuery($sql);
        if($created === true)
            $result = $this->GetAccountByCedula($cedula);
        else
            $result = false;

        return $result;
    }

    public function GetAccounts(){
        $sql = $this->SELECT_TEMPLATE . " ORDER BY accounts.names, accounts.surnames";
        return parent::GetRows($sql);
    }

    public function GetAccount($id){
        $sql = $this->SELECT_TEMPLATE . " WHERE accounts.id = $id";
        return parent::GetRow($sql);
    }

    public function GetScholarshipStudents(){
        $sql = $this->SELECT_TEMPLATE . " WHERE accounts.scholarship IS NOT NULL ORDER BY accounts.names, accounts.surnames";
        return parent::GetRows($sql, true);
    }

    public function GetAccountByCedula($cedula){
        $sql = "SELECT * FROM accounts WHERE cedula = '$cedula'";
        return parent::GetRow($sql);
    }

    public function UpdateAccount($id, $data){
        $names = $data['names'];
        $surnames = $data['surnames'];
        $cedula = $data['cedula'];
        $address = $data['address'];
        $is_student = $data['is_student'];
        $scholarship = $data['scholarship'];
        $scholarship_coverage = $data['scholarship_coverage'];
        $company = $data['company'];

        $sql = "UPDATE accounts SET
            cedula = '$cedula',
            names = '$names',
            surnames = '$surnames',
            address = '$address',
            is_student = $is_student,
            scholarship = $scholarship,
            scholarship_coverage = $scholarship_coverage,
            company = $company
            WHERE
            id = $id";

        return parent::DoQuery($sql);
    }
}
