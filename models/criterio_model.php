<?php

include_once 'PGSQL_model.php';

class CriterioModel extends PGSQLModel
{
    ////////////////////////   M A N I P U L A C I Ó N    D E   C R I T E R I O S   ////////////////////////
    public function AddCategoria($categoria_name){
        $sql = "INSERT INTO categoria (nombre) VALUES ('$categoria_name')";
        parent::DoQuery($sql);
        $new_categoria = parent::GetRow("SELECT * FROM categoria WHERE nombre='$categoria_name'");
        $result = '';
        if($new_categoria === false)
            $result = 'Ocurrió un error al insertar la categoría en la base de datos';
        else
            $result = true;
        
        return $result;
    }

    public function AddDimension($dimension_name, $categoria_id){
        $sql = "INSERT INTO dimension (nombre, categoria) VALUES ('$dimension_name', $categoria_id)";
        parent::DoQuery($sql);
        $new_dimension = parent::GetRow("SELECT * FROM dimension WHERE nombre='$dimension_name' AND categoria = $categoria_id");
        $result = '';
        if($new_dimension === false)
            $result = 'Ocurrió un error al insertar la dimensión en la base de datos';
        else
            $result = true;
        
        return $result;
    }

    public function AddIndicador($categoria_name, $dimension_id, $type){
        $sql = "INSERT INTO indicador (nombre, dimension, tipo) VALUES ('$categoria_name', $dimension_id, '$type')";
        parent::DoQuery($sql);
        $new_dimension = parent::GetRow("SELECT * FROM indicador WHERE nombre='$categoria_name' AND dimension = $dimension_id AND tipo = '$type'");
        $result = '';
        if($new_dimension === false)
            $result = 'Ocurrió un error al insertar el indicador en la base de datos';
        else
            $result = true;
        
        return $result;
    }

    public function AddEnunciado($data){
        $nombre = $data['nombre'];
        $indicador = $data['indicador'];
        $corte = $data['corte'];
        $active = $data['active'];
        $multi_select = $data['multi_select'];
        $grupo_criterio = $data['grupo_criterio'];
        $sql = "INSERT INTO enunciado
            (nombre, indicador, corte, activo, checkbox, grupo_criterio)
            VALUES
            ('$nombre', $indicador, '$corte', $active, $multi_select, $grupo_criterio)";

        parent::DoQuery($sql);

        $result = '';
        $sql = "SELECT * 
            FROM
            enunciado
            WHERE
            nombre = '$nombre' AND
            indicador = $indicador AND
            corte = '$corte' AND
            activo = $active AND
            checkbox = $multi_select AND
            grupo_criterio = $grupo_criterio";
        $new_enunciado = parent::GetRow($sql);

        if($new_enunciado === false)
            $result = "Ocurrió un error al insertar el enunciado en la base de datos";
        else
            $result = true;
        return $result;
    }

    public function AddGrupoCriterio($name){
        $sql = "INSERT INTO grupo_criterio (nombre) VALUES ('$name')";
        parent::DoQuery($sql);
        $new_grupo_criterio = parent::GetRow("SELECT * FROM grupo_criterio WHERE nombre='$name'");
        $result = '';
        if($new_grupo_criterio === false)
            $result = 'Ocurrió un error al insertar el grupo criterio en la base de datos';
        else
            $result = true;
        
        return $result;
    }

    public function AddCriterio($data){
        $name = $data['name'];
        $weight = $data['weight'];
        $grupo_criterio = $data['grupo_criterio'];
        $sql = "INSERT INTO criterio 
            (nombre, peso, grupo_criterio)
            VALUES
            ('$name', $weight, $grupo_criterio)";
        parent::DoQuery($sql);

        $sql = "SELECT *
            FROM
            criterio
            WHERE
            nombre = '$name' AND
            peso = $weight AND
            grupo_criterio = $grupo_criterio";

        $new_criterio = parent::GetRow($sql);
        $result = '';
        if($new_criterio === false)
            $result = 'Ocurrió un error al insertar el criterio en la base de datos';
        else
            $result = true;
        return $result;

    }

