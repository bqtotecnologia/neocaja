<?php

include_once 'SQL_model.php';

class PeriodRangeModel extends SQLModel
{    
    public function CreatePeriodRange(array $data){
        $period = $data['period'];
        $start_month = $data['start_month'];
        $end_month = $data['end_month'];
        $extra_month = $data['first_semester_extra_month'];

        $sql = "INSERT INTO period_ranges 
            (period, start_month, end_month, first_semester_extra_month) 
            VALUES 
            ($period, $start_month, $end_month, $extra_month)";
        return parent::DoQuery($sql);
    }

    public function GetPeriodRanges(){
        $sql = "SELECT * FROM period_ranges ORDER BY period DESC";

        return parent::GetRows($sql, true);
    }

    public function GetPeriodRangeById($id){
        $sql = "SELECT * FROM period_ranges WHERE id = $id";
        return parent::GetRow($sql);
    }

    public function GetPeriodRangeOfPeriod($id){
        $sql = "SELECT * FROM period_ranges WHERE period = $id";
        return parent::GetRow($sql);
    }

    /**
     * Retorna un array con los meses del periodo, si $names es true retorna los nombres, si no los números.
     * Se debe validar primero que exista el rango del periodo, ya que la función no lo verifica
     */
    public function GetMonthsOfPeriod($id, $names = false){
        $periodMonthRange = $this->GetPeriodRangeOfPeriod($id);

        $start_month = intval($periodMonthRange['start_month']);
        $end_month = intval($periodMonthRange['end_month']);
        

        $array_months = [];
        $new_year = $start_month > $end_month;
        while(true){    
            if($names)                    
                array_push($array_months, $this->month_translate[strval($start_month)]);                
            else
                array_push($array_months, $start_month);

            $start_month++;

            if($start_month > $end_month && $new_year === false){              
                break;                
            }

            if($start_month > 12){
                $new_year = false;
                $start_month = 1;
            }
        }

        return $array_months;
    }

    public function UpdatePeriodRange(string $id, array $data){
        $period = $data['period'];
        $start_month = $data['start_month'];
        $end_month = $data['end_month'];
        $extra_month = $data['first_semester_extra_month'];

        $sql = "UPDATE period_ranges SET 
            period = '$period', 
            start_month = $start_month, 
            end_month = $end_month,
            first_semester_extra_month = $extra_month
            WHERE 
            id = $id";
        return parent::DoQuery($sql);
    }
}