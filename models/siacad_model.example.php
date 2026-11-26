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

    
}