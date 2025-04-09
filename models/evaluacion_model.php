<?php

include_once 'PGSQL_model.php';

class EvaluacionModel extends PGSQLModel
{
    // Recibimos información para crear la evaluacion
    // Si la evaluación ya existe retorna false, si se crea bien retorna true
    public function CreateEvaluacion($data)
    {
        include_once 'siacad_model.php';
        include_once 'criterio_model.php';
        $criterio_model = new CriterioModel();
        $siacad = new SiacadModel();

        $cedula_evaluador = $data['cedula'];
        $iddocente = $data['docente'];
        $observacion = $data['observacion'];
        $criterios = $data['criterios'];
        $enunciados = $data['enunciados'];
        $enunciados_ids = $data['enunciados_ids'];
        $user_type = $data['user_type'];
        $corte_to_vote = $siacad->GetCorteToVote();
        $id_periodo = $siacad->GetCurrentPeriodo()['idperiodo'];

        $maximo_posible = $criterio_model->GetMaxPossibleOfTheseEnunciados($enunciados_ids);
        
        $sumatoria = 0;

        foreach($criterios as $criterio)
            $sumatoria += $criterio['peso'];

        $sql = "SELECT
            evaluacion.id
            FROM 
            evaluacion
            INNER JOIN docentes_periodos ON docentes_periodos.id = evaluacion.docente_periodo
            WHERE
            evaluacion.cedula_evaluador='$cedula_evaluador' AND
            docentes_periodos.iddocente=$iddocente AND
            docentes_periodos.periodo=$id_periodo AND
            docentes_periodos.corte='$corte_to_vote'";

        $evaluation = parent::GetRow($sql);
        
        if($evaluation === false){
            // Se procede a crear la evaluacion
            include_once 'docentes_periodos_model.php';
            $docentes_periodos = new DocentesPeriodosModel();
            $current_docente_periodo = $docentes_periodos->GetCurrentDocentePeriodo($iddocente);
            $current_docente_periodo_id = $current_docente_periodo['id'];
            $sql = "INSERT INTO evaluacion 
                (cedula_evaluador, 
                docente_periodo, 
                observacion, 
                sumatoria, 
                maximo_posible,
                tipo) 
                VALUES 
                ('$cedula_evaluador', 
                $current_docente_periodo_id,
                '$observacion', 
                $sumatoria,
                $maximo_posible,
                '$user_type')";
            parent::DoQuery($sql);
            $sql = "SELECT * FROM evaluacion
                WHERE
                cedula_evaluador = '$cedula_evaluador' AND
                docente_periodo = $current_docente_periodo_id AND
                observacion = '$observacion' AND
                sumatoria = $sumatoria AND
                maximo_posible = $maximo_posible AND
                tipo = '$user_type'";
            $current_evaluation = parent::GetRow($sql);
            if($current_evaluation === false) return false;

            $this->CreateDetalleEvaluacion($enunciados, $current_evaluation['id']);
            include_once 'estadistica_docente_model.php';
            $estadisticaDocente = new EstadisticaDocenteModel();
            $estadisticaDocente->UpdateEstadisticaDocente($current_docente_periodo_id, $enunciados, $user_type);
        }else{
            // El estudiante ya evaluó a ese docente ne este corte
            return false;
        }
        return true;
    }

    // Retorna true si el docente se ha autoevaluado este corte y periodo
    public function GetAutoevaluacionOf($iddocente, $cedula){
        include_once 'siacad_model.php';
        $siacad = new SiacadModel();
        $id_periodo = $siacad->GetCurrentPeriodo()['idperiodo'];
        $corte = $siacad->GetCorteToVote();

        $sql = "SELECT
            evaluacion.id
            FROM
            evaluacion
            INNER JOIN docentes_periodos ON docentes_periodos.id = evaluacion.docente_periodo
            WHERE
            evaluacion.cedula_evaluador = '$cedula' AND
            docentes_periodos.corte = '$corte' AND
            docentes_periodos.periodo = $id_periodo AND
            docentes_periodos.iddocente = $iddocente";

        if(parent::GetRow($sql) !== false)
            return true;
        else
            return false;
    }

