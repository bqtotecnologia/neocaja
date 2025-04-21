<?php
class Validator
{
    /**
     * Valida los campos recibidos de un formulario POST
     * 
     * @param array $fields_config Array ordenado con la configuración de los campos
     * @return array|string Un array con la información limpia o un string con un mensaje de error
     */
    public function ValidatePOSTFields(array $fields_config):array|string{
        $cleanData = [];
        $error = '';
        foreach($fields_config as $field => $currentField){
            
            if(!isset($_POST[$field])){
                $error = 'El campo ' . $field . ' no existe';
                break;
            }

            $recievedData = $_POST[$field];
    
            if($recievedData === '' && $currentField['required'] === true){
                $error = 'El campo ' . $field . ' no puede estar vacío';
                break;
            }
    
            if(strlen(strval($recievedData)) > $currentField['max']){
                $error = 'El campo ' . $field . ' supera el máximo de caracteres (' . $currentField['max'] . ')';
                break;
            }
    
            if(strlen(strval($recievedData)) < $currentField['min']){
                $error = 'El campo ' . $field . ' no alcanza el mínimo de caracteres (' . $currentField['min'] . ')';
                break;
            }

            if($currentField['suspicious']){
                if($this->HasSuspiciousCharacters($recievedData)){
                    $error = 'El campo ' . $field . ' contiene caracteres sospechosos: < > / \\ ; " { } [ ] $ & | ¿ ? ! = -   ';
                    break;
                }
            }

            if(in_array($currentField['type'], ['numeric', 'float'])){
                if(is_numeric($recievedData) === false){
                    $error = 'El campo ' . $field . ' debe ser numérico';
                    break;
                }                
            }

            if($currentField['type'] === 'date'){
                $timezone = new DateTimeZone('America/Caracas');
                try{
                    $recievedData = new DateTime($recievedData, $timezone);
                }
                catch(Exception $e){
                    $error = 'El campo ' . $field . ' debe ser una fecha válida';
                    break;
                }
            }

            if($currentField['type'] === 'numeric')
                $recievedData = intval($recievedData);

            if($currentField['type'] === 'float')
                $recievedData = floatval($recievedData);

            $cleanData[$field] = $recievedData;
        }

        if(isset($_POST['id'])){
            $id = $_POST['id'];
            if(is_numeric($id) === false){
                $error = 'El id recibido debe ser numérico';
                exit;
            }
            $cleanData['id'] = intval($id);
        }

        if($error === '')
            return $cleanData;
        else
            return $error;
    }

    /**
     * Retorna true si un string tiene caracteres sospechosos o false si no
     * 
     * Los caracteres que revisa son los siguientes < > / \\ ; " { } [ ] $ & | ¿ ? ! = -   
     * 
     * @param string $field el campo a revisar
     * @return bool true si es sospechoso, false si no
     */
    public function HasSuspiciousCharacters(string $field):bool{
        // < > / \\ ; " { } [ ] $ & | ¿ ? ! = -   
        $regex = '/[<>\-\/;"\'(){}\[\]$\\\|&\?\¿\¡!=]/u';
        return preg_match($regex, $field);
    }
}