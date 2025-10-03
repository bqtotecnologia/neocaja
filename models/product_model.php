<?php
include_once 'SQL_model.php';

class ProductModel extends SQLModel
{ 
    private $SELECT_TEMPLATE = "SELECT 
        pr.id,
        pr.name,
        pr.active,
        pr.created_at as product_created_at,
        ph.id as history_id,
        ph.price,
        ph.created_at as price_created_at
        FROM
        products pr
        LEFT JOIN product_history ph ON ph.product = pr.id AND ph.current = 1 ";

    public function CreateProduct($data){
        $name = $data['name'];
        $active = $data['active'];

        $sql = "INSERT INTO products (name, active) VALUES ('$name', $active)";
        $created = parent::DoQuery($sql);

        if($created === false) 
            $result = false;
        else
            $result = $this->GetProductByName($name);

        return $result;
    }

    public function GetAllProducts(){
        $sql = $this->SELECT_TEMPLATE . " ORDER BY pr.name";
        return parent::GetRows($sql, true);
    }

    public function GetActiveProducts(){
        $sql = $this->SELECT_TEMPLATE . " WHERE pr.active = 1 ORDER BY pr.name";
        return parent::GetRows($sql, true);
    }

    public function GetProduct($id){
        $sql = $this->SELECT_TEMPLATE . " WHERE pr.id = '$id'";
        return parent::GetRow($sql);
    }

    public function GetProductByName($name){
        $sql = $this->SELECT_TEMPLATE . " WHERE pr.name = '$name'";
        return parent::GetRow($sql);
    }

    public function GetProductHistory($productId){
        return parent::GetRows("SELECT * FROM product_history WHERE product = $productId ORDER BY created_at DESC", true);
    }

    public function UpdateProduct($id, $data){
        $name = $data['name'];
        $active = $data['active'];

        $sql = "UPDATE products SET
            name = '$name',
            active = $active
            WHERE
            id = $id";
        
        return parent::DoQuery($sql);
    }

    public function UpdateProductPrice($price, $id){
        $disabled = $this->DisableAllPricesOfProduct($id);
        if($disabled === false)
            $result = false;
        else{
            $sql = "INSERT INTO product_history (product, price) VALUES ($id, '$price')";
            $result = parent::DoQuery($sql);
        }

        return $result;
    }

    public function DisableAllPricesOfProduct($id){
        $sql = "UPDATE product_history SET current = 0 WHERE product = $id";
        return parent::DoQuery($sql);
    }

    /**
     * Retorna los productos que el estudiante ve al momento de pagar
    */
    public function GetAvailableProductsOfStudent($cedula, $period){
        include_once 'invoice_model.php';
        include_once 'global_vars_model.php';
        include_once 'account_model.php';

        $invoice_model = new InvoiceModel();
        $global_vars_model = new GlobalVarsModel();
        $account_model = new AccountModel();

        $global_vars = $global_vars_model->GetGlobalVars(true);        
        $monthly = $this->GetProductByName('Mensualidad');
        $monthlyPrice = floatval($monthly['price']);
        $foc = $this->GetProductByName('FOC');

        $target_account = $account_model->GetAccountByCedula($cedula);
        $scholarshipped = !($target_account['scholarship'] === NULL && $target_account['scholarship_coverage'] === 0);
        if($scholarshipped){
            $monthlyPrice = $monthlyPrice - ($monthlyPrice * (floatval($target_account['scholarship_coverage']) / 100));
        }             
        
        $monthStates = $invoice_model->GetAccountState($cedula, $period);
        $nonPaid = [];
        if($invoice_model->AccountPaidFOCOnPeriod($cedula, $period) === false){
            $to_add = [
                'name' => 'FOC',
                'price' => floatval($foc['price']),
                'code' => sha1($cedula . 'F'),
                'month' => null,
            ];
            array_push($nonPaid, $to_add);
        }
            
        foreach($monthStates as $month => $value){
            if($value['paid'] === 1)
                continue;

            $monthFinalPrice = $monthlyPrice;

            $monthNumber = intval($this->GetMonthNumberByName($month));

            $to_add = [
                'name' => 'Mensualidad ' . $month,
                'code' => $cedula . 'M' . $monthNumber,
                'month' => $monthNumber,
            ];

            if($value['partial'] === 1){                
                $remaining = $invoice_model->GetRemainingPriceOfMonthOfStudent($monthNumber, $cedula, $period);
                $to_add['name'] = 'Restante ' . $to_add['name'];
                $monthFinalPrice -= $remaining;
                $to_add['code'] .= 'P';
            }

            if($value['debt'] === 1){
                $to_add['name'] .= ' con mora';
                $monthFinalPrice += $monthFinalPrice * ($global_vars['Porcentaje mora'] / 100);
                $monthFinalPrice = round(floatval($monthFinalPrice), 2);
                $to_add['code'] .= 'R';
            }
            
            $to_add['price'] = $monthFinalPrice;
            $to_add['code'] = sha1($to_add['code']);
            
            array_push($nonPaid, $to_add);
        }
        return $nonPaid;
    }
}