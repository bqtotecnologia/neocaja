<?php

use Dom\Notation;

include_once 'SQL_model.php';

class CoinModel extends SQLModel
{ 
    private $SELECT_TEMPLATE = "SELECT 
        coins.id,
        coins.name,
        coins.active,
        coins.created_at as coin_created_at,
        coins.auto_update,
        coins.url,
        ch.id as history_id,
        ch.price,
        ch.created_at as price_created_at
        FROM
        coins
        LEFT JOIN coin_history ch ON ch.coin = coins.id AND ch.current = 1 ";

    public function CreateCoin($data){
        $name = $data['name'];
        $url = $data['url'];
        $active = $data['active'];
        $auto_update = $data['auto_update'];

        $sql = "INSERT INTO coins (name, url, active, auto_update) VALUES ('$name', '$url', $active, $auto_update)";
        
        $created = parent::DoQuery($sql);        

        if($created === false) 
            $result = false;
        else
            $result = $this->GetCoinByName($name);

        return $result;
    }

    public function GetAllCoins(){
        $sql = $this->SELECT_TEMPLATE . " ORDER BY coins.name";
        return parent::GetRows($sql, true);
    }

    public function GetCoinPriceOfDate($id, $date){
        $sql = "SELECT 
            coins.id,
            coins.name,
            coins.active,
            coins.created_at,
            coins.auto_update,
            coin_history.id as history_id,
            coin_history.created_at as price_created_at,
            coin_history.price,
            coin_history.current
            FROM
            coins
            INNER JOIN coin_history ON coin_history.coin = coins.id
            WHERE
            coin_history.created_at = '$date' AND
            coins.id = $id";

        return parent::GetRow($sql);
    }

    public function GetCoinValuesOfDate($date){
        $sql = "SELECT 
        coins.name,
        coin_history.price
        FROM
        coins
        INNER JOIN coin_history ON coin_history.coin = coins.id
        WHERE
        DATE(coin_history.created_at) = '$date'
        GROUP BY
        coins.id";

        return parent::GetRows($sql);
    }

    /**
     * Retorna todas las monedas cuya tasa no esté actualizada al día de hoy
     * @param bool $include_not_auto_updated Si recibe true incluye las monedas que tengan auto_update, sino, las omite
     */
    public function GetNotUpdatedCoins(bool $include_not_auto_updated = true){
        $sql = $this->SELECT_TEMPLATE . " WHERE coins.id NOT IN (
            SELECT
            coins.id
            FROM
            coins
            INNER JOIN coin_history ON coin_history.coin = coins.id AND coin_history.current = 1
            WHERE
            DATE(coin_history.created_at) = DATE(NOW())
            )";

        if($include_not_auto_updated === false)
            $sql .= " AND coins.auto_update = 1";

        $not_updated_coins = parent::GetRows($sql, true);
        return $not_updated_coins;
    }

    public function GetActiveCoins(){
        $sql = $this->SELECT_TEMPLATE . " WHERE coins.active = 1 ORDER BY coins.name";
        return parent::GetRows($sql, true);
    }

    public function GetCoin($id){
        $sql = $this->SELECT_TEMPLATE . " WHERE coins.id = '$id'";
        return parent::GetRow($sql);
    }

    public function GetCoinByName($name){
        $sql = $this->SELECT_TEMPLATE . " WHERE coins.name = '$name'";
        return parent::GetRow($sql);
    }

    public function GetCoinHistory($coinId){
        return parent::GetRows("SELECT * FROM coin_history WHERE coin = $coinId ORDER BY created_at DESC", true);
    }

    /**
     * Retorna los últimos X valores de las monedas registradas (obviando el bolívar)
     * @param int $limit el límite de registros a mostrar por moneda
     */
    public function GetOrderedCoinHistories($limit = 7){
        $result = [];
        $coins = $this->GetActiveCoins();

        foreach($coins as $coin){
            $coinId = $coin['id'];
            $sql = "SELECT * FROM coin_history WHERE coin = $coinId ORDER BY created_at DESC LIMIT $limit";
            $history = parent::GetRows($sql, true);
            $result[$coin['name']] = $history;
        }

        return $result;
    }

    public function UpdateCoin($id, $data){
        $name = $data['name'];
        $url = $data['url'];
        $active = $data['active'];
        $auto_update = $data['auto_update'];

        $sql = "UPDATE coins SET
            name = '$name',
            url = '$url',
            active = $active,
            auto_update = $auto_update
            WHERE
            id = $id";
        
        return parent::DoQuery($sql);
    }

    public function UpdateCoinPrice($price, $id){
        $disabled = $this->DisableAllPricesOfCoin($id);
        if($disabled === false)
            $result = false;
        else{
            $sql = "INSERT INTO coin_history (coin, price) VALUES ($id, '$price')";
            $result = parent::DoQuery($sql);
        }

        return $result;
    }

    /**
     * Actualiza la tasa de una moneda en una fecha dada, si la fecha no existe crea un registro nuevo con current = 0
     */
    public function UpdateCoinPriceOfDate($id, $price, $date){
        $exists = $this->GetCoinPriceOfDate($id, $date);
        if($exists === false)
            $sql = "INSERT INTO coin_history (coin, price, current, created_at) VALUES ($id, $price, 0, '$date 00:00:00')";
        else
            $sql = "UPDATE coin_history SET price='$price' WHERE id = " . $exists['history_id'];

        return parent::DoQuery($sql);
    }

    public function DisableAllPricesOfCoin($id){
        $sql = "UPDATE coin_history SET current = 0 WHERE coin = $id";
        return parent::DoQuery($sql);
    }
}