    public function UpdateCategoria($id, $categoria_name){
        $sql = "UPDATE categoria SET nombre = '$categoria_name' WHERE id = $id";
        parent::DoQuery($sql);
        $updated_categoria = parent::GetRow("SELECT * FROM categoria WHERE id='$id'");
        $result = '';

        if($updated_categoria === false)
            $result = 'Ocurrió un error al actualizar la categoría en la base de datos';
        else{
            if($updated_categoria['nombre'] !== $categoria_name)
                $result = 'Ocurrió un error al actualizar la categoría en la base de datos';
            else
                $result = true;
        }
        return $result;
    }

    public function UpdateDimension($id, $dimension_name, $categoria_id){
        $sql = "UPDATE dimension SET nombre = '$dimension_name', categoria = $categoria_id WHERE id = $id";
        parent::DoQuery($sql);
        $updated_dimension = parent::GetRow("SELECT * FROM dimension WHERE id='$id'");
        $result = '';

        if($updated_dimension === false)
            $result = 'Ocurrió un error al actualizar la dimensión en la base de datos';
        else{
            if(
                $updated_dimension['nombre'] !== $dimension_name ||
                $updated_dimension['categoria'] !== $categoria_id
            )
                $result = 'Ocurrió un error al actualizar la dimensión en la base de datos';
            else
                $result = true;
        }
        return $result;
    }

    public function UpdateIndicador($id, $indicador_name, $dimension, $type){
        $sql = "UPDATE indicador 
            SET 
            nombre = '$indicador_name',
            dimension = $dimension,
            tipo = '$type'
            WHERE id = $id";
        parent::DoQuery($sql);
        $updated_indicador = parent::GetRow("SELECT * FROM indicador WHERE id='$id'");
        $result = '';

        if($updated_indicador === false)
            $result = 'Ocurrió un error al actualizar el indicador en la base de datos';
        else{
            if(
                $updated_indicador['nombre'] !== $indicador_name ||
                $updated_indicador['dimension'] !== $dimension
            )
                $result = 'Ocurrió un error al actualizar el indicador en la base de datos';
            else
                $result = true;
        }
        return $result;
    }

    public function UpdateEnunciado($data){
        $id = $data['id'];
        $nombre = $data['nombre'];
        $indicador = $data['indicador'];
        $corte = $data['corte'];
        $active = $data['active'];
        $multi_select = $data['multi_select'];
        $grupo_criterio = $data['grupo_criterio'];
    

        $sql = "UPDATE enunciado SET
            nombre = '$nombre',
            indicador = $indicador, ";

        if($corte !== "''")
            $sql .= "corte = '$corte', ";
        else
            $sql .= "corte = $corte, ";
        
        $sql .= "activo = $active, 
        checkbox = $multi_select, 
        grupo_criterio = $grupo_criterio
        WHERE
        id = $id";
        
        parent::DoQuery($sql);

        $result = '';
        $sql = "SELECT * 
            FROM
            enunciado
            WHERE
            id = $id";

        $active = $active === 'true' ? true : false;
        $multi_select = $multi_select === 'true' ? true : false;
        $new_enunciado = parent::GetRow($sql);
        $corte = $corte === "''" ? '' : $corte;

        if(
            $new_enunciado['nombre'] !== $nombre ||
            $new_enunciado['indicador'] !== $indicador ||
            $new_enunciado['corte'] !== $corte ||
            $new_enunciado['activo'] !== $active ||
            $new_enunciado['checkbox'] !== $multi_select ||
            $new_enunciado['grupo_criterio'] !== $grupo_criterio
        ){
            $result = "Ocurrió un error al actualizar el enunciado en la base de datos";
        }
        else
            $result = true;
        return $result;
    }