    // Retorna el número de estudiantes, docentes y coordinadores que han evaluado divididos en los cortes de un periodo
    // Si no recibe un periodo por parámetro, lo hace con el periodo actual
    public function GetEvaluationsNumbersByType($periodo = null){
        include_once 'siacad_model.php';
        $siacad = new SiacadModel();
        $target_periodo = $periodo;
        if($periodo === null)
            $target_periodo = $siacad->GetCurrentPeriodo()['idperiodo'];

        $sql = "SELECT 
            corte,
            tipo,
            COUNT(tipo) as cantidad
            FROM
                (
                SELECT
                evaluacion.cedula_evaluador,
                evaluacion.tipo as tipo,
                docentes_periodos.corte as corte
                FROM
                evaluacion
                INNER JOIN docentes_periodos ON docentes_periodos.id = evaluacion.docente_periodo
                WHERE
                docentes_periodos.periodo = $target_periodo
                GROUP BY
                docentes_periodos.corte,
                evaluacion.cedula_evaluador,
                evaluacion.tipo
                ) 
            as query
            GROUP BY
            corte,
            tipo";


        $rows = parent::GetRows($sql);
        if($rows === false) return [];
        include_once 'admin_model.php';
        $admin_model = new AdminModel();
        $total_coordinadores = count($admin_model->GetCoordinadores(true));
        $total_docentes = count($siacad->GetActiveDocentes($target_periodo));
        $total_students = count($siacad->GetActiveStudents($target_periodo));


        $result = [];
        $type_translator = [
            'student' => 'Estudiantes',
            'teacher' => 'Docentes',
            'coord' => 'Coordinadores'
        ];
        foreach($rows as $row){
            if(!isset($result[$type_translator[$row['tipo']]])) 
            $result[$type_translator[$row['tipo']]] = array(
                'I' => ['cantidad' => 0, 'maximo' => 0], 
                'II' => ['cantidad' => 0, 'maximo' => 0], 
                'III' => ['cantidad' => 0, 'maximo' => 0]
            );
            $result[$type_translator[$row['tipo']]][$row['corte']]['cantidad'] = $row['cantidad'];

            if($row['tipo'] === 'student'){
                $result[$type_translator[$row['tipo']]][$row['corte']]['maximo'] = $total_students;
            }
            else if($row['tipo'] === 'teacher'){
                $result[$type_translator[$row['tipo']]][$row['corte']]['maximo'] = $total_docentes;
            }
            else if($row['tipo'] === 'coord'){
                $result[$type_translator[$row['tipo']]][$row['corte']]['maximo'] = $total_coordinadores;
            }
        }
        return $result;
    }

    // Retorna el número de evaluaciones por carrera
    public function GetEvaluationsByCarreer($idperiodo = null){
        include_once 'siacad_model.php';
        $siacad = new SiacadModel();

        $target_periodo = $idperiodo === null ? $siacad->GetCurrentPeriodo()['idperiodo'] : $idperiodo;
        $sql = "SELECT DISTINCT
            evaluacion.cedula_evaluador as cedula,
            docentes_periodos.corte
            FROM
            evaluacion
            INNER JOIN docentes_periodos ON docentes_periodos.id = evaluacion.docente_periodo
            WHERE
            docentes_periodos.periodo = $target_periodo AND
            evaluacion.tipo = 'student'
            GROUP BY
            evaluacion.cedula_evaluador,
            docentes_periodos.corte
            ORDER BY
            docentes_periodos.corte,
            evaluacion.cedula_evaluador";

        $participants = parent::GetRows($sql);
        if($participants === false) return [];
        return $siacad->GetEstudiantesOfCedulaListByCarrera($participants, $target_periodo);
    }

