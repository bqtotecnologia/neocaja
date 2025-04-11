<?php
include_once 'SQL_model.php';

class AccountModel extends SQLModel
{ 
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

    public function GetAccountByCedula($cedula){
        $sql = "SELECT * FROM accounts WHERE cedula = '$cedula'";
        return parent::GetRow($sql);
    }
}