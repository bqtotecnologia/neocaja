<?php
// El modelo base que contiene los métodos fundamentales para manejo de bases de datos con MySQL

class SQLModel{
    public  $db_server = 'localhost';
    public  $db_username = '';
    public  $db_password = '';
    public $db_database = '';

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

    // Obtenemos una consulta SQL y retornamos todos los resultados
    public function GetRows($sql){
        $conn = $this->GetConnection();
        $query = $conn->query($sql);
        $result = [];
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $row;
        }
        if($result === []) $result = false;
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
        $conn = $this->GetConnection();
        try {
            $conn->query($sql);
            return true;
        } catch (Exception $e) {
            echo $e->getMessage();
            echo'<br><br>';
            return false;
        }
    }

    // Crea una entrada en la bitácora
    public function CreateBinnacle($user, $action){
        $sql = "INSERT INTO bitacora (usuario, accion) VALUES ($user, '$action')";
        $this->DoQuery($sql);
    }
}