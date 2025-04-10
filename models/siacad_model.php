<?php

include_once 'PGSQL_model.php';

/*
Este modelo se usa para consultar información del SIGEA
*/
class SiacadModel extends PGSQLModel
{
    public function __construct()
    {
        parent::SetInfo(
            '192.168.0.1', // server
            'ce',          // username
            'osobuco',     // password
            'siacad'       // database
        );
    }

    ///////////////////  G E T   D O C E N T E S  ///////////////////

    // Retorna un docente según su cédula
    public function GetDocenteByCedula($cedula)
    {
        $sql = "SELECT * FROM docentes WHERE cedula='$cedula'";
        return parent::GetRow($sql);
    }

    // Retorna un docente según su iddocente
    public function GetDocenteById($id)
    {
        $sql = "SELECT * FROM docentes WHERE iddocente=$id";
        return parent::GetRow($sql);
    }

    // Obtiene la cédula de un estudiante y retorna todos los profesores que le dan clase actualmente
    public function GetDocentesOfEstudiante($cedula){
        $id_periodo = $this->GetCurrentPeriodo()['idperiodo'];
        $sql = "SELECT DISTINCT ON (docentes.iddocente)
            docentes.nombres, 
            docentes.apellidos, 
            docentes.iddocente, 
            materias.nombremateria 
            FROM 
            docentes
            INNER JOIN secciones ON secciones.iddocente = docentes.iddocente
            INNER JOIN inscripciones ON inscripciones.idseccion = secciones.idseccion
            INNER JOIN matriculas ON matriculas.idmatricula = inscripciones.idmatricula
            INNER JOIN participantescarreras ON participantescarreras.idparticipantescarrera = matriculas.idparticipantescarrera
            INNER JOIN participantes ON participantes.cedula = participantescarreras.cedula
            INNER JOIN materiasperiodos ON materiasperiodos.idmateriaperiodo = secciones.idmateriaperiodo
            INNER JOIN materias ON materias.idmateria = materiasperiodos.idmateria
            WHERE
            participantes.cedula='$cedula' AND
            materiasperiodos.idperiodo = $id_periodo";
        
        return parent::GetRows($sql);
    }

    // Recibe la cédula de un coordinador y los ids de los docentes ya evaluados
    // Retorna una lista de los docentes que el coordinador aún no ha evaluado
    public function GetNotEvaluadosDocentesOfCoordinador($cedula_coordinador, $ids_evaluados)
    {
        $id_periodo = $this->GetCurrentPeriodo()['idperiodo'];
        $coordinador = $this->GetDocenteByCedula($cedula_coordinador);
        if ($coordinador === false) return false;

        $id_coordinador = $coordinador['iddocente'];        
        $coordinaciones = $this->GetCoordinacionesOfPersonal($id_coordinador, false, false);

        $sql = "SELECT 
            DISTINCT ON (docentescoordinaciones.iddocente)
            docentescoordinaciones.iddocente,
            docentes.nombres,
            docentes.apellidos
            FROM docentescoordinaciones
            INNER JOIN coordinaciones ON coordinaciones.idcoordinacion = docentescoordinaciones.idcoordinacion
            INNER JOIN docentes ON docentes.iddocente = docentescoordinaciones.iddocente
            INNER JOIN secciones ON secciones.iddocente = docentes.iddocente
            INNER JOIN materiasperiodos ON materiasperiodos.idmateriaperiodo = secciones.idmateriaperiodo
            WHERE
            coordinaciones.nombrecoordinacion IN ($coordinaciones) AND
            docentes.iddocente NOT IN ($ids_evaluados) AND
            materiasperiodos.idperiodo = $id_periodo
            ORDER BY 
            docentescoordinaciones.iddocente";

        return parent::GetRows($sql);
    }