    public function GetEvaluacionNumberOfDocente($data){
        $iddocente = $data['docente'];
        $corte = $data['corte'];
        $tipo = $data['tipo'];
        $periodo = $data['periodo'];
        $cedulas = $data['cedulas'];

        $sql = "SELECT
            COUNT(evaluacion.id) as evaluaciones
            FROM
            evaluacion
            INNER JOIN docentes_periodos ON docentes_periodos.id = evaluacion.docente_periodo
            WHERE
            docentes_periodos.iddocente = $iddocente AND
            docentes_periodos.periodo = $periodo AND
            evaluacion.tipo = '$tipo' AND
            evaluacion.cedula_evaluador IN ($cedulas)";

        if($corte !== null)
            $sql .= " AND docentes_periodos.corte = '$corte'";

        return parent::GetRow($sql)['evaluaciones'];
    }

    public function GetEvaluacionById($id){
        $sql = "SELECT * FROM evaluacion WHERE id=$id";
        return parent::GetRow($sql);
    }

    // Registra cada enunciado y cada criterio de una evaluación
    public function CreateDetalleEvaluacion($enunciados, $id_evaluacion){
        $sql = "INSERT INTO detalle_evaluacion
            (evaluacion, enunciado, criterio) VALUES ";

        foreach($enunciados as $enunciado_id => $criterios){
            foreach(explode(', ',$criterios) as $criterio_id){
                $sql .= "($id_evaluacion, $enunciado_id, $criterio_id), ";
            }
        }
        $sql = trim($sql, ', ');
        parent::DoQuery($sql);
    }

    // Retorna los docentes restantes por evaluar de un estudiante
    public function GetDocentesLeftOfEstudiante($cedula_estudiante){
        include_once 'siacad_model.php';
        include_once 'docentes_periodos_model.php';
        $siacad = new SiacadModel();
        $docentes_periodos = new DocentesPeriodosModel();

        // Obtenemos los ids de los docentes separados por comas
        $ordered_ids = $docentes_periodos->GetMyEvaluadosDocentes($cedula_estudiante, false);
        return $siacad->GetDocentesOfStudentNotInList($cedula_estudiante, $ordered_ids);
    }

    // Retorna todas las evaluaciones realizadas por el docente recibido por parámetro
    public function GetMyEvaluations($iddocente){
        include_once 'siacad_model.php';
        $siacad = new SiacadModel();
        $docente = $siacad->GetDocenteById($iddocente);
        if($docente === false) return [];

        $cedula_docente = $docente['cedula'];
        $sql = "SELECT
            evaluacion.id,
            evaluacion.fecha,
            docentes_periodos.iddocente,
            docentes_periodos.corte,
            docentes_periodos.periodo,
            evaluacion.maximo_posible,
            evaluacion.sumatoria
            FROM
            evaluacion
            INNER JOIN docentes_periodos ON docentes_periodos.id = evaluacion.docente_periodo
            WHERE
            evaluacion.cedula_evaluador ='$cedula_docente'";
        $evaluaciones = parent::GetRows($sql);
        if($evaluaciones === false) return [];
        
        $result = [];
        foreach($evaluaciones as $evaluacion){
            $target_docente = $siacad->GetDocenteById($evaluacion['iddocente']);
            $to_add = [
                'id' => $evaluacion['id'],
                'fecha' => $evaluacion['fecha'],
                'nombre' => $target_docente['nombres'] . ' ' . $target_docente['apellidos'],
                'total' => ($evaluacion['sumatoria'] * 20) / $evaluacion['maximo_posible'],
                'corte' => $evaluacion['corte'],
                'periodo' => $siacad->GetPeriodoById($evaluacion['periodo'])['nombreperiodo']
            ];
            array_push($result, $to_add);
        }
        return $result;
    }

    // Retorna los docentes que le falta por evaluar a un coordinador
    public function GetDocentesLeftOfCoordinador($cedula_coordinador){
        include_once 'siacad_model.php';
        include_once 'docentes_periodos_model.php';
        $siacad = new SiacadModel();
        $docentes_periodos = new DocentesPeriodosModel();

        // Obtenemos los ids de los docentes separados por comas
        $ordered_ids = $docentes_periodos->GetMyEvaluadosDocentes($cedula_coordinador, false);
        return $siacad->GetNotEvaluadosDocentesOfCoordinador($cedula_coordinador, $ordered_ids);
    }

