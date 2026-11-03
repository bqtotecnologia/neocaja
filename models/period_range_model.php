<?php

include_once 'SQL_model.php';

class PeriodRangeModel extends SQLModel
{    
    public function CreatePeriodRange(array $data){
        $period = $data['period'];
        $start_month = $data['start_month'];
        $end_month = $data['end_month'];

        $sql = "INSERT INTO period_ranges (period, start_month, end_month) VALUES ($period, $start_month, $end_month)";
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

    public function UpdatePeriodRange(string $id, array $data){
        $period = $data['period'];
        $start_month = $data['start_month'];
        $end_month = $data['end_month'];

        $sql = "UPDATE period_ranges SET period = '$period', start_month = $start_month, end_month = $end_month WHERE id = $id";
        return parent::DoQuery($sql);
    }
}