    // Retorna los docentes de un estudiante excluyendo los ids que se reciben separados por comas
    public function GetDocentesOfStudentNotInList($cedula, $ordered_ids){
        $id_periodo = $this->GetCurrentPeriodo()['idperiodo'];
       
        $sql = "SELECT DISTINCT ON (docentes.iddocente)
            docentes.nombres, 
            docentes.apellidos, 
            docentes.iddocente, 
            materias.nombremateria 
            FROM 
            docentes
            INNER JOIN secciones ON secciones.iddocente = docentes.iddocente
            INNER JOIN inscripciones ON inscripciones.idseccion = secciones.idseccion
            INNER JOIN matriculas ON matriculas.idmatricula = inscripciones.idmatricula
            INNER JOIN participantescarreras ON participantescarreras.idparticipantescarrera = matriculas.idparticipantescarrera
            INNER JOIN participantes ON participantes.cedula = participantescarreras.cedula
            INNER JOIN materiasperiodos ON materiasperiodos.idmateriaperiodo = secciones.idmateriaperiodo
            INNER JOIN materias ON materias.idmateria = materiasperiodos.idmateria
            WHERE
            participantes.cedula='$cedula' AND
            materiasperiodos.idperiodo = $id_periodo AND
            docentes.iddocente NOT IN ($ordered_ids)";
        
        return parent::GetRows($sql);
    }

    // Retorna los coordinadores a evaluar por un docente
    public function GetCoordinadoresOfDocenteNotInList($cedula_docente, $ids_evaluados){
        include 'admin_model.php';
        $admin_model = new AdminModel();
        $cedulas_coordinadores = $admin_model->GetCoordinadores();
        $sql = "SELECT
            iddocente
            FROM
            docentes
            WHERE
            cedula IN ($cedulas_coordinadores)";

        // Obtenemos a todos los coordinadores registrados localmente
        $coordinadores = parent::GetRows($sql);
        
        $ids_coordinadores = '';
        if($coordinadores !== false){
            // Buscamos sus iddocente y lo guardamos en un string separado por comas
            foreach($coordinadores as $coordinador){
                $ids_coordinadores .= "'" . $coordinador['iddocente'] . "', ";
            }
            $ids_coordinadores = trim($ids_coordinadores, ', ');
        }
        $id_docente = $this->GetDocenteByCedula($cedula_docente)['iddocente'];
        $my_coordinaciones = $this->GetCoordinacionesOfPersonal($id_docente, false, false);

        // Obtenemos los coordinadores no evaluados de la coordinación del docente
        $sql = "SELECT 
            DISTINCT ON (docentescoordinaciones.iddocente)
            docentescoordinaciones.iddocente,
            docentes.nombres,
            docentes.apellidos
            FROM 
            docentescoordinaciones
            INNER JOIN coordinaciones ON coordinaciones.idcoordinacion = docentescoordinaciones.idcoordinacion
            INNER JOIN docentes ON docentes.iddocente = docentescoordinaciones.iddocente
            WHERE
            coordinaciones.nombrecoordinacion IN ($my_coordinaciones) AND
            docentes.iddocente NOT IN ($ids_evaluados) AND
            docentes.iddocente IN ($ids_coordinadores) 
            ORDER BY 
            docentescoordinaciones.iddocente";
        
        return parent::GetRows($sql);
    }

    // Obtiene el id de un evaluador y el id del que será evaluado
    // Retorna true si las coordinaciones de ambos coinciden
    public function CoordinacionMatch($id_evaluador, $id_evaluado){
        // Obtenemos las coordinaciones como array para compararlas
        $my_coordinaciones = $this->GetCoordinacionesOfPersonal($id_evaluador, true);
        $target_coordinaciones = $this->GetCoordinacionesOfPersonal($id_evaluado, true);
        $matchs = false;

        foreach($my_coordinaciones as $my_coordinacion){
            foreach($target_coordinaciones as $target_coordinacion){
                if($target_coordinacion === $my_coordinacion){
                    $matchs = true;
                    break;
                }
            }
        }
        return $matchs;
    }

