<?php

include_once 'PGSQL_model.php';

class EvaluadorModel extends PGSQLModel
{
    public function CreateEvaluador($cedula)
    {
        include_once 'siacad_model.php';
        $siacad = new SiacadModel();
        $id_periodo = $siacad->GetCurrentPeriodo()['idperiodo'];
        $sql = "INSERT INTO evaluador (cedula, last_periodo) VALUES ('$cedula', $id_periodo)";
        parent::DoQuery($sql);        
    }

    // Retorna true si el evaluador ya termino de evaluar en el corte actual
    public function EvaluadorYaEvaluo($cedula)
    {
        include_once 'siacad_model.php';
        $siacad = new SiacadModel();
        $current_corte = $siacad->GetCorteToVote();
        $id_periodo = $siacad->GetCurrentPeriodo()['idperiodo'];
        if($current_corte === 'I') $current_corte = 'corte_1';
        else if($current_corte === 'II') $current_corte = 'corte_2';
        else if($current_corte === 'III') $current_corte = 'corte_3';
        else return true;
        
        $sql = "SELECT * 
            FROM evaluador 
            WHERE 
            cedula = '$cedula' AND
            last_periodo = $id_periodo AND
            $current_corte = true";
        
        $result = parent::GetRow($sql);
        if($result === false) return false;
        else return true;
    }

    // Retorna true si el evaluador existe, sino false
    public function EvaluadorExists($cedula){
        $sql = "SELECT * FROM evaluador WHERE cedula = '$cedula'";
        return parent::GetRow($sql);
    }

    // Actualizamos al evaluador para que marque como votado en el corte correspondiente
    public function UpdateEvaluatedCorte($cedula, $corte){
        if($corte === 'corte_1')
            $sql = "UPDATE evaluador SET corte_1 = TRUE WHERE cedula='$cedula'";
        else if($corte === 'corte_2')
            $sql = "UPDATE evaluador SET corte_1 = TRUE, corte_2 = TRUE WHERE cedula='$cedula'";
        else if($corte === 'corte_3')
            $sql = "UPDATE evaluador SET corte_1 = TRUE, corte_2 = TRUE, corte_3 = TRUE WHERE cedula='$cedula'";
        parent::DoQuery($sql);        
    }
}