    // Retorna los coordinadores que le falta pore valuar a un docente
    public function GetCoordinadoresLeftOfDocente($cedula_docente){
        include_once 'siacad_model.php';
        include_once 'docentes_periodos_model.php';
        $siacad = new SiacadModel();
        $docentes_periodos = new DocentesPeriodosModel();

        // Obtenemos los ids de los docentes separados por comas
        $ordered_ids = $docentes_periodos->GetMyEvaluadosDocentes($cedula_docente, false);
        return $siacad->GetCoordinadoresOfDocenteNotInList($cedula_docente, $ordered_ids);
    }

    // Recibe el id de un voto y retorna el total de puntuación de todos sus criterios
    public function GetTotalsOfEvaluacion($id_evaluacion){
        $sql = "SELECT 
            *
            FROM 
            evaluacion 
            WHERE 
            evaluacion.id = $id_evaluacion";

        $evaluation = parent::GetRow($sql);
        if($evaluation === false) return false;

        $result = [];
        include_once 'criterio_model.php';
        $criterio_model = new CriterioModel();
        $id_evaluacion = $evaluation['id'];

        if($evaluation['tipo'] === 'student'){
            $sql = "SELECT 
            categoria.nombre as categoria,
            dimension.nombre as dimension,
            indicador.nombre as indicador,
            enunciado.nombre as enunciado,
            enunciado.id as enunciado_id,
            criterio.nombre as criterio,
            COUNT(detalle_evaluacion.criterio) as cantidad,
            criterio.peso as peso
            FROM
            detalle_evaluacion
            INNER JOIN evaluacion ON evaluacion.id = detalle_evaluacion.evaluacion
            INNER JOIN enunciado ON enunciado.id = detalle_evaluacion.enunciado
            INNER JOIN criterio ON criterio.id = detalle_evaluacion.criterio
            INNER JOIN indicador ON indicador.id = enunciado.indicador
            INNER JOIN dimension ON dimension.id = indicador.dimension
            INNER JOIN categoria ON categoria.id = dimension.categoria
            WHERE
            evaluacion.id = $id_evaluacion
            GROUP BY
            indicador.id,
            enunciado.id,
            criterio.id
            ORDER BY
            indicador.id,
            enunciado.id,
            criterio.id";
    
            $criterios = parent::GetRows($sql);
    
            if($criterios === false) $criterios = [];
            
            foreach ($criterios as $full_criterio) {
                if(
                    !isset($result[$full_criterio['categoria']]
                    [$full_criterio['dimension']]
                    [$full_criterio['indicador']]
                    [$full_criterio['enunciado']])
                )
                    $result[$full_criterio['categoria']]
                    [$full_criterio['dimension']]
                    [$full_criterio['indicador']]
                    [$full_criterio['enunciado']] = array(
                        'maximo_posible' => $criterio_model->GetMaxPossibleOfTheseEnunciados($full_criterio['enunciado_id']),
                        'criterios' => array()
                    );
                    
                    array_push($result[$full_criterio['categoria']]
                    [$full_criterio['dimension']]
                    [$full_criterio['indicador']]
                    [$full_criterio['enunciado']]['criterios'], array(
                        'nombre' => $full_criterio['criterio'],
                        'cantidad' => $full_criterio['cantidad'],
                        'peso' => $full_criterio['peso']
                    ));
            }
        }
        else{    
            $sql = "SELECT 
                indicador.nombre as indicador,
                enunciado.nombre as enunciado,
                enunciado.id as enunciado_id,
                criterio.nombre as criterio,
                COUNT(detalle_evaluacion.criterio) as cantidad,
                criterio.peso as peso
                FROM
                detalle_evaluacion
                INNER JOIN evaluacion ON evaluacion.id = detalle_evaluacion.evaluacion
                INNER JOIN enunciado ON enunciado.id = detalle_evaluacion.enunciado
                INNER JOIN criterio ON criterio.id = detalle_evaluacion.criterio
                INNER JOIN indicador ON indicador.id = enunciado.indicador
                WHERE
                evaluacion.id = $id_evaluacion
                GROUP BY
                indicador.id,
                enunciado.id,
                criterio.id
                ORDER BY
                indicador.id,
                enunciado.id,
                criterio.id";
    
            $criterios = parent::GetRows($sql);
    
            if($criterios === false) $criterios = [];

            foreach ($criterios as $full_criterio) {
                if(
                    !isset($result[$full_criterio['indicador']]
                    [$full_criterio['enunciado']])
                )
                    $result[$full_criterio['indicador']]
                    [$full_criterio['enunciado']] = array(
                        'maximo_posible' => $criterio_model->GetMaxPossibleOfTheseEnunciados($full_criterio['enunciado_id']),
                        'criterios' => array()
                    );

                    
                    $result[$full_criterio['indicador']]
                    [$full_criterio['enunciado']]['criterios']
                    [$full_criterio['criterio']] = array(
                        'cantidad' => $full_criterio['cantidad'],
                        'peso' => $full_criterio['peso'] * $full_criterio['cantidad']
                    );
            }
        }
        return $result;
    }

