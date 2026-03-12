<?php
include_once 'SQL_model.php';

class UnknownIncomesModel extends SQLModel
{ 
    public $INCOME_SELECT_TEMPLATE = "SELECT
        unknown_incomes_generations.id as generation,
        unknown_incomes_generations.created_at,
        unknown_incomes.id,
        unknown_incomes.date,
        unknown_incomes.price,
        unknown_incomes.ref,
        unknown_incomes.description,
        unknown_incomes.remote_payment,
        polymorph.names,
        polymorph.surnames,
        polymorph.cedula,
        polymorph.account_id
        FROM 
        unknown_incomes_generations 
        INNER JOIN unknown_incomes ON unknown_incomes.generation = unknown_incomes_generations.id
        LEFT JOIN remote_payments ON remote_payments.id = unknown_incomes.remote_payment       
        LEFT JOIN
        (
            SELECT 
            accounts.names,
            accounts.surnames,
            accounts.cedula,
            accounts.id as account_id,
            invoices.id as invoice_id
            FROM
            accounts
            LEFT JOIN invoices ON invoices.account = accounts.id
        )
        as polymorph ON
        (polymorph.account_id = remote_payments.related_id AND remote_payments.related_with = 'client') 
        OR
        (polymorph.invoice_id = remote_payments.related_id AND remote_payments.related_with = 'invoice') 
        -- Here yo need to place GROUP BY unknown_incomes.id before writting a WHERE statement       
        ";

    public $GENERATION_SELECT_TEMPLATE = "SELECT
        unknown_incomes_generations.id,
        unknown_incomes_generations.created_at,
        COUNT(unknown_incomes.id) as created
        FROM
        unknown_incomes_generations
        INNER JOIN unknown_incomes ON unknown_incomes.generation = unknown_incomes_generations.id ";
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

    public function GetUnknownIncomesByDateAndReference($date, $ref){
        $sql = $this->INCOME_SELECT_TEMPLATE . " WHERE 
            DATE(unknown_incomes.date) = DATE('$date') AND 
            unknown_incomes.ref LIKE '%$ref%' 
            GROUP BY 
            unknown_incomes.id";

        return parent::GetRows($sql, true);
    }

    public function GetUnknownIncomesGeneration($id){
        return parent::GetRow($this->GENERATION_SELECT_TEMPLATE . " WHERE unknown_incomes_generations.id = $id");
    }

    public function GetUnknownIncomesGenerations(){
        $sql = $this->GENERATION_SELECT_TEMPLATE . " ORDER BY unknown_incomes_generations.created_at DESC";
        return parent::GetRows($sql);
    }

    public function GetUnknownIncomesOfGeneration($id){
        return parent::GetRows($this->INCOME_SELECT_TEMPLATE . " WHERE unknown_incomes_generations.id = $id");
    }

    public function GetUnknownIncome($id){
        return parent::GetRow($this->INCOME_SELECT_TEMPLATE . " WHERE unknown_incomes.id = $id");
    }

    public function GetIdentifiedIncomesBetweenDates($start_date, $end_date){
        $sql = $this->INCOME_SELECT_TEMPLATE . " WHERE 
            unknown_incomes.date BETWEEN '$start_date' AND '$end_date' AND
            unknown_incomes.account IS NOT NULL";

        return parent::GetRows($sql);
    }

    public function GetUnknownIncomesByDate($date, $identified = false){
        $sql = $this->INCOME_SELECT_TEMPLATE . " WHERE DATE(unknown_incomes.date) = '$date'";

        if($identified)
            $sql .= ' AND unknown_incomes.account IS NOT NULL';
        else
            $sql .= ' AND unknown_incomes.account IS NULL';

        return parent::GetRows($sql, true);
    }

    public function UnlinkFromRemotePayment($id){
        $sql = "UPDATE unknown_incomes SET remote_payment = NULL WHERE remote_payment = $id";
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