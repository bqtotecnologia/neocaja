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
        accounts.phone,
        accounts.is_student,
        accounts.created_at,
        account_history.scholarship_coverage,
        account_history.id as account_history_id,
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
        LEFT JOIN account_history ON (account_history.account = accounts.id AND account_history.current = 1)
        LEFT JOIN companies ON companies.id = account_history.company 
        LEFT JOIN scholarships ON scholarships.id = account_history.scholarship";

    public function CreateAccount($data){
        $names = $data['names'];
        $surnames = $data['surnames'];
        $cedula = $data['cedula'];
        $address = $data['address'];
        $phone = $data['phone'];
        $is_student = $data['is_student'];

        $sql = "INSERT INTO accounts 
            (names, surnames, cedula, address, phone, is_student)
            VALUES
            ('$names', '$surnames', '$cedula', '$address', '$phone', $is_student)";


        $created = parent::DoQuery($sql);
        if($created === true)
            $result = $this->GetAccountByCedula($cedula);
        else
            $result = false;

        return $result;
    }

    public function GetAccounts(){
        $sql = $this->SELECT_TEMPLATE . " ORDER BY accounts.names, accounts.surnames";
        return parent::GetRows($sql, true);
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
        $sql =  $this->SELECT_TEMPLATE . " WHERE accounts.cedula = '$cedula'";
        return parent::GetRow($sql);
    }

    public function GetCompanyHistory($id){
        $sql = "SELECT
            companies.id,
            companies.name,
            companies.rif_letter,
            companies.rif_number,
            companies.created_at,
            companies.address
            FROM
            companies
            INNER JOIN account_company_history ON account_company_history.company = companies.id
            WHERE
            account_company_history.id = $id";
            
        return parent::GetRow($sql);
    }

    public function UpdateAccount($id, $data){
        $names = $data['names'];
        $surnames = $data['surnames'];
        $cedula = $data['cedula'];
        $address = $data['address'];
        $phone = $data['phone'];
        $is_student = $data['is_student'];

        $sql = "UPDATE accounts SET
            cedula = '$cedula',
            names = '$names',
            surnames = '$surnames',
            address = '$address',
            phone = '$phone',
            is_student = $is_student
            WHERE
            id = $id";

        return parent::DoQuery($sql);
    }

    public function DeactivateHistoryOfAccount($account){
        return parent::DoQuery("UPDATE account_history SET current = 0 WHERE account = $account");
    }

    /**
     * Actualiza el historial de becas/empresa de un estudiante
     */
    public function UpdateAccountHistory($account, $data){
        $this->DeactivateHistoryOfAccount($account);
        return parent::SimpleInsert('account_history', $data);
    }

    public function DeleteAccount($cedula){
        return parent::DoQuery("DELETE FROM accounts WHERE cedula = '$cedula'");
    }
}