    public function UpdateGrupoCriterio($id, $name){
        $sql = "UPDATE grupo_criterio SET nombre = '$name' WHERE id = $id";
        parent::DoQuery($sql);
        $updated_grupo_criterio = parent::GetRow("SELECT * FROM grupo_criterio WHERE id='$id'");
        $result = '';

        if($updated_grupo_criterio === false)
            $result = 'Ocurrió un error al actualizar el grupo criterio en la base de datos';
        else{
            if($updated_grupo_criterio['nombre'] !== $name)
                $result = 'Ocurrió un error al actualizar el grupo criterio en la base de datos';
            else
                $result = true;
        }
        return $result;
    }

    public function UpdateCriterio($data, $id){
        $name = $data['name'];
        $weight = $data['weight'];
        $grupo_criterio = $data['grupo_criterio'];

        $sql = "UPDATE criterio
            SET
            nombre = '$name',
            peso = $weight,
            grupo_criterio = $grupo_criterio
            WHERE
            id = $id";
        
        parent::DoQuery($sql);

        $sql = "SELECT *
            FROM
            criterio
            WHERE
            nombre = '$name' AND
            peso = $weight AND
            grupo_criterio = $grupo_criterio";

        $new_criterio = parent::GetRow($sql);
        $result = '';
        if($new_criterio === false)
            $result = 'Ocurrió un error al actualizar el criterio en la base de datos';
        else
            $result = true;
        return $result;

    }

    public function GetCategorias(){
        return parent::GetRows("SELECT * FROM categoria");
    }

    public function GetDimensiones(){
        return parent::GetRows("SELECT * FROM dimension");
    }

    public function GetIndicadores(){
        return parent::GetRows("SELECT * FROM indicador");
    }

    public function GetEnunciados(){
        return parent::GetRows("SELECT * FROM enunciado");
    }

    public function GetGruposCriterios(){
        return parent::GetRows("SELECT * FROM grupo_criterio");
    }

    public function GetCategoriaById($id){
        return parent::GetRow("SELECT * FROM categoria WHERE id=$id");
    }

    public function GetDimensionById($id){
        $sql = "SELECT
            dimension.id as id_dimension,
            dimension.nombre as nombre,
            categoria.id as id_categoria,
            categoria.nombre as categoria
            FROM 
            dimension
            INNER JOIN categoria ON categoria.id = dimension.categoria
            WHERE 
            dimension.id=$id";
        return parent::GetRow($sql);
    }

    public function GetIndicadorById($id){
        $sql = "SELECT
            indicador.id as id_indicador,
            indicador.nombre as nombre,
            dimension.id as dimension,
            indicador.tipo as tipo
            FROM
            indicador
            INNER JOIN dimension ON dimension.id = indicador.dimension
            WHERE
            indicador.id = $id";
        return parent::GetRow($sql);
    }

    public function GetEnunciadoById($id){
        return parent::GetRow("SELECT * FROM enunciado WHERE id=$id");
    }

    public function GetGrupoCriterioById($id){
        return parent::GetRow("SELECT * FROM grupo_criterio WHERE id =$id");
    }

    public function GetCriterioById($id){
        return parent::GetRow("SELECT * FROM criterio WHERE id = $id");
    }

    // Retorna todos los enunciados con los ids de sus criterios
    public function GetFullEnunciados(){
        $sql = "SELECT
            criterio.id as id_criterio,
            enunciado.id as id_enunciado
            FROM
            enunciado
            INNER JOIN grupo_criterio ON grupo_criterio.id = enunciado.grupo_criterio
            INNER JOIN criterio ON criterio.grupo_criterio = grupo_criterio.id
            ORDER BY 
            enunciado.id,
            criterio.id";

        return parent::GetRows($sql);
    }
    