    // Obtiene una lista de iddocente así: '5,43,77,135,251' y retorna esos docentes
    public function GetDocentesOfListOfIds($id_list){
        $sql = "SELECT
            iddocente,
            nombres,
            apellidos
            FROM docentes
            WHERE
            iddocente IN ($id_list)
            ORDER BY nombres";
        
        return parent::GetRows($sql);
    }

    // Obtiene el id de un docente y cédula de un estudiante
    // Retorna al docente si este le da clases, sino false
    public function DocenteTeachToStudent($id_docente, $cedula_estudiante){
        $id_periodo = $this->GetCurrentPeriodo()['idperiodo'];
       
        $sql = "SELECT
        docentes.nombres, 
        docentes.cedula,
        docentes.apellidos, 
        docentes.iddocente
        FROM 
        docentes
        INNER JOIN secciones ON secciones.iddocente = docentes.iddocente
        INNER JOIN inscripciones ON inscripciones.idseccion = secciones.idseccion
        INNER JOIN matriculas ON matriculas.idmatricula = inscripciones.idmatricula
        INNER JOIN participantescarreras ON participantescarreras.idparticipantescarrera = matriculas.idparticipantescarrera
        INNER JOIN participantes ON participantes.cedula = participantescarreras.cedula
        INNER JOIN materiasperiodos ON materiasperiodos.idmateriaperiodo = secciones.idmateriaperiodo
        WHERE
        participantes.cedula='$cedula_estudiante' AND
        materiasperiodos.idperiodo = $id_periodo AND
        docentes.iddocente = $id_docente";
        return parent::GetRow($sql);
    }

    public function GetdocentesOfCoordinacionesAndIdsList($coordinaciones, $ordered_ids){
        $id_periodo = $this->GetCurrentPeriodo()['idperiodo'];
        $sql = "SELECT DISTINCT 
            docentes.iddocente,
            docentes.nombres,
            docentes.apellidos
            FROM 
            docentescoordinaciones
            INNER JOIN coordinaciones ON coordinaciones.idcoordinacion = docentescoordinaciones.idcoordinacion
            INNER JOIN docentes ON docentes.iddocente = docentescoordinaciones.iddocente
            INNER JOIN secciones ON secciones.iddocente = docentes.iddocente
            INNER JOIN materiasperiodos ON materiasperiodos.idmateriaperiodo = secciones.idmateriaperiodo
            WHERE
            coordinaciones.nombrecoordinacion SIMILAR TO '$coordinaciones' AND
            docentescoordinaciones.iddocente IN ($ordered_ids) AND
            materiasperiodos.idperiodo = $id_periodo
            ORDER BY 
            docentes.iddocente";
        return parent::GetRows($sql);
    }

    public function GetActiveDocentes($id_periodo = null){
        if($id_periodo === null)
            $target_periodo = $this->GetCurrentPeriodo()['idperiodo'];
        else
            $target_periodo = $id_periodo;
        $sql = "SELECT DISTINCT ON (docentes.iddocente)
            docentes.iddocente
            FROM 
            docentes
            INNER JOIN secciones ON secciones.iddocente = docentes.iddocente
            INNER JOIN inscripciones ON inscripciones.idseccion = secciones.idseccion
            INNER JOIN matriculas ON matriculas.idmatricula = inscripciones.idmatricula
            INNER JOIN participantescarreras ON participantescarreras.idparticipantescarrera = matriculas.idparticipantescarrera
            INNER JOIN participantes ON participantes.cedula = participantescarreras.cedula
            INNER JOIN materiasperiodos ON materiasperiodos.idmateriaperiodo = secciones.idmateriaperiodo
            INNER JOIN materias ON materias.idmateria = materiasperiodos.idmateria
            WHERE
            materiasperiodos.idperiodo = $target_periodo";
        
        return parent::GetRows($sql);
    }

    ///////////////////  G E T   M A T E R I A S  ///////////////////

