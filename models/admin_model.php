<?php
include_once 'PGSQL_model.php';

class AdminModel extends PGSQLModel
{ 
    // Obtiene una cédula y retorna el registro que coincida
    public function CheckAdmin($cedula)
    {
        $sql = "SELECT * FROM admin WHERE cedula='$cedula'";
        return parent::GetRow($sql);
    }

    //Función para añadir adminsitradores único del super administrador
    public function AddAdmin($cedula, $admin_level){
        $sql = "SELECT * FROM admin WHERE cedula='$cedula'";
        $result = parent::GetRow($sql);

        if($result !== false) 
            return 'Cédula ya registrada';

        $sql = "INSERT INTO admin (cedula, type) VALUES ('$cedula', '$admin_level')";        
        parent::DoQuery($sql);

        $sql = "SELECT * FROM admin WHERE cedula = '$cedula' AND type = '$admin_level'";
        $new_admin = parent::GetRow($sql);
        if($new_admin === false)
            return 'Hubo un problema al insertar el administrador en la base de datos';
        else
            return true;
    }

    public function GetAdmins(){
        $sql = "SELECT * FROM admin";
        return parent::GetRows($sql);
    }

    public function GetAdminById($id){
        $sql = "SELECT * FROM admin WHERE id = '$id'";
        return parent::GetRow($sql);
    }

    public function GetAdminByCedula($cedula){
        $sql = "SELECT * FROM admin WHERE cedula = '$cedula'";
        return parent::GetRow($sql);
    }

    // Retorna true si el usuario coincide con el del super admin
    public function CheckSuperAdminUser($user){
        $sql = "SELECT * FROM super_admin 
            WHERE
            usuario = '$user'";

        $result = parent::GetRow($sql);
        if($result === false) return false;
        return true;
    }

    // Retorna true si las credenciales recibidas son del super admin
    public function CheckSuperAdminLogin($user, $password){
        $sql = "SELECT * FROM super_admin 
            WHERE
            usuario = '$user' AND
            contrasena = '$password'";

        $result = parent::GetRow($sql);
        if($result === false) return false;
        return true;

    }

    // Retorna los coordinadores
    // si $as_list es false, los retorna como un string separado por comas
    public function GetCoordinadores($as_list = false){
        $sql = "SELECT
            *
            FROM admin
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

    public function UpdateAdmin($id, $new_cedula, $newType){
        $sql = "UPDATE admin SET cedula='$new_cedula', type='$newType' WHERE id=$id";
        return parent::DoQuery($sql);
    }

    public function DeleteAdmin($id){
        $sql = "DELETE FROM admin WHERE id = $id";
        return parent::DoQuery($sql);
    }
}