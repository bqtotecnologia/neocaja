<?php

include_once 'SQL_model.php';

class BinnacleModel extends SQLModel
{
    private $BINNACLE_TEMPLATE = "SELECT
        admins.cedula,
        admins.name as name,
        binnacle.created_at,
        binnacle.action
        FROM
        binnacle
        INNER JOIN admins ON admins.id = binnacle.user ";

    /**
     * Obtener la bitácora entre un rango de fechas "Y-m-d"
     */
    public function GetBinnacleByDateRange(string $start_date, string $end_date){
        $sql = $this->BINNACLE_TEMPLATE . "
            WHERE
            DATE(binnacle.created_at) BETWEEN '$start_date' AND '$end_date'
            ORDER BY
            binnacle.created_at DESC";

        return parent::GetRows($sql, true);
    }    

    /**
     * Obtiene la bitácora de un usuario específico según su id
     */
    public function GetBinnacleOfUser(string $user_id){
        $sql = $this->BINNACLE_TEMPLATE . "
            WHERE
            binnacle.user = $user_id
            ORDER BY
            binnacle.created_at DESC";

        return parent::GetRows($sql, true);
    }    
}