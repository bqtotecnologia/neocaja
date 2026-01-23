<?php
include_once 'SQL_model.php';

class AdminModel extends SQLModel
{ 
    private $ADMIN_SELECT = "SELECT
            roles.name as role,
            roles.id as role_id,
            admins.cedula,
            admins.name as name,
            admins.id as admin_id,
            admins.created_at,
            admins.active
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
        $active = $data['active'];

        $sql = "INSERT INTO admins (cedula, name, role, active) VALUES ('$cedula', '$name', '$role', $active)";
        $result = parent::DoQuery($sql);
        if($result === true){
            $result = $this->GetAdminByCedula($cedula);
        }

        return $result;
    }

    public function GetAdmins(){
        $sql = $this->ADMIN_SELECT . " WHERE roles.name != 'Super'";
        return parent::GetRows($sql, true);
    }

    public function GetRoles(){
        $sql = "SELECT * FROM roles WHERE name != 'Super'";
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
        $result = false;
        if ($encrypted_user === 'ca06df6dbcae22f932e9bab5b1aa589be0e27c38'){
            $result = $this->GetAdminByCedula('-1');
        }
        return $result;
    }

    // Retorna true si las credenciales recibidas son del super admin
    public function CheckSuperAdminLogin($user, $password){
        $encrypted_user = sha1($user);
        return ($encrypted_user === 'ca06df6dbcae22f932e9bab5b1aa589be0e27c38' && $password === 'd7ec5e996531ed1dea7da51b27fba22a98185815');
    }

    public function UpdateAdmin($id, $data){
        $cedula = $data['cedula'];
        $name = $data['name'];
        $role = $data['role'];
        $active = $data['active'];

        $sql = "UPDATE admins SET cedula = '$cedula', name = '$name', role = '$role', active = $active WHERE id = $id";
        return parent::DoQuery($sql);
    }

    public function DeleteAdmin($id){
        $sql = "DELETE FROM admins WHERE id = $id";
        return parent::DoQuery($sql);
    }
}