    // Retorna los criterios de un 'coord' o 'teacher'
    public function GetCategoriasOf($tipo = true)
    {
        include_once 'siacad_model.php';
        $siacad = new SiacadModel();
        $current_corte = $siacad->GetCorteToVote();
        $result = [];
        if($tipo === 'student'){
            $sql = "SELECT 
            categoria.id as idcategoria,
            categoria.nombre as categoria,
            dimension.id as iddimension,
            dimension.nombre as dimension,
            indicador.id as idindicador,
            indicador.nombre as indicador,
            enunciado.id as idenunciado,
            enunciado.nombre as enunciado,
            enunciado.checkbox as checkbox,
            criterio.id as idcriterio,
            criterio.nombre as criterio,
            criterio.peso as peso
            FROM categoria
            INNER JOIN dimension ON dimension.categoria = categoria.id
            INNER JOIN indicador ON indicador.dimension = dimension.id
            INNER JOIN enunciado ON enunciado.indicador = indicador.id
            INNER JOIN grupo_criterio ON grupo_criterio.id = enunciado.grupo_criterio
            INNER JOIN criterio ON criterio.grupo_criterio = grupo_criterio.id
            WHERE
            enunciado.activo = true AND
            enunciado.corte = '$current_corte'
            ORDER BY
            categoria.id, dimension.id, indicador.id, enunciado.id, criterio.id";
        
            $rows = parent::GetRows($sql);
            if($rows === false) return false;

            $last_categoria = '';
            $last_dimension = '';
            $last_indicador = '';
            $last_enunciado = '';
            foreach ($rows as $row) {
                if($last_categoria != $row['categoria']){
                    // Si es una categoría distinta a la anterior
                    $result[$row['categoria']]['id'] = $row['idcategoria'];
                    $last_categoria = $row['categoria'];
                }

                if ($last_dimension != $row['dimension']) {
                    // Si es una dimensión distinta a la anterior
                    $result[$row['categoria']]['dimensiones'][$row['dimension']]['id'] = $row['iddimension']; // guardamos su id
                    $result[$row['categoria']]['dimensiones'][$row['dimension']]['height'] = $this->GetCriteriosOf('dimension', $row['iddimension'], $current_corte); // guardamos el número de criterios
                    $last_dimension = $row['dimension'];
                }

                if ($last_indicador != $row['indicador']) {
                    // Si es un indicador distinto al anterior
                    $result[$row['categoria']]['dimensiones'][$row['dimension']]
                        ['indicadores'][$row['indicador']]['id'] = $row['idindicador']; // guardamos su id
                    $result[$row['categoria']]['dimensiones'][$row['dimension']]
                        ['indicadores'][$row['indicador']]['height'] = $this->GetCriteriosOf('indicador', $row['idindicador'], $current_corte); // guardamos el número de criterios
                    $last_indicador = $row['indicador'];
                }

                if ($last_enunciado != $row['enunciado']) {
                    // Si es un enunciado distinto al anterior
                    $result[$row['categoria']]['dimensiones'][$row['dimension']]
                        ['indicadores'][$row['indicador']]['enunciados'][$row['enunciado']]['id'] = $row['idenunciado']; // guardamos su id
                    $result[$row['categoria']]['dimensiones'][$row['dimension']]
                        ['indicadores'][$row['indicador']]['enunciados'][$row['enunciado']]['checkbox'] = $row['checkbox']; // guardamos si es checkbox
                    $result[$row['categoria']]['dimensiones'][$row['dimension']]
                    ['indicadores'][$row['indicador']]['enunciados'][$row['enunciado']]['height'] = $this->GetCriteriosOf('enunciado', $row['idenunciado'], $current_corte); // guardamos el número de criterios
                    $last_enunciado = $row['enunciado'];
                }

                $result[$row['categoria']]
                        ['dimensiones'][$row['dimension']]
                        ['indicadores'][$row['indicador']]
                        ['enunciados'][$row['enunciado']]
                        ['criterios'][$row['criterio']] = array(
                    'id' => $row['idcriterio']
                        );

            }
        }
        else{
            $sql = "SELECT 
            indicador.nombre as indicador, 
            indicador.id as id_indicador, 
            enunciado.nombre as enunciado, 
            enunciado.id as id_enunciado,
            criterio.nombre as criterio, 
            criterio.id as id_criterio 
            FROM indicador 
            INNER JOIN enunciado ON enunciado.indicador = indicador.id 
            INNER JOIN grupo_criterio ON grupo_criterio.id = enunciado.grupo_criterio 
            INNER JOIN criterio ON criterio.grupo_criterio = grupo_criterio.id 
            WHERE 
            indicador.tipo = '$tipo' AND
            enunciado.activo = true
            ORDER BY
            indicador.id,
            enunciado.id,
            criterio.id";
    
            $rows = parent::GetRows($sql);
            
            foreach($rows as $row){
                if(!isset($result[$row['indicador']]))
                    $result[$row['indicador']] = array('id' => $row['id_indicador']);
    
                if(!isset($result[$row['indicador']][$row['enunciado']]))
                    $result[$row['indicador']][$row['enunciado']] = array();
    
                // Lo guardamos para que sea id_indicador-id_criterio y ese será el nombre del radio input del form
                $result[$row['indicador']][$row['enunciado']]['id'] = $row['id_enunciado'];
                $result[$row['indicador']][$row['enunciado']]['criterios'][$row['criterio']] = array(
                    'id_indicador' => $row['id_indicador'],
                    'id_criterio' => $row['id_criterio'],
                    'id_enunciado' => $row['id_enunciado']
                );
            }
        }
        return $result;
    }

