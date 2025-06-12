<?php
include_once 'SQL_model.php';

class CoinModel extends SQLModel
{ 
    private $SELECT_TEMPLATE = "SELECT 
        coins.id,
        coins.name,
        coins.active,
        coins.created_at as coin_created_at,
        coins.url,
        ch.price,
        ch.created_at as price_created_at
        FROM
        coins
        LEFT JOIN coin_history ch ON ch.coin = coins.id AND ch.current = 1 ";

    public function CreateCoin($data){
        $name = $data['name'];
        $url = $data['url'];
        $active = $data['active'];

        $sql = "INSERT INTO coins (name, url, active) VALUES ('$name', '$url', $active)";
        
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

    /**
     * Returns all coins whose value aren't updated to the current date
     */
    public function GetNotUpdatedCoins(){
        $sql = $this->SELECT_TEMPLATE . " WHERE DATE(ch.created_at) = DATE(NOW())";        
        $updated_coins = parent::GetRows($sql, true);

        $ids = '';
        foreach($updated_coins as $coin){
            $ids .= $coin['id'] . ', ';
        }

        if($ids === '')
            $not_updated_coins = [];
        else{
            $ids = trim($ids, ', ');
            $sql = $this->SELECT_TEMPLATE . " WHERE coins.id NOT IN ($ids)";
            $not_updated_coins = parent::GetRows($sql, true);
        }
        return $not_updated_coins;
    }

    public function GetActiveCoins(){
        $sql = $this->SELECT_TEMPLATE . " WHERE coin.active = 1 ORDER BY coin.name";
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

    public function UpdateCoin($id, $data){
        $name = $data['name'];
        $url = $data['url'];
        $active = $data['active'];

        $sql = "UPDATE coins SET
            name = '$name',
            url = '$url',
            active = $active
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

    public function DisableAllPricesOfCoin($id){
        $sql = "UPDATE coin_history SET current = 0 WHERE coin = $id";
        return parent::DoQuery($sql);
    }
}