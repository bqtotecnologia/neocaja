<?php

include_once 'SQL_model.php';

class AbsolutionModel extends SQLModel
{    
    public $SELECT_ABSOLUTION = "SELECT
        accounts.id as account_id,
        accounts.cedula_letter,
        accounts.cedula,
        CONCAT(accounts.surnames, ' ', accounts.names) as fullname,
        absolutions.id,
        absolutions.period,
        absolutions.reason,
        absolutions.created_at
        FROM
        accounts
        INNER JOIN absolutions ON absolutions.account = accounts.id";

    public $SELECT_ABSOLUTION_DETAILS = "SELECT
        absolution_details.id,
        absolution_details.absolution,
        absolution_details.concept,
        absolution_details.created_at
        FROM
        absolution_details
        INNER JOIN absolutions ON absolutions.id = absolution_details.absolution";


    public function CreateAbsolution($data){
        $account = $data['account'];
        $period = $data['period'];
        $reason = $data['reason'];

        $sql = "INSERT INTO absolutions (account, period, reason) VALUES ($account, $period, '$reason')";
        return parent::DoQuery($sql);
    }

    public function GetAbsolutionById($id){
        $sql = $this->SELECT_ABSOLUTION . " WHERE absolutions.id = $id";
        return parent::GetRow($sql);
    }

    public function GetAbsolutions(){
        $sql = $this->SELECT_ABSOLUTION . " ORDER BY accounts.surnames, accounts.names";
        return parent::GetRows($sql, true);
    }

    // Retorna todos los clientes absueltos, ya sea de FOC o de mensualidades.
    public function GetAllAcquittedAccounts(){
        $absolutions = $this->GetAcquittedMonths();
        $acquitted_focs = $this->GetAcquittedMonths();

        $result = [];
        foreach($absolutions as $acquitted_month){
            if(!isset($result[$acquitted_month['cedula']])){
                $result[$acquitted_month['cedula']] = $acquitted_month;
                $result[$acquitted_month['cedula']]['acquitted']  = [];
            }

            $to_add = [
                'id' => $acquitted_month['id'],
                'concept' => $this->month_translate[$acquitted_month['month']]
            ];

            array_push($result[$acquitted_month['cedula']]['acquitted'], $to_add);
        }

        foreach($acquitted_focs as $acquitted_foc){
            if(!isset($result[$acquitted_foc['cedula']])){
                $result[$acquitted_foc['cedula']] = $acquitted_foc;
                $result[$acquitted_foc['cedula']]['acquitted']  = [];
            }

            $to_add = [
                'id' => $acquitted_foc['id'],
                'concept' => 'FOC'
            ];

            array_push($result[$acquitted_foc['cedula']]['acquitted'], $to_add);
        }

        return $result;
    }

    /**
     * Recibe un id de cuenta, concepto de absolvencia y periodo para retornar si el estudiante ya tiene absuelta dicho concepto
     */
    public function GetAbsolutionOfStudentOfPeriod($account, $period){
        $sql = $this->SELECT_ABSOLUTION . " WHERE accounts.id = $account AND absolutions.period = $period";
        return parent::GetRow($sql);
    }

    public function GetAcquittedMonths(){
        $sql = $this->SELECT_ABSOLUTION . " GROUP BY accounts.id, absolutions.id, absolutions.month ORDER BY accounts.surnames, accounts.names";
        return parent::GetRows($sql, true);
    }

    public function GetAcquittedFOCs(){
        $sql = $this->SELECT_ACQUITTED_FOC . " GROUP BY accounts.id, acquitted_focs.id ORDER BY accounts.surnames, accounts.names";
        return parent::GetRows($sql, true);
    }

    public function GetAbsolutionConcepts($id){
        $sql = $this->SELECT_ABSOLUTION_DETAILS . " WHERE absolutions.id = $id GROUP BY absolution_details.id";
        $details = parent::GetRows($sql, true);

        $result = [];
        foreach($details as $detail){
            array_push($result, $detail['concept']);
        }

        return $result;
    }

    public function UpdateAbsolution($id, $data){
        $account = $data['account'];
        $period = $data['period'];
        $reason = $data['reason'];

        $sql = "UPDATE absolutions SET 
            account = $account, 
            period = $period, 
            reason = '$reason'
            WHERE
            id = $id";

        return parent::DoQuery($sql);       
    }

    public function UpdateAbolutionConcepts($id, $concepts){
        $this->DeleteConceptsOfAbsolution($id);

        $sql = "INSERT INTO absolution_details (absolution, concept) VALUES ";
        foreach($concepts as $concept){
            $sql .= "($id, '$concept'),";
        }
        $sql = trim($sql, ',');
        var_dump($sql);
        return parent::DoQuery($sql);
    }

    public function DeleteConceptsOfAbsolution($id){
        $sql = "DELETE FROM absolution_details WHERE absolution = $id";
        return parent::DoQuery($sql);
    }
}