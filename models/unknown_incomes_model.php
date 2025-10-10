<?php
include_once 'SQL_model.php';

class UnknownIncomesModel extends SQLModel
{ 
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
    public function InsertUnknownIncomes($payments){
        $sql = "INSERT INTO unknown_incomes (date, price, ref, description) VALUES ";
        foreach($payments as $payment){
            $date = $payment['date'];
            $ref = $payment['ref'];
            $description = $payment['description'];
            $price = $payment['price'];
            $sql .= "('$date', '$price', '$ref', '$description'), ";            
        }

        $sql = trim($sql, ', ');
        return parent::DoQuery($sql);
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