    // Recibe una lista de 'id_enunciado' => 'id_criterio1, id_criterio2'
    // Retorna los criterios si el número coincide, sino false
    public function CheckCriteriosExists($enunciados){
        $allOK = true;
        $real_criterios = [];
        $recieved_criterios_number = 0;
        foreach($enunciados as $enunciado_id => $criterios){
            $sql = " SELECT
                criterio.id,
                criterio.peso
                FROM
                criterio
                INNER JOIN grupo_criterio ON grupo_criterio.id = criterio.grupo_criterio
                INNER JOIN enunciado ON enunciado.grupo_criterio = grupo_criterio.id
                WHERE
                criterio.id in ($criterios) AND
                enunciado.id = $enunciado_id";
            $obtained_criterios = parent::GetRows($sql);
            if($criterios === false) {
                $allOK = false;
                break;
            }
            else{
                $recieved_criterios_number += substr_count($criterios, ',') + 1; // El número de criterios que llegaron
                $real_criterios = array_merge($real_criterios, $obtained_criterios); // Los criterios que realmente existen
            }   
        }

        if(count($real_criterios) === $recieved_criterios_number && $allOK === true) 
            $result = $real_criterios;
        else
            $result = false;
        return $result;
    }

    // Obtiene una lista de criterios y retorna sus máximos puntos posibles
    public function GetMaxPossibleOfTheseEnunciados($enunciados_ids){
        $sql = "SELECT
            SUM(maximo) as total
            FROM
            (
            SELECT
            enunciado.id,
            SUM(criterio.peso) as maximo
            FROM
            criterio
            INNER JOIN grupo_criterio ON grupo_criterio.id = criterio.grupo_criterio
            INNER JOIN enunciado ON enunciado.grupo_criterio = grupo_criterio.id
            WHERE
            enunciado.checkbox = true AND
            enunciado.id IN ($enunciados_ids)
            GROUP BY enunciado.id
            ) 
            as not_checkbox_enunciados";
    
        $total = parent::GetRow($sql)['total'];

        $sql = "SELECT
            SUM(maximo) as total
            FROM
            (
            SELECT
            enunciado.id,
            MAX(criterio.peso) as maximo
            FROM
            criterio
            INNER JOIN grupo_criterio ON grupo_criterio.id = criterio.grupo_criterio
            INNER JOIN enunciado ON enunciado.grupo_criterio = grupo_criterio.id
            WHERE
            enunciado.checkbox = false AND
            enunciado.id IN ($enunciados_ids)
            GROUP BY 
            enunciado.id
            ) 
            as not_checkbox_enunciados";

        $total += parent::GetRow($sql)['total'];
        return $total;
    }