    // Retorna las materias que da el docente en el periodo actual según su cédula
    public function GetMateriasByCedulaDocente($cedula){
        $id_periodo = $this->GetCurrentPeriodo()['idperiodo'];; // Obteniendo el id del periodo actual
        $teacher = $this->GetDocenteByCedula($cedula);
        if($teacher === false) return false; // Retorna false si el profesor no existe

        $id_docente = $teacher['iddocente'];
        
        $sql = "SELECT DISTINCT
            materias.nombremateria
            FROM
            materias
            INNER JOIN materiasperiodos ON materiasperiodos.idmateria = materias.idmateria
            INNER JOIN secciones ON secciones.idmateriaperiodo = materiasperiodos.idmateriaperiodo
            INNER JOIN docentes ON docentes.iddocente = secciones.iddocente
            WHERE
            materiasperiodos.idperiodo= $id_periodo AND
            docentes.iddocente = $id_docente";

        return parent::GetRows($sql);
    }

    // Retorna las materias que ve el estudiante de ese docente
    public function GetMateriasOfDocenteAndEstudiante($cedula_estudiante, $cedula_docente){
        $id_periodo = $this->GetCurrentPeriodo()['idperiodo'];
        $sql = "SELECT
            materias.nombremateria
            FROM 
            materias
            INNER JOIN materiasperiodos ON materiasperiodos.idmateria = materias.idmateria
            INNER JOIN secciones ON secciones.idmateriaperiodo = materiasperiodos.idmateriaperiodo
            INNER JOIN docentes ON docentes.iddocente = secciones.iddocente
            INNER JOIN inscripciones ON inscripciones.idseccion = secciones.idseccion
            INNER JOIN matriculas ON matriculas.idmatricula = inscripciones.idmatricula
            INNER JOIN participantescarreras ON participantescarreras.idparticipantescarrera = matriculas.idparticipantescarrera
            WHERE
            participantescarreras.cedula = $cedula_estudiante AND
            docentes.cedula = $cedula_docente AND
            materiasperiodos.idperiodo = $id_periodo";

        return parent::GetRows($sql);
    }


    ///////////////////  G E T   E S T U D I A N T E S  ///////////////////


    // Retorna un estudiante e información útil según su cédula
    public function GetEstudianteByCedula($cedula)
    {
        $sql = "SELECT
            participantes.cedula,
            participantes.nombre1,
            participantes.nombre2,
            participantes.apellido1,
            participantes.apellido2,
            carreras.nombrecarrera as carrera,
            secciones.nombreseccion as seccion,
            semestres.abreviado as semestre
            FROM 
            participantes
            INNER JOIN participantescarreras ON participantescarreras.cedula = participantes.cedula
            INNER JOIN matriculas ON matriculas.idparticipantescarrera = participantescarreras.idparticipantescarrera
            INNER JOIN inscripciones ON inscripciones.idmatricula = matriculas.idmatricula
            INNER JOIN secciones ON secciones.idseccion = inscripciones.idseccion
            INNER JOIN materiasperiodos ON materiasperiodos.idmateriaperiodo = secciones.idmateriaperiodo
            INNER JOIN materias ON materias.idmateria = materiasperiodos.idmateria
            INNER JOIN semestres ON semestres.idsemestre = materias.idsemestre
            INNER JOIN pensums ON pensums.idpensum = semestres.idpensum
            INNER JOIN carreras ON carreras.idcarrera = pensums.idcarrera
            WHERE
            participantes.cedula = $cedula";

        return parent::GetRow($sql);
    }


    // Retorna true si el estudiante está activo ahora mismo
    public function StudentIsActive($cedula){
        $id_periodo = $this->GetCurrentPeriodo()['idperiodo'];
        $sql = "SELECT
            participantes.nombre1
            FROM 
            participantes
            INNER JOIN participantescarreras ON participantescarreras.cedula = participantes.cedula
            INNER JOIN matriculas ON matriculas.idparticipantescarrera = participantescarreras.idparticipantescarrera
            INNER JOIN inscripciones ON inscripciones.idmatricula = matriculas.idmatricula
            INNER JOIN secciones ON secciones.idseccion = inscripciones.idseccion
            INNER JOIN materiasperiodos ON materiasperiodos.idmateriaperiodo = secciones.idmateriaperiodo
            WHERE
            matriculas.matriculado = 'TRUE' AND
            inscripciones.inscrita = 'TRUE' AND
            materiasperiodos.idperiodo = $id_periodo AND
            participantes.cedula = '$cedula'";

        
        return parent::GetRows($sql);
    }

