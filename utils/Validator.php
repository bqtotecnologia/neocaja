<?php
/**
 * Clase que contiene diversas validaciones al momento de chequear información recibida por formularios
 */
class Validator
{
    /**
     * Valida los campos recibidos de un formulario POST
     * 
     * Verifica que los campos númericos integer y float estén correctos, igual con las fechas, caracteres sospechosos y ids de elementos en las bases de datos
     * 
     * @param array $fields_config Array ordenado con la configuración de los campos
     * @return array|string Un array con la información limpia o un string con un mensaje de error
     */
    public static function ValidatePOSTFields(array $fields_config){
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
                if(Validator::HasSuspiciousCharacters($recievedData)){
                    $error = 'El campo ' . $field . ' contiene caracteres sospechosos: < > / \\ ; " { } [ ] $ & | ¿ ? ! = -   ';
                    break;
                }
            }

            if(in_array($currentField['type'], ['integer', 'float'])){
                if(is_numeric($recievedData) === false && $recievedData !== ''){
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

            if($currentField['type'] === 'integer')
                $recievedData = intval($recievedData);

            if($currentField['type'] === 'float')
                $recievedData = floatval($recievedData);

            $cleanData[$field] = $recievedData;
        }

        if(isset($_POST['id'])){
            $id = Validator::ValidateRecievedId('id', 'POST');
            if(is_string($id))
                $error = $id;
        
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
    public static function HasSuspiciousCharacters(string $field):bool{
        // < > / \\ ; " { } [ ] $ & | ¿ ? ! = -   
        $regex = '/[<>\-\/;"\'(){}\[\]$\\\|&\?\¿\¡!=]/u';
        return preg_match($regex, $field);
    }
    
    /**
     * Verifica si existe un valor 'id' en el método HTTP escogido
     * @param string $key_name El nombre del key que contiene el id a validar
     * @param string $method El nombre del método "GET"/"POST"
     * @return string|int
     */
    public static function ValidateRecievedId(string $key_name = 'id', string $method = 'GET'){
        $data = [
            'GET' => $_GET,
            'POST' => $_POST
        ];

        $result = true;
        if(empty($data[$method]))
            $result = "$method vacío";        

        if($result === true){
            if(!isset($data[$method][$key_name]))
                $result = "Id no recibido ($key_name)";
        }

        if($result === true){
            if(!is_numeric($data[$method][$key_name]))
                $result = "Id inválido ($key_name)";
        }

        if($result === true)
            $result = intval($data[$method][$key_name]);

        return $result;
    }

    /**
     * Recibe un número como string y retorna la cantidad de decimales que tiene
     * Si el número no es flotante retorna false
     */
    public static function GetDecimalCountOfFloat(string $number){
        $splits = explode('.', $number);
        if(count($splits) < 2)
            return false;

        return strlen($splits[1]);
    }

    /**
     * Checks if the coin API returns the appropiate values
     * @return string|array Retorna un string con el error o un array con la información recibida de la API
     */
    public static function ValidateCoinAPI($url){
        $error = '';
        try {
            $response = file_get_contents($url);
            $json = json_decode($response, true);
            if($json === NULL)
                $error = 'La API escrita no existe';
        } catch (Exception $e) {
            //var_dump($e);
            //exit;
            $error = 'Ocurrió un error al intentar consultar la API';
        } 

        if($error === ''){
            if(!isset($json['success']))
                $error = 'La API no retorna un valor "success"';
            else{
                if($json['success'] !== false && $json['success'] !== true)
                    $error = 'El valor success de la API no retorna un valor true o false';
                else{
                    $coinResponse = $json['success'];
                }
            }
        }

        if($error === ''){
            if(!isset($json['result']))
                $error = 'La API no retorna un valor "result"';
            else{
                if(!is_numeric($json['result'])){
                    if($coinResponse === true)
                        $error = 'El valor result arrojado por la API no es numérico';
                    else
                        $error = $json['result'];
                }
                else{
                    $coinValue = $json['result'];
                    $decimalCount = Validator::GetDecimalCountOfFloat($coinValue);
                    if($decimalCount !== 4)
                        $error = 'La parte decimal de la moneda debe tener 4 decimales. Valor obtenido: ' . $coinValue;
                }
            }
        }

        if($error === ''){
            if($coinResponse === false)
                $error = $json['result'];
        }

        if($error === '')
            $result = ['success' => $coinResponse, 'value' => $coinValue];
        else
            $result = $error;

        return $result;
    }
}