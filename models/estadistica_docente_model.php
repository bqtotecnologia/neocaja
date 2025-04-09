<?php

include_once 'PGSQL_model.php';

class EstadisticaDocenteModel extends PGSQLModel
{
    public function UpdateEstadisticaDocente($docente_periodo, $recieved_enunciados, $type){
        $sql = "SELECT
            estadistica_docente.enunciado
            FROM
            estadistica_docente
            INNER JOIN enunciado ON enunciado.id = estadistica_docente.enunciado
            INNER JOIN indicador ON indicador.id = enunciado.indicador
            WHERE
            estadistica_docente.docente_periodo = $docente_periodo AND
            indicador.tipo = '$type'
            GROUP BY
            estadistica_docente.enunciado";
        $real_enunciados = parent::GetRows($sql);

        if($real_enunciados === false){
            // Si no existe la creamos
            include_once 'criterio_model.php';
            $criterio_model = new CriterioModel();
            $all_enunciados = $criterio_model->GetFullEnunciados();

            $sql = "INSERT INTO estadistica_docente
                    (docente_periodo, enunciado, criterio) VALUES ";
            foreach($all_enunciados as $enunciado){
                $criterio_id = $enunciado['id_criterio'];
                $enunciado_id = $enunciado['id_enunciado'];
                $sql .= "
                ($docente_periodo,
                $enunciado_id,
                $criterio_id), ";
            }
            $sql = rtrim($sql, ', '); // Le quitamos la última ,
            parent::DoQuery($sql);
        }
        else{
            // Si ya existe, tenemos que verificar que no haya ningún enunciado de sobra
            // De ser así, lo agregamos
            $not_included = [];
            
            if(count($real_enunciados) !== count($recieved_enunciados)){
                $ordered_enunciados = [];
                foreach($real_enunciados as $enunciado)  { array_push($ordered_enunciados, $enunciado['enunciado']); }
                foreach($recieved_enunciados as $enunciado_id => $criterios){
                    if(!in_array($enunciado_id, $ordered_enunciados)){
                        $not_included[$enunciado_id] = $criterios;
                    }
                }
            }
            
            if($not_included !== []){
                $sql = "INSERT INTO estadistica_docente
                    (docente_periodo, enunciado, criterio) VALUES ";
                foreach($not_included as $enunciado_id => $criterios){
                    foreach(explode(', ', $criterios) as $criterio){
                        $sql .= "
                        ($docente_periodo,
                        $enunciado_id,
                        $criterio), ";
                    }
                }
                $sql = rtrim($sql, ', '); // Le quitamos la última ,
                parent::DoQuery($sql);
            }
        }


        // Actualizamos los criterios aumentando en 1 la cantidad
        $sql = "UPDATE 
            estadistica_docente
            SET 
            cantidad = cantidad + 1 
            FROM
            docentes_periodos
            WHERE 
            docentes_periodos.id = estadistica_docente.docente_periodo AND
            docentes_periodos.id = $docente_periodo AND (";

        foreach($recieved_enunciados as $enunciado_id => $criterios_ids){
            $sql .= "(estadistica_docente.enunciado = $enunciado_id AND
                estadistica_docente.criterio IN ($criterios_ids)) OR ";
        }

        $sql = trim($sql, ' OR ') . ')';
        parent::DoQuery($sql);
    }

    // Retorna los resultados de las evaluaciones de estudiantes hacia un docente listas para ser plasmadas en gráficos
    public function GetTotalsOfDocente($data){
        $docente = $data['docente'];
        $corte = isset($data['corte']) ? $data['corte'] : null;
        $periodo = $data['periodo'];
        include_once 'docentes_periodos_model.php';
        $docente_model = new DocentesPeriodosModel();
        $docentes_periodos = $docente_model->GetDocentePeriodo($docente, $corte, $periodo);
        if($docentes_periodos === false) return false;  

        if($data['tipo'] === 'student') 
            return $this->GetTotalsOfDocenteByEstudiantes($docentes_periodos);
        else 
            return $this->GetTotalsOfDocenteByOthers($docentes_periodos, $data['tipo']);
    }