    public function GetActiveStudents($id_periodo = null){
        if($id_periodo === null)
            $target_periodo = $this->GetCurrentPeriodo()['idperiodo'];
        else
            $target_periodo = $id_periodo;
            
        $sql = "SELECT DISTINCT
            participantes.cedula
            FROM 
            participantes
            INNER JOIN participantescarreras ON participantescarreras.cedula = participantes.cedula
            INNER JOIN matriculas ON matriculas.idparticipantescarrera = participantescarreras.idparticipantescarrera
            INNER JOIN inscripciones ON inscripciones.idmatricula = matriculas.idmatricula
            INNER JOIN secciones ON secciones.idseccion = inscripciones.idseccion
            INNER JOIN materiasperiodos ON materiasperiodos.idmateriaperiodo = secciones.idmateriaperiodo
            WHERE
            matriculas.matriculado = TRUE AND
            inscripciones.inscrita = TRUE AND
            materiasperiodos.idperiodo = $target_periodo";
        return parent::GetRows($sql);
    }


    // Obtiene un array así ['cedula' => '21512', 'corte' => 'I'], ...
    public function GetEstudiantesOfCedulaListByCarrera($participants, $idperiodo = null){
        $oredered_participants = [];
        foreach($participants as $participant) {
            if(!isset($oredered_participants[$participant['corte']]))
                $oredered_participants[$participant['corte']] = '';

                $oredered_participants[$participant['corte']] .= "'" . $participant['cedula'] . "', ";
        }
        

        $result = [];
        $target_periodo = $idperiodo === null ? $this->GetCurrentPeriodo()['idperiodo'] : $idperiodo;
        foreach($oredered_participants as $corte => $cedulas){
            if(!isset($result[$corte]))
                $result[$corte] = [];
            $oredered_cedulas = trim($cedulas, ', ');
            $sql = "SELECT 
                carreras.nombrecarrera as carrera,
                COUNT(participantes.cedula) as cantidad
                FROM 
                participantes 
                INNER JOIN participantescarreras ON participantescarreras.cedula = participantes.cedula 
                INNER JOIN pensums ON pensums.idpensum = participantescarreras.idpensum 
                INNER JOIN matriculas ON matriculas.idparticipantescarrera = participantescarreras.idparticipantescarrera
                INNER JOIN carreras ON carreras.idcarrera = pensums.idcarrera 
                WHERE 
                participantes.cedula IN ($oredered_cedulas) AND 
                matriculas.idperiodo = $target_periodo
                GROUP BY
                carreras.idcarrera
                ORDER BY
                carreras.nombrecarrera";

            $carreers = parent::GetRows($sql);
            
            foreach($carreers as $career){
                $tentative_name = $career['carrera'];
                if(strpos($tentative_name, 'Educación') !== false)
                    $tentative_name = 'Educación';
                else if(strpos($tentative_name, 'Administración') !== false)
                    $tentative_name = 'Administración';
                else if(strpos($tentative_name, 'Contaduría') !== false)
                    $tentative_name = 'Contaduría';

                if(isset($result[$corte][$tentative_name]))
                    $result[$corte][$tentative_name] += $career['cantidad'];
                else 
                    $result[$corte][$tentative_name] = $career['cantidad'];
            }
        }
        if(!isset($result['I'])) $result['I'] = [];
        if(!isset($result['II'])) $result['II'] = [];
        if(!isset($result['III'])) $result['III'] = [];
        
        return $result;
    }

    ///////////////////  M É T O D O S   V A R I O S  ///////////////////

    // Retorna el id del periodo académico en curso
    public function GetCurrentPeriodo(){
        $sql = "SELECT * FROM periodos WHERE actual='TRUE'";
        return parent::GetRow($sql);
    }

