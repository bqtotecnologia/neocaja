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
            '',    // server
            '',    // username
            '',    // password
            ''     // database
        );
    }

    // Retorna el id del periodo académico en curso
    public function GetCurrentPeriodo(){
        $sql = "SELECT * FROM periodos WHERE actual='TRUE'";
        return parent::GetRow($sql);
    }

    public function GetPeriodoById($id){
        $sql = "SELECT * FROM periodos WHERE idperiodo=$id";
        return parent::GetRow($sql);
    }

    /**
     * Retorna los periodos según una lista de ids
     */
    public function GetPeriodsByIdList($idList){
        $ids = '';
        foreach($idList as $id){
            $ids .= $id . ', ';
        }

        $ids = trim($ids, ', ');
        $sql = "SELECT * FROM periodos WHERE idperiodo IN ($ids) ORDER BY fechainicio DESC";
        return parent::GetRows($sql);
    }

    /**
     * Retorna una lista con los números de los meses que conforman el periodo
     * Si recibe $names = true retorna la lista de meses pero con los nombres en vez de los números
     */
    public function GerMonthsOfPeriodo($id, $names = false){
        $periodMonths = [];
        $target_period = $this->GetPeriodoById($id);
        $timezone = new DateTimeZone('America/Caracas');
        $startDate = new DateTime($target_period['fechainicio'], $timezone);
        $endDate = new DateTime($target_period['fechafin'], $timezone);
        
        while($startDate < $endDate){
            $month = intval($startDate->format('m'));

            if($names === false)
                array_push($periodMonths, $month);
            else
                array_push($periodMonths, $this->month_translate[strval($month)]);
            
            $startDate->modify('+1 month');
        }

        return $periodMonths;
    }

    // Obtiene una cédula y retorna el tipo de usuario junto a su registro
    public function GetUserTypeByCedula($cedula){
        $data = [];
        $student = $this->GetEstudianteByCedula($cedula);
        if($student !== false){
            $data = array(
                'role' => 'Estudiante',
                'data' => $student
            );
        }        

        include_once 'admin_model.php';
        $admin_model = new AdminModel();
        $admin = $admin_model->CheckAdmin($cedula);
        if($admin !== false){
            $data = array(
                'role' => $admin['role'],
                'data' => $admin,
            );
        }
        return $data;
    }

    public function GetEstudianteByCedula($cedula)
    {
        $sql = "SELECT
            participantes.cedula,
            participantes.nombre1,
            participantes.nombre2,
            CONCAT(nombre1, ' ', nombre2) as nombres,
            CONCAT(apellido1, ' ', apellido2) as apellidos,
            participantes.apellido1,
            participantes.apellido2,
            participantes.direccion,
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

    /**
     * Recibe un string con las cédulas de la siguiente forma "'123', '456', '789'"
     */
    public function GetStudentsByCedulaList(string $cedulas){
        $sql = "SELECT
            participantes.cedula,
            CONCAT(nombre1, ' ', nombre2) as nombres,
            CONCAT(apellido1, ' ', apellido2) as apellidos,
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
            participantes.cedula in ($cedulas)
            ORDER BY
            participantes.apellidos,
            participantes.nombres";

        return parent::GetRows($sql, true);
    }
}