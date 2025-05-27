<?php
include_once 'SQL_model.php';

class AdminModel extends SQLModel
{ 
    private $ADMIN_SELECT = "SELECT
            roles.name as role,
            roles.id as role_id,
            admins.cedula,
            admins.name as name,
            admins.id as admin_id
            FROM
            admins
            INNER JOIN roles ON roles.id = admins.role ";
    // Obtiene una cédula y retorna el registro que coincida
    public function CheckAdmin($cedula)
    {
        $sql = $this->ADMIN_SELECT . " WHERE admins.cedula = '$cedula'";
        return parent::GetRow($sql);
    }

    /**
     * Crea un administrador y lo retorna
     */
    public function CreateAdmin(array $data){
        $cedula = $data['cedula'];
        $name = $data['name'];
        $role = $data['role'];

        $sql = "INSERT INTO admins (cedula, name, role) VALUES ('$cedula', '$name', '$role')";        
        $result = parent::DoQuery($sql);
        if($result === true){
            $result = $this->GetAdminByCedula($cedula);
        }

        return $result;
    }

    public function GetAdmins(){
        $sql = "SELECT * FROM admins";
        return parent::GetRows($sql);
    }

    public function GetRoles(){
        $sql = "SELECT * FROM roles";
        return parent::GetRows($sql);
    }

    public function GetRoleById($id){
        return parent::DoQuery("SELECT * FROM roles WHERE id = $id");
    }

    public function GetAdminById($id){
        $sql = $this->ADMIN_SELECT . " WHERE admins.id = '$id'";
        return parent::GetRow($sql);
    }

    public function GetAdminByCedula($cedula){
        $sql = "SELECT * FROM admins WHERE cedula = '$cedula'";
        return parent::GetRow($sql);
    }

    // Retorna true si el usuario coincide con el del super admin
    public function CheckSuperAdminUser($user){
        $encrypted_user = sha1($user);
        $sql = "SELECT * FROM super_admin 
            WHERE
            username = '$encrypted_user'";

        $result = parent::GetRow($sql);
        if($result === false) return false;
        return true;
    }

    // Retorna true si las credenciales recibidas son del super admin
    public function CheckSuperAdminLogin($user, $password){
        $encrypted_user = sha1($user);
        $sql = "SELECT * FROM super_admin 
            WHERE
            username = '$encrypted_user' AND
            password = '$password'";

        $result = parent::GetRow($sql);
        if($result === false) return false;
        return true;

    }

    // Retorna los coordinadores
    // si $as_list es false, los retorna como un string separado por comas
    public function GetCoordinadores($as_list = false){
        $sql = "SELECT
            *
            FROM admins
            WHERE
            type = 'coord'";
        $coordinadores = parent::GetRows($sql);
        if($as_list === true) 
            return $coordinadores;
        else{
            $ordered_cedulas = '-1';
            if($coordinadores !== false){
                $ordered_cedulas = '';
                foreach($coordinadores as $coordinador){
                    $ordered_cedulas .= "'" .$coordinador['cedula'] . "', ";
                }
                $ordered_cedulas = trim($ordered_cedulas, ', ');
            }
            return $ordered_cedulas;
        }
    }

    public function UpdateAdmin($id, $data){
        $cedula = $data['cedula'];
        $name = $data['name'];
        $role = $data['role'];

        $sql = "UPDATE admins SET cedula = '$cedula', name = '$name', role = '$role' WHERE id = $id";
        return parent::DoQuery($sql);
    }

    public function DeleteAdmin($id){
        $sql = "DELETE FROM admins WHERE id = $id";
        return parent::DoQuery($sql);
    }
}