    // Retorna las estadísticas de las evaluaciones de los estudiantes hacia un docente
    private function GetTotalsOfDocenteByEstudiantes($docentes_periodos){
        $result = [];
        include_once 'criterio_model.php';
        $criterio_model = new CriterioModel();
        foreach($docentes_periodos as $docente_periodo) {
            $id_docente_periodo = $docente_periodo['id'];
    
            $sql = "SELECT 
            categoria.nombre as categoria,
            dimension.nombre as dimension,
            indicador.nombre as indicador,
            enunciado.nombre as enunciado,
            enunciado.id as enunciado_id,
            criterio.nombre as criterio,
            estadistica_docente.cantidad,
            criterio.peso as peso
            FROM
            estadistica_docente
            INNER JOIN enunciado ON enunciado.id = estadistica_docente.enunciado
            INNER JOIN criterio ON criterio.id = estadistica_docente.criterio
            INNER JOIN indicador ON indicador.id = enunciado.indicador
            INNER JOIN dimension ON dimension.id = indicador.dimension
            INNER JOIN categoria ON categoria.id = dimension.categoria
            WHERE
            estadistica_docente.docente_periodo = $id_docente_periodo AND
            estadistica_docente.cantidad > 0
            ORDER BY
            categoria.id,
            dimension.id,
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
                ){
                    $result[$full_criterio['categoria']]
                    [$full_criterio['dimension']]
                    [$full_criterio['indicador']]
                    [$full_criterio['enunciado']] = array(
                        'maximo_posible' => $criterio_model->GetMaxPossibleOfTheseEnunciados($full_criterio['enunciado_id']),
                        'criterios' => array()
                    );
                }

                $result[$full_criterio['categoria']]
                [$full_criterio['dimension']]
                [$full_criterio['indicador']]
                [$full_criterio['enunciado']]['criterios']
                [$full_criterio['criterio']] = array(
                    'cantidad' => $full_criterio['cantidad'],
                    'peso' => $full_criterio['peso']
                );
            }
        }
        return $result;
    }

    // Recibe una lista de docentes_periodos y retorna los totales evaluados por docentes/coordinadores
    private function GetTotalsOfDocenteByOthers($docentes_periodos, $tipo){
        $result = [];
        include_once 'criterio_model.php';
        $criterio_model = new CriterioModel();
        foreach($docentes_periodos as $docente_periodo) {
            $id_docente_periodo = $docente_periodo['id'];

            $sql = "SELECT 
            indicador.nombre as indicador,
            enunciado.nombre as enunciado,
            enunciado.id as enunciado_id,
            criterio.nombre as criterio,
            estadistica_docente.cantidad,
            criterio.peso as peso
            FROM
            estadistica_docente
            INNER JOIN docentes_periodos ON docentes_periodos.id = estadistica_docente.docente_periodo
            INNER JOIN evaluacion ON evaluacion.docente_periodo = docentes_periodos.id
            INNER JOIN enunciado ON enunciado.id = estadistica_docente.enunciado
            INNER JOIN criterio ON criterio.id = estadistica_docente.criterio
            INNER JOIN indicador ON indicador.id = enunciado.indicador
            WHERE
            estadistica_docente.docente_periodo = $id_docente_periodo AND
            estadistica_docente.cantidad > 0 AND
            evaluacion.tipo = '$tipo' AND
            indicador.tipo = '$tipo'
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
                    [$full_criterio['criterio']]  = array(
                        'cantidad' => $full_criterio['cantidad'],
                        'peso' => $full_criterio['peso']
                    );
            }
        }
        return $result;
    }

    public function GetMaximoPosibleOfDocenteOf($periodo, $corte, $iddocente, $tipo){
        $sql = "SELECT 
            COUNT(evaluacion.id) as evaluaciones,
            MAX(evaluacion.maximo_posible) as maximo
            FROM
            evaluacion
            INNER JOIN docentes_periodos ON docentes_periodos.id = evaluacion.docente_periodo
            WHERE
            docentes_periodos.periodo = $periodo AND
            docentes_periodos.iddocente = $iddocente AND
            evaluacion.tipo = '$tipo' ";

        if($corte !== NULL)
            $sql .= "AND docentes_periodos.corte = '$corte'";

        $sql .= "GROUP BY docentes_periodos.corte";
        $rows = parent::GetRows($sql);
        if($rows === false) return 0;
        $total = 0;
        foreach($rows as $row){
            $total += $row['maximo'];
        }
        return $total;
    }

    public function GetTotalsOfDocenteByListOfCedulas($data){
        $iddocente = $data['docente'];
        $corte = $data['corte'];
        $tipo = $data['tipo'];
        $periodo = $data['periodo'];
        $cedulas = $data['cedulas'];

        include_once 'criterio_model.php';
        $criterio_model = new CriterioModel();

        $sql = "SELECT 
            categoria.nombre as categoria,
            dimension.nombre as dimension,
            indicador.nombre as indicador,
            enunciado.nombre as enunciado,
            enunciado.id as enunciado_id,
            criterio.nombre as criterio,
            criterio.peso as peso
            FROM
            evaluacion
            INNER JOIN docentes_periodos ON docentes_periodos.id = evaluacion.docente_periodo
            INNER JOIN detalle_evaluacion ON detalle_evaluacion.evaluacion = evaluacion.id
            INNER JOIN enunciado ON enunciado.id = detalle_evaluacion.enunciado
            INNER JOIN criterio ON criterio.id = detalle_evaluacion.criterio
            INNER JOIN indicador ON indicador.id = enunciado.indicador
            INNER JOIN dimension ON dimension.id = indicador.dimension
            INNER JOIN categoria ON categoria.id = dimension.categoria
            WHERE
            docentes_periodos.periodo = $periodo AND";

        if($corte !== null)
            $sql .= " docentes_periodos.corte = '$corte' AND ";
            
            $sql .= " docentes_periodos.iddocente = $iddocente AND
            evaluacion.cedula_evaluador IN ($cedulas) AND
            evaluacion.tipo = '$tipo'
            ORDER BY
            categoria.id,
            dimension.id,
            indicador.id,
            enunciado.id,
            criterio.id";

        $rows = parent::GetRows($sql);
        if($rows === false) return [];
        $result = [];

        foreach ($rows as $full_criterio) {
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
                
                if(!isset($result[$full_criterio['categoria']]
                [$full_criterio['dimension']]
                [$full_criterio['indicador']]
                [$full_criterio['enunciado']]['criterios']
                [$full_criterio['criterio']])){
                    $result[$full_criterio['categoria']]
                    [$full_criterio['dimension']]
                    [$full_criterio['indicador']]
                    [$full_criterio['enunciado']]['criterios']
                    [$full_criterio['criterio']] = array(
                        'cantidad' => 0,
                        'peso' => $full_criterio['peso']
                    );
                }

                $result[$full_criterio['categoria']]
                [$full_criterio['dimension']]
                [$full_criterio['indicador']]
                [$full_criterio['enunciado']]['criterios']
                [$full_criterio['criterio']]['cantidad']++;
        }

        return $result;
    }
}