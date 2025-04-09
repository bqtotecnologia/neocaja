<?php

include_once 'PGSQL_model.php';

class DocentesPeriodosModel extends PGSQLModel
{

    // Retorna a todos los docentes que han sido evaluados en algún momento de la existencia de este sistema
    public function GetAllEvaluatedDocentes(){
        $sql = "SELECT DISTINCT iddocente
            FROM
            docentes_periodos";
        $docentes = parent::GetRows($sql);
        if($docentes === false) return [];
        include_once 'siacad_model.php';
        $siacad = new SiacadModel();
        $ordered_ids = '';
        foreach($docentes as $docente){
            $ordered_ids .= $docente['iddocente'] . ', ';
        }
        return $siacad->GetDocentesOfListOfIds(trim($ordered_ids, ', '));
    }

    // Retorna el doente_periodo según el iddocente, corte y periodo
    // Si el corte recibido es null, retornará todos los del periodo escogido
    public function GetDocentePeriodo($docente, $corte, $periodo){
        $sql = "SELECT * FROM docentes_periodos WHERE iddocente=$docente AND periodo=$periodo ";
        if($corte !== null) $sql .= "AND corte='$corte'";
        return parent::GetRows($sql);
    }

    // Retorna el registro del docente en el periodo y corte actual, si no existe lo crea
    public function GetCurrentDocentePeriodo($iddocente){
        include_once 'siacad_model.php';
        $siacad = new SiacadModel();
        $id_periodo = $siacad->GetCurrentPeriodo()['idperiodo'];
        $current_corte = $siacad->GetCorteToVote();
        
        $getter_sql = "SELECT 
            *
            FROM 
            docentes_periodos
            WHERE
            iddocente = $iddocente AND
            periodo = $id_periodo AND
            corte = '$current_corte'";
        $currentDocentePeriodo = parent::GetRow($getter_sql);

        if($currentDocentePeriodo === false){
            // Si no existe, lo creamos
            $creator_sql = "INSERT INTO docentes_periodos
                (iddocente,
                periodo,
                corte)
                VALUES
                ($iddocente,
                $id_periodo,
                '$current_corte')";
            parent::DoQuery($creator_sql);
            $currentDocentePeriodo = parent::GetRow($getter_sql);
        }

        return parent::GetRow($getter_sql);
    }
     
    // Obtiene la cédula de un estudiante y retorna true si aún quedan docentes por evaluar
    public function EstudianteHaveRemainingDocentes($cedula){
        include_once 'siacad_model.php';
        $siacad = new SiacadModel();
        $id_periodo = $siacad->GetCurrentPeriodo()['idperiodo'];
        $corte_to_vote = $siacad->GetCorteToVote();   
        
        $docentes = $siacad->GetDocentesOfEstudiante($cedula);
        if ($docentes === false) // El estudiante no tiene docentes
            return false;
        
        // Obtenemos los docentes que ha evaluado el estudiante
        $sql = "SELECT 
            docentes_periodos.iddocente
            FROM 
            docentes_periodos
            INNER JOIN evaluacion ON evaluacion.docente_periodo = docentes_periodos.id
            INNER JOIN evaluador ON evaluador.cedula = evaluacion.cedula_evaluador
            WHERE
            evaluador.cedula = '$cedula' AND
            docentes_periodos.periodo = $id_periodo AND
            docentes_periodos.corte = '$corte_to_vote'";

        $evaluated_docentes = parent::GetRows($sql);
        
        if($evaluated_docentes === false) // El estudiante no ha evaluado a nadie
            return true;
        
        if(count($docentes) === count($evaluated_docentes)){
            // Los docentes evaluados coinciden con los del estudiante, ya evaluó a todos sus docentes
            return false;
        }
        else{
            if(count($docentes) > count($evaluated_docentes)){
                // Aún quedan docentes por evaluar
                return true;
            }
            return false;
        }
    }

    // Retorna los docentes que han sido evaluados por cierto evaluador según su cédula
    // Si se usa $as_list = false, los retorna como un string con los iddocente separados por comas
    public function GetMyEvaluadosDocentes($cedula, $as_list = true){
        include_once 'siacad_model.php';
        $siacad = new SiacadModel();
        $id_periodo = $siacad->GetCurrentPeriodo()['idperiodo'];
        $current_corte = $siacad->GetCorteToVote();
        
        $sql = "SELECT 
            docentes_periodos.iddocente
            FROM 
            docentes_periodos
            INNER JOIN evaluacion ON evaluacion.docente_periodo = docentes_periodos.id
            INNER JOIN evaluador ON evaluador.cedula = evaluacion.cedula_evaluador
            WHERE
            evaluador.cedula = '$cedula' AND
            docentes_periodos.periodo = $id_periodo AND
            docentes_periodos.corte = '$current_corte'";
        $docentes = parent::GetRows($sql);

        if($as_list === true)
            return $docentes;
        else{
            $ordered_ids = '-1'; // Los ids de los docentes separados por comas
            if($docentes !== false){
                $ordered_ids = '';
                foreach($docentes as $docente){
                    $ordered_ids .= "'" . $docente['iddocente'] . "', ";
                }
                $ordered_ids = trim($ordered_ids, ', ');
            }
            return $ordered_ids;
        }
    }

    public function GetDocentesPeriodosById($id){
        $sql = "SELECT * FROM docentes_periodos WHERE id=$id";
        return parent::GetRow($sql);
    }

    /**
     * Retorna los periodos en los que se han hecho evaluaciones
     */
    public function GetEvaluatedPeriodos(){
        $sql = "SELECT periodo FROM docentes_periodos GROUP BY periodo ORDER BY periodo DESC";
        $periodos = parent::GetRows($sql);

        if($periodos === false)
            $periodos = [];

        $result = [];
        foreach($periodos as $periodo)
            array_push($result, $periodo['periodo']);

        return $result;
    }

    public function GetAllEvaluatedDocentesOfCoordinador($idcoordinador){
        include_once 'siacad_model.php';
        $siacad = new SiacadModel();

        $sql = "SELECT DISTINCT iddocente
            FROM
            docentes_periodos";
        $docentes = parent::GetRows($sql);
        if($docentes === false) return [];

        $ordered_ids = '';
        foreach($docentes as $docente){
            $ordered_ids .= $docente['iddocente'] . ', ';
        }
        $ordered_ids = trim($ordered_ids, ', ');

        $coordinaciones = $siacad->GetCoordinacionesOfPersonal($idcoordinador, true);
        $oredered_coordinaciones = '';
        foreach($coordinaciones as $coordinacion){
            $oredered_coordinaciones .= $coordinacion['nombrecoordinacion'] . '%|';
        }
        $oredered_coordinaciones = trim($oredered_coordinaciones, '%|');
        
        return $siacad->GetdocentesOfCoordinacionesAndIdsList($oredered_coordinaciones, $ordered_ids);
    }
 }
 