<?php
include_once 'SQL_model.php';

class UnknownIncomesModel extends SQLModel
{ 
    public $SELECT_TEMPLATE = "SELECT
        unknown_incomes_generations.id as generation,
        unknown_incomes_generations.created_at,
        unknown_incomes.id,
        unknown_incomes.date,
        unknown_incomes.price,
        unknown_incomes.ref,
        unknown_incomes.description,
        accounts.names,
        accounts.surnames,
        accounts.cedula,
        accounts.id as account_id
        FROM 
        unknown_incomes_generations 
        INNER JOIN unknown_incomes ON unknown_incomes.generation = unknown_incomes_generations.id
        LEFT JOIN accounts ON accounts.id = unknown_incomes.account
        ";
    /**
     * Crea una generación de ingresos no identificados y retorna el registro creado
     */
    public function CreateGeneration(){
        $created = parent::DoQuery("INSERT INTO unknown_incomes_generations () VALUES ()");

        if($created === true){
            $sql = "SELECT * FROM unknown_incomes_generations ORDER BY id DESC LIMIT 1";
            $created_generation = parent::GetRow($sql);
            if($created_generation === false)
                $result = 'No se encontró la generación creada';
            else
                $result = $created_generation;
        }
        else
            $result = 'Hubo un error al intentar crear la generación de ingresos no identificados';
    
        return $result;
    }

    /**
     * Inserta en la base de datos los ingresos no identificados dados
     */
    public function InsertUnknownIncomes($payments, $generation){
        $sql = "INSERT INTO unknown_incomes (date, price, ref, description, generation) VALUES ";


        foreach($payments as $payment){           
            $date = $payment['date'];
            $ref = $payment['ref'];
            $description = $payment['description'];
            $price = $payment['price'];
            $sql .= "('$date', '$price', '$ref', '$description', $generation), ";            
        }

        $sql = trim($sql, ', ');    
        
        
        return parent::DoQuery($sql);                  
    }

    public function GetUnknownIncome($id){
        return parent::GetRow($this->SELECT_TEMPLATE . " WHERE unknown_incomes.id = $id");
    }

    public function GetUnknownIncomesByDate($date, $identified = false){
        $sql = $this->SELECT_TEMPLATE . " WHERE DATE(unknown_incomes.date) = '$date'";

        if($identified)
            $sql .= ' AND unknown_incomes.account IS NOT NULL';
        else
            $sql .= ' AND unknown_incomes.account IS NULL';

        return parent::GetRows($sql, true);
    }

    public function DeleteIncomesGeneration($id){
        $sql = "DELETE FROM unknown_incomes_generation WHERE id = $id";
        return parent::DoQuery($sql);
    }

    public function AssignIncomeToAccount($id, $account){
        $sql = "UPDATE unknown_incomes SET account = $account WHERE id = $id";
        return parent::DoQuery($sql);
    }
}