    public function GetPeriodoById($id){
        $sql = "SELECT * FROM periodos WHERE idperiodo=$id";
        return parent::GetRow($sql);
    }

    public function GetPeriodosByListOfIds($ids){
        $sql = "SELECT * FROM periodos WHERE idperiodo IN ($ids)";
        return parent::GetRows($sql);
    }

    // Retornamos los cortes con sus fechas de inicio y fin
    public function GetCortesDates(){
        $id_periodo = $this->GetCurrentPeriodo()['idperiodo'];
        
        $sql = "SELECT * FROM periodoseventos WHERE 
        idperiodo=$id_periodo AND
        (idevento=3 OR -- Corte 1
        idevento=4 OR -- Corte 2
        idevento=5) -- Corte 3
        ORDER BY fechainicio";

        $cortes = parent::GetRows($sql);
        $result = [];
        $count = 1;
        foreach($cortes as $corte) {
            $long_corte = 'corte_' . $count;
            $to_add = array(
                'inicio' => $corte['fechainicio'],
                'fin' => $corte['fechafin']
            );
            $result[$long_corte] = $to_add;
            $count ++;
        }
        return $result;
    }

    // Retorna el corte en el que estamos
    public function GetCurrentCorte(){
        $cortes = $this->GetCortesDates();
        $today = date('Y-m-d');
        $current = '';
        foreach ($cortes as $corte_name => $corte) {
            if($today >= $corte['inicio'] && $today <= $corte['fin']){
                $current = $corte_name;
                break;
            }
        }
        // Si hoy se pasa de la fecha del fin del tercer corte
        // Entonces estamos más allá del corte 3
        if($today >= $cortes['corte_3']['fin']) $current = 'corte_4';

        if($current === 'corte_1') $current = 'I';
        else if($current === 'corte_2') $current = 'II';
        else if($current === 'corte_3') $current = 'III';
        else if ($current === 'corte_4') $current = 'IV';
        else $current = '';
        return $current;
    }

    // Retorna el corte en el que se va a evaluar, restándole 1 al corte actual
    public function GetCorteToVote(){
        $current_corte = $this->GetCurrentCorte();
        if($current_corte === 'II') $current_corte = 'I';
        else if ($current_corte === 'III') $current_corte = 'II';
        else if ($current_corte === 'IV') $current_corte = 'III';
        else $current_corte = 'no permitido';
        return $current_corte;
    }

    // Obtiene el iddocente de un coordinador o docente
    // Si $as_list = true retorna las coordinaciones como array, sino como string separado por comas
    // Si $fix_coordinacion = false retorna las coordinaciones con el nombre registrado en el sigea
    public function GetCoordinacionesOfPersonal($id_personal, $as_list = false, $fix_coordinacion = true){
        $sql = "SELECT
        coordinaciones.nombrecoordinacion
        FROM coordinaciones
        INNER JOIN docentescoordinaciones ON docentescoordinaciones.idcoordinacion = coordinaciones.idcoordinacion
        WHERE
        docentescoordinaciones.iddocente = $id_personal
        ORDER BY 
        docentescoordinaciones.iddocentecoordinacion";        
        $coordinaciones = parent::GetRows($sql);
        if($as_list === true) {
            if($coordinaciones === false) return [];
            return $coordinaciones;
        }

        if($coordinaciones === false) return false;
        $coordinaciones_string = '';

        foreach($coordinaciones as $coordinacion){
            $current_coordinacion = $coordinacion['nombrecoordinacion'];
            if($fix_coordinacion === true){
                // Las siguientes coordinaciones dan problemas así que aquí las acomodamos
                if($current_coordinacion === 'Educación nueva') $current_coordinacion = 'Educación';
                if($current_coordinacion === 'Administración y Contaduría') $current_coordinacion = 'Administración';
                if($current_coordinacion === 'Electrónica y electrotecnia') $current_coordinacion = "Electrónica";
            }

            $coordinaciones_string .= "'" . $current_coordinacion . "', ";
        }
        return trim($coordinaciones_string, ', ');
    }

