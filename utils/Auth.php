<?php
/**
 * Clase con diversos métodos relacionados con las variables de sesión
 */
class Auth{
    /**
     * Obtiene un array y verifica si el rol del usuario actual está en el array
     */
    public static function UserLevelIn(array $roles){
        return in_array($_SESSION['neocaja_rol'], $roles);
    }
}

?>