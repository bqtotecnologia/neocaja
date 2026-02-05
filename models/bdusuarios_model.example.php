<?php

include_once 'PGSQL_model.php';

class BdusuariosModel extends PGSQLModel
{
    public function __construct()
    {
        parent::SetInfo(
            '',  // server
            '',     // username
            '',     // password
            ''    // database
        );
    }

    /*
    Obtiene un username y password
    Consulta la base de datos correspondiente a los usuarios
    Si encuentra el registro lo retorna, de lo contrario retorna false
    */
    public function TryLogin($username, $password)
    {
        $sql = "SELECT usuario, nombrecompleto FROM usuarios WHERE usuario = '$username' AND password = '$password'";
        $user = parent::GetRow($sql);
        if($user === false){
            // Existe la posibilidad de que sea el super admin
            include_once 'admin_model.php';
            $admin_model = new AdminModel();
            $user = $admin_model->CheckSuperAdminLogin($username, $password);
            if($user !== false)
                $user = array(
                    'nombrecompleto' => 'Administrador Supremo',
                    'super_admin' => true
                );
            else{
                // Puede ser un usuario del SENIAT
                $user = $admin_model->CheckSENIATLogin($username, $password);
                if($user !== false)
                    $user = array(
                        'nombrecompleto' => 'Usuario SENIAT',
                        'super_admin' => false
                    );
            }
        } 
        return $user;
    }

    // Retorna true si el usuario existe, checkea incluso si coincide con el super admin
    public function UserExists($user){
        $sql = "SELECT usuario FROM usuarios WHERE usuario='$user'";
        $result = parent::GetRow($sql);
        if($result === false){
            // chequeamos a ver si es el super admin
            include_once 'admin_model.php';
            $admin_model = new AdminModel();
            $result = $admin_model->CheckSuperAdminUser($user);
        }
        if($result !== false) return true;
        return false;
    }
}