    // Obtiene una cédula y retorna el tipo de usuario junto a su registro
    public function GetUserTypeByCedula($cedula){
        $data = [];
        $student = $this->GetEstudianteByCedula($cedula);
        if($student !== false){
            $data = array(
                'type' => 'Estudiante',
                'data' => $student
            );
        }        

        include_once 'admin_model.php';
        $admin_model = new AdminModel();
        $admin = $admin_model->CheckAdmin($cedula);
        if($admin !== false){
            $data = array(
                'type' => $admin['type'],
                'data' => $admin,
            );
        }
        return $data;
    }

    public function GetEvaluacionesOfDocenteBySeccionByCedulasEstudiantes($cedulas, $idperiodo, $iddocente){
        $sql = "SELECT 
        semestres.abreviado as semestre,
        secciones.idseccion as id_seccion,
        secciones.nombreseccion as seccion,
        carreras.carreraabreviada as carrera,
        COUNT(participantes.cedula) as cantidad
        FROM 
        participantes
        INNER JOIN participantescarreras ON participantescarreras.cedula = participantes.cedula
        INNER JOIN matriculas ON matriculas.idparticipantescarrera = participantescarreras.idparticipantescarrera
        INNER JOIN inscripciones ON inscripciones.idmatricula = matriculas.idmatricula
        INNER JOIN secciones ON secciones.idseccion = inscripciones.idseccion
        INNER JOIN materiasperiodos ON materiasperiodos.idmateriaperiodo = secciones.idmateriaperiodo
        INNER JOIN materias ON materias.idmateria = materiasperiodos.idmateria
        INNER JOIN semestres ON semestres.idsemestre = materias.idsemestre
        INNER JOIN pensums ON pensums.idpensum = semestres.idpensum
        INNER JOIN carreras ON carreras.idcarrera = pensums.idcarrera
        WHERE
        participantes.cedula IN ($cedulas) AND
        materiasperiodos.idperiodo = $idperiodo AND
        secciones.iddocente  = $iddocente
        GROUP BY
        secciones.idseccion,
        semestres.idsemestre,
        carreras.idcarrera
        ORDER BY
        semestres.abreviado,
        secciones.nombreseccion,
        carreras.carreraabreviada";

        return parent::GetRows($sql);
    }

    public function GetEstudiantesOfSeccion($id_seccion){
        $sql = "SELECT 
        participantes.cedula
        FROM 
        participantes
        INNER JOIN participantescarreras ON participantescarreras.cedula = participantes.cedula
        INNER JOIN matriculas ON matriculas.idparticipantescarrera = participantescarreras.idparticipantescarrera
        INNER JOIN inscripciones ON inscripciones.idmatricula = matriculas.idmatricula
        INNER JOIN secciones ON secciones.idseccion = inscripciones.idseccion
        INNER JOIN materiasperiodos ON materiasperiodos.idmateriaperiodo = secciones.idmateriaperiodo
        WHERE
        secciones.idseccion  = $id_seccion";

        return parent::GetRows($sql);
    }

    public function GetSectionName($id_seccion){
        $sql = "SELECT 
        (semestres.abreviado || ' ' || secciones.nombreseccion || ' ' || carreras.carreraabreviada) as name
        FROM 
        secciones
        INNER JOIN materiasperiodos ON materiasperiodos.idmateriaperiodo = secciones.idmateriaperiodo
        INNER JOIN materias ON materias.idmateria = materiasperiodos.idmateria
        INNER JOIN semestres ON semestres.idsemestre = materias.idsemestre
        INNER JOIN pensums ON pensums.idpensum = semestres.idpensum
        INNER JOIN carreras ON carreras.idcarrera = pensums.idcarrera
        WHERE
        secciones.idseccion = $id_seccion";

        $result = parent::GetRow($sql);
        if($result === false) return 'No identificada';
        return $result['name'];
    }
}