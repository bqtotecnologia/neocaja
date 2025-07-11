<?php

include_once 'SQL_model.php';

class GlobalVarsModel extends SQLModel
{    
    public $SELECT_TEMPLATE = "SELECT
        global_vars.name,
        global_vars.id,
        global_vars_history.value,
        global_vars_history.current,
        global_vars_history.created_at as date
        FROM
        global_vars
        INNER JOIN global_vars_history ON global_vars_history.global_var = global_vars.id AND global_vars_history.current = 1";

    public function GetGlobalVar($id){
        $sql = $this->SELECT_TEMPLATE . " WHERE global_vars.id = $id";
        return parent::GetRow($sql);
    }

    public function GetGlobalVars($as_array = false){        
        $global_vars = parent::GetRows($this->SELECT_TEMPLATE, true);
        $result = [];

        if($as_array){
            foreach($global_vars as $var){
                $result[$var['name']] = $var['value'];
            }
        }
        else{
            $result = $global_vars;
        }

        return $result;
    }

    public function GetGlobalVarHistory($id){
        $sql = "SELECT
            global_vars.name,
            global_vars.id,
            global_vars_history.value,
            global_vars_history.current,
            global_vars_history.created_at as date
            FROM
            global_vars
            INNER JOIN global_vars_history ON global_vars_history.global_var = global_vars.id
            WHERE
            global_vars.id = $id
            ORDER BY
            global_vars_history.created_at DESC";

        $history = parent::GetRows($sql, true);
        return $history;
    }

    public function UpdateGlobalVar(string $id, float $value){
        $disabled = $this->DisableAllHistoryOfGlobalVar($id);
        if($disabled === false)
            return false;

        $sql = "INSERT INTO global_vars_history (global_var, value) VALUES ($id, '$value')";
        return parent::DoQuery($sql);
    }

    public function DisableAllHistoryOfGlobalVar($id){
        $sql = "UPDATE global_vars_history SET current = 0 WHERE global_var = $id";
        return parent::DoQuery($sql);
    }
}