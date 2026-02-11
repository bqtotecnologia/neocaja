<?php
include_once 'SQL_model.php';

class AuthenticationModel extends SQLModel
{ 
    private $SELECT_TEMPLATE = "SELECT
        authentications.id,
        authentications.cedula,
        authentications.login_attempts,
        authentications.last_attempt,
        authentications.login_cooldown,
        authentications.last_connection
        FROM
        authentications";

    public function CreateLoginHistory($cedula, $success){
        $sql = "INSERT INTO login_history (cedula, success) VALUES ('$cedula', $success)";
        return parent::DoQuery($sql);
    }

    public function CreateAuthenticationData($cedula){
        $sql = "INSERT INTO authentications (cedula) VALUES ('$cedula')";
        return parent::DoQuery($sql);
    }

    public function GetAuthenticationDataByCedula($cedula){
        $sql = $this->SELECT_TEMPLATE . " WHERE authentications.cedula = '$cedula'";
        return parent::GetRow($sql);
    }

    public function UpdateAuthenticationData($id, $data){
        return parent::SimpleUpdate('authentications', $data, $id);
    }
}