    // Retorna todos los votos con una observacion no-vacía del periodo actual
    public function GetObservacionesOfDocenteIn($periodo, $corte, $iddocente){
        $sql = "SELECT 
            evaluacion.observacion
            FROM
            evaluacion
            INNER JOIN docentes_periodos ON docentes_periodos.id = evaluacion.docente_periodo
            WHERE
            docentes_periodos.periodo = $periodo AND
            docentes_periodos.iddocente = $iddocente AND
            evaluacion.observacion != ''";

        if($corte !== null)
            $sql .= " AND docentes_periodos.corte = '$corte' ";

        $sql .=  "ORDER BY evaluacion.fecha";
        return parent::GetRows($sql);
    }

    public function GetObservacionesOfPeriodo($periodo){
        $sql = "SELECT
            docentes_periodos.iddocente,
            evaluacion.cedula_evaluador,
            evaluacion.observacion
            from
            evaluacion
            INNER JOIN docentes_periodos ON docentes_periodos.id = evaluacion.docente_periodo
            WHERE
            docentes_periodos.periodo = $periodo AND
            evaluacion.observacion != '' AND
            evaluacion.observacion != ' '
            ORDER BY
            docentes_periodos.iddocente,
            evaluacion.observacion";

        $observaciones = parent::GetRows($sql);
        if($observaciones === false)
            $observaciones = [];

        return $observaciones;
    }

    // Retorna el número de evaluaciones realizadas por los estudiantes al docente
    public function GetEvaluacionesNumberOfDocenteOf($periodo, $corte, $iddocente, $tipo){
        $sql = "SELECT COUNT(evaluacion.id) as cantidad
            FROM
            evaluacion
            INNER JOIN docentes_periodos ON docentes_periodos.id = evaluacion.docente_periodo
            WHERE
            docentes_periodos.periodo = $periodo AND
            docentes_periodos.iddocente = $iddocente AND
            evaluacion.tipo = '$tipo'";

        if($corte !== NULL)
            $sql .= " AND docentes_periodos.corte = '$corte'";

        return parent::GetRow($sql)['cantidad'];
    }

    // Retorna todos los periodos en que se han hecho evaluaciones
    public function GetPeriodosOfEvaluaciones(){
        $sql = "SELECT DISTINCT periodo FROM docentes_periodos";
        $periodos = parent::GetRows($sql);
        $ordered_periodos = '';
        foreach($periodos as $periodo) { $ordered_periodos .= $periodo['periodo'] . ', '; }
        $ordered_periodos = trim($ordered_periodos, ', ');
        include_once 'siacad_model.php';
        $siacad = new SiacadModel();
        return $siacad->GetPeriodosByListOfIds($ordered_periodos);
    }

