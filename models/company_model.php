<?php
include_once 'SQL_model.php';

class CompanyModel extends SQLModel
{ 
    public function CreateCompany($data){
        $name = $data['name'];
        $rif_letter = $data['rif_letter'];
        $rif_number = $data['rif_number'];
        $address = $data['address'];

        $sql = "INSERT INTO companies 
            (name, rif_letter, rif_number, address) 
            VALUES 
            ('$name', '$rif_letter', '$rif_number', '$address')";
        $created = parent::DoQuery($sql);

        if($created === false) 
            $result = false;
        else
            $result = $this->GetCompanyByRif($rif_letter, $rif_number);

        return $result;
    }

    public function GetCompany($id){
        return parent::GetRow("SELECT * FROM companies WHERE id = $id");
    }

    public function GetCompanyByRif($letter, $number){
        $sql = "SELECT * FROM companies WHERE rif_letter = '$letter' AND rif_number = '$number'";
        return parent::GetRow($sql);
    }

    public function GetCompanies(){
        $sql = "SELECT * FROM companies ORDER BY name";
        return parent::GetRows($sql, true);
    }

    public function GetStudentsOfCompany($companyId){
        $sql = "SELECT
            accounts.cedula
            FROM
            accounts
            INNER JOIN companies ON companies.id = accounts.company
            WHERE
            companies.id = $companyId";
        
        $accounts = parent::GetRows($sql, true);
        $strCedulas =  '';
        foreach($accounts as $account){
            $strCedulas .= "'" . $account['cedula'] . "', ";
        }
        $strCedulas = trim($strCedulas, ', ');

        $result = [];
        if($strCedulas !== ''){
            include_once 'siacad_model.php';
            $siacad_model = new SiacadModel();
            $result = $siacad_model->GetStudentsByCedulaList($strCedulas);
        }

        return $result;
    }

    public function UpdateCompany($id, $data){
        $name = $data['name'];
        $rif_letter = $data['rif_letter'];
        $rif_number = $data['rif_number'];
        $address = $data['address'];

        $sql = "UPDATE companies SET
            name = '$name',
            rif_letter = '$rif_letter',
            rif_number = '$rif_number',
            address = '$address'
            WHERE
            id = $id";
        
        return parent::DoQuery($sql);
    }
}