    // Recibe el nombre de una tabla y el id (categoria, dimension, indicador, enunciado)
    // Retorna el número de criterios totales
    public function GetCriteriosOf($table, $id, $current_corte = null){
        $sql = "SELECT COUNT(criterio.nombre) FROM criterio 
            INNER JOIN grupo_criterio ON grupo_criterio.id = criterio.grupo_criterio ";
        if ($table == 'enunciado') {
            $sql .= "INNER JOIN enunciado ON enunciado.grupo_criterio = grupo_criterio.id";
        } else if ($table == 'indicador') {
            $sql .= "INNER JOIN enunciado ON enunciado.grupo_criterio = grupo_criterio.id
                     INNER JOIN indicador ON indicador.id = enunciado.indicador";
        } else if ($table == 'dimension') {
            $sql .= "INNER JOIN enunciado ON enunciado.grupo_criterio = grupo_criterio.id
                     INNER JOIN indicador ON indicador.id = enunciado.indicador
                     INNER JOIN dimension ON dimension.id = indicador.dimension";
        } else if ($table == 'categoria') {
            $sql .= "INNER JOIN enunciado ON enunciado.grupo_criterio = grupo_criterio.id
                     INNER JOIN indicador ON indicador.id = enunciado.indicador
                     INNER JOIN dimension ON dimension.id = indicador.dimension
                     INNER JOIN categoria ON categoria.id = dimension.categoria";
        }

        $sql .= " WHERE $table.id=$id";
        if($current_corte !== null){
            $sql .= " AND enunciado.corte = '$current_corte'";
        }
        
        $result = parent::GetRow($sql);
        if ($result === false) return false;
        else return $result['count'];
    }

    // Obtiene un string 'categoria'/'dimension'/etc y retorna el nombre y id de este y el nombre de su elemento padre
    public function GetEvaluationElement($element){
        if ($element == 'criterio') {
            $sql = "SELECT
                criterio.id as id,
                criterio.nombre as nombre,
                grupo_criterio.nombre as father_element,
                criterio.peso as peso
                FROM
                criterio
                LEFT JOIN grupo_criterio ON grupo_criterio.id = criterio.grupo_criterio";
        }
        else if ($element == 'grupo_criterio') {
            $sql = "SELECT
                grupo_criterio.id as id,
                grupo_criterio.nombre
                FROM
                grupo_criterio";
        }
        else if ($element == 'enunciado') {
            $sql = "SELECT
                enunciado.id as id,
                enunciado.nombre as nombre,
                indicador.nombre as father_element,
                enunciado.corte as corte,
                grupo_criterio.nombre as grupo_criterio,
                enunciado.activo as activo
                FROM
                enunciado
                INNER JOIN indicador ON indicador.id = enunciado.indicador
                INNER JOIN grupo_criterio ON grupo_criterio.id = enunciado.grupo_criterio";
        } else if ($element == 'indicador') {
            $sql = "SELECT
                indicador.id as id,
                indicador.nombre as nombre,
                dimension.nombre as father_element,
                indicador.tipo as tipo
                FROM
                indicador
                LEFT JOIN dimension ON dimension.id = indicador.dimension";
        } else if ($element == 'dimension') {
            $sql = "SELECT
                dimension.id as id,
                dimension.nombre as nombre,
                categoria.nombre as father_element
                FROM
                dimension
                LEFT JOIN categoria ON categoria.id = dimension.categoria";
        } else if ($element == 'categoria') {
            $sql = "SELECT DISTINCT ON (categoria.id)
                categoria.id as id,
                categoria.nombre as nombre
                FROM
                categoria";
        }
        return parent::GetRows($sql);
    }
}