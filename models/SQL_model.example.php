<?php
// El modelo base que contiene los métodos fundamentales para manejo de bases de datos con MySQL

class SQLModel{
    public  $db_server = 'localhost';
    public  $db_username = '';
    public  $db_password = '';
    public $db_database = '';

    public $month_translate = [
        '1' => 'Enero',
        '2' => 'Febrero',
        '3' => 'Marzo',
        '4' => 'Abril',
        '5' => 'Mayo',
        '6' => 'Junio',
        '7' => 'Julio',
        '8' => 'Agosto',
        '9' => 'Septiembre',
        '10' => 'Octubre',
        '11' => 'Noviembre',
        '12' => 'Diciembre',
    ];

    // Una función que recibe los parámetros necesarios para iniciar una conexión
    public function SetInfo($server, $user, $pass, $database){
        $this->db_server = $server;
        $this->db_username = $user;
        $this->db_password = $pass;
        $this->db_database = $database;
    }

    // Retorna la conexión
    public function GetConnection()
    {   
        $my_string = 'mysql:host='.$this->db_server.';dbname='.$this->db_database;
        $pdo = new PDO($my_string, $this->db_username, $this->db_password);
        //$pdo->exec("set names utf16");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }

    /**
     * Obtiene una consulta SELECt SQL y retorna los resultados
     * @param string $sql La consulta
     * @param bool $empty_array Si recibe true retornará un array vacío si no se encuentran resultados, de lo contrario retorna false
     */
    public function GetRows($sql, $empty_array = false){
        $conn = $this->GetConnection();
        $query = $conn->query($sql);
        $result = [];
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $row;
        }

        if($result === [] && $empty_array === false) $result = false;
        return $result;
    }

    // Obtenemos una consulta SQL y retornamos estrictamente el primer resultado
    public function GetRow($sql){
        $conn = $this->GetConnection();
        $query = $conn->query($sql);
        $result = [];
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $row;
        }
        if(count($result) >= 1) return $result[0];
        return false;
    }

    // Ejecuta una consulta que no retorna registros, retorna true si no hubo errores, sino false
    public function DoQuery($sql){
        $final_sql = "SET @app_audit = 1; " . $sql;
        // Para que la base de datos entienda que es la acción de un usuario del sistema
        // De esa forma no se disparan los triggers y no se inunda la tabla binnacle
        $conn = $this->GetConnection();
        try {
            $conn->query($final_sql);
            return true;
        } catch (Exception $e) {
            echo $e->getMessage();
            echo'<br><br>';
            return false;
        }
    }

    /**
     * Retorna el número de un mes obteniendo su nombre en español
     */
    public function GetMonthNumberByName($name){
        $result = null;
        foreach($this->month_translate as $number => $month)
            if($month === $name)
                $result = $number;

        return $result;
    }

    // Crea una entrada en la bitácora
    public function CreateBinnacle($user, $action){
        $sql = "INSERT INTO binnacle (user, action) VALUES ($user, '$action')";
        $this->DoQuery($sql);
    }

    /**
     * Crea un insert a una tabla dada y recibiendo un array asociativo comprendido por 'campo' => valor
     * Si algún valor es NULL intentará insertarlo como NULL
     */
    public function SimpleInsert($table, $data){       
        $field_names = '';
        $field_values = '';
        
        foreach($data as $name => $value){
            if($name === 'id') continue;

            $field_names .= $name . ', ';
            if($value === null)
                $field_values .= 'NULL, ';
            else
                $field_values .= "'$value', ";
        }

        $field_names = trim($field_names, ', ');
        $field_values = trim($field_values, ', ');

        $sql = "INSERT INTO $table ($field_names) VALUES ($field_values)";
        return $this->DoQuery($sql);
    }

    /**
     * Crea y ejecuta un update recibiendo el nombre de tabla, el id del registro y un array asociativo
     * comprendido por 'campo' => valor
     * Si algún valor es NULL intentará insertarlo como NULL
     */
    public function SimpleUpdate($table, $data, $target_id){
        $sql = "UPDATE $table SET ";
        foreach($data as $name => $value){
            if($name === 'id') continue;
            
            $sql .= "$name = ";

            if($value === null)
                $sql .= 'NULL, ';
            else
                $sql .= "'$value', ";
        }

        $sql = trim($sql, ', ');
        $sql .= " WHERE id = $target_id";
        return $this->DoQuery($sql);
    }
}