    // Recibe cedula de un usuario y id de un docente
    // Retorna true si el usuario puede evaluarlo en este corte y periodo
    public function UserCanEvaluate($user_cedula, $target_id){
        include_once 'siacad_model.php';
        $siacad = new SiacadModel();
        $id_periodo = $siacad->GetCurrentPeriodo()['idperiodo'];
        $current_corte = $siacad->GetCorteToVote();
        if($current_corte === 'no permitido') return false;
        
        $sql = "SELECT
            evaluacion.id
            FROM
            evaluacion
            INNER JOIN docentes_periodos ON docentes_periodos.id = evaluacion.docente_periodo
            WHERE
            evaluacion.cedula_evaluador = '$user_cedula' AND
            docentes_periodos.periodo = $id_periodo AND
            docentes_periodos.iddocente = $target_id AND
            docentes_periodos.corte = '$current_corte'";
        
        $evaluations = parent::GetRow($sql);
        if($evaluations === false) return true; // El usuario no ha evaluado al objetivo
        else return false; // El usuario ya lo evaluó así que no puede evaluarlo
    }

    // Obtiene un iddocente y retorna una lista indicando si el docente tiene evaluaciones
    // Las ordena en 'periodo' => 'corte' => 'student/teacher/coord/observaciones' => true
    // También lo retorna por secciones 'periodo' => 'secciones'
    public function DocenteHaveEvaluaciones($iddocente){
        $sql = "SELECT
            docentes_periodos.periodo as periodo,
            docentes_periodos.corte as corte,
            evaluacion.tipo as tipo,
            evaluacion.cedula_evaluador as evaluador,
            evaluacion.observacion as observaciones
            FROM
            docentes_periodos
            INNER JOIN evaluacion ON evaluacion.docente_periodo = docentes_periodos.id
            WHERE
            docentes_periodos.iddocente = $iddocente";
        $rows = parent::GetRows($sql);
        if($rows === false) return [];

        $result = [];
        foreach($rows as $row){
            if(!isset($result[$row['periodo']]))
                $result[$row['periodo']] = array();

            if(!isset($result[$row['periodo']][$row['corte']])) {
                $result[$row['periodo']][$row['corte']] = array(
                    'student' => false,
                    'coord' => false,
                    'teacher' => false,
                    'observaciones' => false
                );
            }
            if(!isset($result[$row['periodo']][$row['corte']][$row['tipo']])) 
                $result[$row['periodo']][$row['corte']][$row['tipo']] = array();

            if($row['observaciones'] !== '')
                $result[$row['periodo']][$row['corte']]['observaciones'] = true;
            
            $result[$row['periodo']][$row['corte']][$row['tipo']] = true;
        }
        return $result;
    }

    public function GetEvaluacionesBySecciones($iddocente, $corte, $periodo){
        include_once 'siacad_model.php';
        $siacad = new SiacadModel();

        $sql = "SELECT DISTINCT
            evaluacion.cedula_evaluador as cedula
            FROM
            evaluacion
            INNER JOIN docentes_periodos ON docentes_periodos.id = evaluacion.docente_periodo
            WHERE
            docentes_periodos.iddocente = $iddocente AND
            docentes_periodos.periodo = $periodo ";

        if($corte !== null)
            $sql .= " AND docentes_periodos.corte = '$corte'";

        $sql .= " ORDER BY evaluacion.cedula_evaluador";
        $cedulas = parent::GetRows($sql);

        $ordered_cedulas = '';
        foreach($cedulas as $cedula){
            $ordered_cedulas .= "'" . $cedula['cedula'] . "', ";
        }
        $ordered_cedulas = trim($ordered_cedulas, ', ');
        return $siacad->GetEvaluacionesOfDocenteBySeccionByCedulasEstudiantes($ordered_cedulas, $periodo, $iddocente);
    }
 }