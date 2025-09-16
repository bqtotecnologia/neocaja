<?php

include_once 'SQL_model.php';

class InvoiceModel extends SQLModel
{    
    public $SINGLE_SELECT_TEMPLATE = "SELECT 
        invoices.id,
        invoices.control_number,
        invoices.invoice_number,
        invoices.created_at,
        invoices.period,
        invoices.active,
        invoices.observation,
        invoices.rate_date,
        CONCAT(accounts.names, ' ', accounts.surnames) as account_fullname,
        accounts.cedula,
        accounts.id as account_id
        FROM
        invoices
        INNER JOIN accounts ON accounts.id = invoices.account 
    ";

    public function CreateInvoice(array $data, string $period_id){
        $invoice_number = $data['invoice_number'];
        $control_number = $data['control_number'];
        $rate_date = $data['rate-date']->format('Y-m-d');
        $account = $data['account'];
        $observation = $data['observation'];

        $sql = "INSERT INTO invoices
        (
        invoice_number,
        control_number,
        rate_date,
        account,
        observation,
        period
        )
        VALUES
        (
        $invoice_number,
        $control_number,
        '$rate_date',
        $account,
        $observation,
        $period_id
        )";

        $created = parent::DoQuery($sql);
        if($created === true)
            $result = $this->GetInvoiceByControlNumber($control_number);
        else
            $result = false;

        return $result;
    }

    public function AddConceptToInvoice(array $concept, string $invoice_id){
        $product_history = $concept['history_id'];
        $price = $concept['price'];
        $month = $concept['month'];

        $sql = "INSERT INTO concepts
        (
        product,
        price,
        invoice,
        month
        )
        VALUES
        (
        $product_history,
        $price,
        $invoice_id,
        $month
        )";

        return parent::DoQuery($sql);
    }

    public function AddPaymentMethodToInvoice(array $payment_method, string $invoice_id){
        $method = $payment_method['method'];
        $coin = $payment_method['coin'];
        $salepoint = $payment_method['salepoint'];
        $document_number = $payment_method['document_number'];
        $bank = $payment_method['bank'];
        $price = $payment_method['price'];
        $igtf = $payment_method['igtf'];

        $sql = "INSERT INTO invoice_payment_method
        (
        invoice,
        type,
        price,
        coin,
        bank,
        sale_point,
        document_number,
        igtf
        )
        VALUES
        (
        $invoice_id,
        $method,
        $price,
        $coin,
        $bank,
        $salepoint,
        $document_number,
        $igtf
        )";

        var_dump($sql);
        echo '<br>';
        return parent::DoQuery($sql);
    }

    public function GetAllInvoices(){
        $sql = $this->SINGLE_SELECT_TEMPLATE . " ORDER BY invoices.created_at DESC";
        return parent::GetRows($sql, true);
    }

    public function GetInvoicesOfDate($date){
        $sql = $this->SINGLE_SELECT_TEMPLATE . " WHERE DATE(invoices.created_at) = '$date' ORDER BY invoices.created_at DESC";
        return parent::GetRows($sql, true);
    }

    public function GetInvoice($id){
        $sql = $this->SINGLE_SELECT_TEMPLATE . " WHERE invoices.id = $id";
        return parent::GetRow($sql);        
    }

    public function GetInvoiceByInvoiceNumber($number){
        $sql = $this->SINGLE_SELECT_TEMPLATE . " WHERE invoices.invoice_number = $number";
        return parent::GetRow($sql);
    }

    public function GetInvoiceByControlNumber($number){
        $sql = $this->SINGLE_SELECT_TEMPLATE . " WHERE invoices.control_number = $number";
        return parent::GetRow($sql);
    }

    /**
     * Retorna los últimos números de control y de factura
     */
    public function GetLatestNumbers(){
        $sql = "SELECT 
            (MAX(invoice_number) + 1) as invoice_number, 
            (MAX(control_number) + 1) as control_number
            FROM
            invoices";
        
        $numbers = parent::GetRow($sql);
        if($numbers['invoice_number'] === NULL)
            $numbers['invoice_number'] = 1;

        if($numbers['control_number'] === NULL)
            $numbers['control_number'] = 1;

        return $numbers;
    }

    /**
     * Retorna una lista con los ids de los periodos registrados en las facturas
     */
    public function GetPeriodsOfInvoices(){
        $sql = "SELECT period FROM invoices GROUP BY period";
        $ids = parent::GetRows($sql);

        $result = [];
        foreach($ids as $id){
            array_push($result, $id['period']);
        }

        return $result;
    }

    public function GetInvoicesOfAccountOfPeriod(string $account, string $period){
        $sql = "SELECT 
            inv.id,
            inv.invoice_number,
            inv.control_number,
            inv.created_at,
            CONCAT(accounts.surnames, ' ', accounts.names, ' ') as account_fullname,
            accounts.cedula,
            inv.observation
            FROM
            invoices inv
            INNER JOIN accounts ON accounts.id = inv.account 
            WHERE 
            accounts.id = $account AND 
            inv.period = $period AND
            inv.active = 1
            GROUP BY
            inv.id
            ORDER BY
            inv.created_at DESC";

        return parent::GetRows($sql, true);
    }

    public function GetInvoicesOfAccount($account){
        $sql = "SELECT 
            inv.id,
            inv.invoice_number,
            inv.control_number,
            inv.created_at,
            CONCAT(accounts.surnames, ' ', accounts.names, ' ') as account_fullname,
            accounts.cedula,
            inv.observation
            FROM
            invoices inv
            INNER JOIN accounts ON accounts.id = inv.account 
            WHERE 
            accounts.id = $account AND
            inv.active = 1
            GROUP BY
            inv.id
            ORDER BY
            inv.created_at DESC";


        return parent::GetRows($sql, true);
    }

    public function GetPaymentMethodsOfInvoice($id){
        $sql = "SELECT
        ipm.price,
        coins.name as coin,
        banks.name as bank,
        payment_method_types.name as payment_method,
        ipm.document_number,
        ipm.igtf,
        sale_points.code as sale_point
        FROM
        invoice_payment_method ipm
        INNER JOIN coin_history ON coin_history.id = ipm.coin
        INNER JOIN coins ON coins.id = coin_history.coin
        INNER JOIN payment_method_types ON payment_method_types.id = ipm.type
        LEFT JOIN banks ON banks.id = ipm.bank
        LEFT JOIN sale_points ON sale_points.id = ipm.sale_point
        WHERE
        ipm.invoice = $id";
        
        return parent::GetRows($sql);
    }

    public function GetConceptsOfInvoice($id){
        $sql = "SELECT
        products.name as product,
        concepts.price,
        concepts.month
        FROM
        concepts
        INNER JOIN product_history ON product_history.id = concepts.product
        INNER JOIN products ON products.id = product_history.product
        WHERE
        concepts.invoice = $id";

        return parent::GetRows($sql);
    }

    /**
     * Retorna el estado de cuenta de un cliente de un periodo específico
     * Especifica si cada mes fue pagado, es o estuvo moroso y si abonó
     */
    public function GetAccountState($cedula, $periodId){
        include_once 'global_vars_model.php';       
        include_once 'siacad_model.php';

        $global_model = new GlobalVarsModel();
        $siacad = new SiacadModel();

        $periodMonths = $siacad->GerMonthsOfPeriodo($periodId, true);
        $global_vars = $global_model->GetGlobalVars(true);

        $sql = "SELECT
            products.name as product,
            concepts.price,
            concepts.month,
            invoices.id as invoice,
            invoices.created_at
            FROM
            invoices
            INNER JOIN concepts ON concepts.invoice = invoices.id
            INNER JOIN products ON products.id = concepts.product
            INNER JOIN accounts ON accounts.id = invoices.account
            WHERE
            accounts.cedula = '$cedula' AND
            invoices.period = $periodId AND
            products.name LIKE '%Mensualidad%'AND
            concepts.month IS NOT NULL AND
            invoices.active = 1
            ORDER BY
            concepts.month";

        $concepts = parent::GetRows($sql, true);
        
        $ordered_concepts = [];
        foreach($periodMonths as $month){
            $ordered_concepts[$month] = [
                'concepts' => [],
                'paid' => 0,
                'debt' => 0,
                'partial' => 0,
                'valid' => 0,
            ];
        }

        
        
        foreach($concepts as $concept){           
            $target_month = $this->month_translate[strval($concept['month'])];
            if(!isset($ordered_concepts[$target_month]))
                continue;
            
            $ordered_concepts[$target_month]['invoice'] = $concept['invoice'];
            $ordered_concepts[$target_month]['date'] = $concept['created_at'];
            $ordered_concepts[$target_month]['valid'] = 1;

            $ordered_concepts[$target_month]['concepts'][$concept['product']] = $concept['price'];
        }     
        $result = [];

        if($concepts !== []){
            foreach($ordered_concepts as $key => $value){
                if(!in_array($key, $periodMonths)){
                    continue;
                }
                $result[$key] = $value; 

                if(array_key_exists('Mensualidad', $value['concepts']) || array_key_exists('Saldo Mensualidad', $value['concepts'])){
                    // El mes está pagado
                    $result[$key]['paid'] = 1;
                }
    
                if($result[$key]['paid'] === 1){
                    if(array_key_exists('Diferencia Mensualidad', $value['concepts']))
                        $result[$key]['debt'] = 1;
                }
                else if(isset($result[$key]['invoice'])){
                    $timezone = new DateTimeZone('America/Caracas');
                    $invoice_date = new DateTime($value['date'], $timezone);
                    $invoice_month = intval($invoice_date->format('m'));
                    $invoice_year = $invoice_date->format('Y');
    
                    $retard_date = "$invoice_year-$invoice_month-" . intval($global_vars['Dia tope mora']);
                    $retard_date = new DateTime($retard_date, $timezone);
                    
                    if(intval($key) < $invoice_month){
                        $result[$key]['debt'] = 1;
                    }
                    else if(intval($key) === $invoice_month && $invoice_date > $retard_date){
                        $result[$key]['debt'] = 1;
                    }
                }
    
                if(array_key_exists('Abono Mensualidad', $value['concepts'])){
                    $result[$key]['partial'] = 1;
                }
            }
        }
        else{
            $result = $ordered_concepts;
        }

        return $result;
    }

    /**
     * Obtiene la deuda de un cliente en este periodo.
     * Especifica los meses que debe incluyendo el actual.
     * También verifica que el estudiante haya pagado FOC.
     */
    public function GetDebtOfAccountOfPeriod($cedula, $periodId){
        include_once 'siacad_model.php';
        include_once 'global_vars_model.php';
        include_once 'product_model.php';

        $siacad = new SiacadModel();
        $global_vars_model = new GlobalVarsModel();
        $product_model = new ProductModel();

        $global_vars = $global_vars_model->GetGlobalVars(true);
        $monthly = $product_model->GetProductByName('Mensualidad');
        $target_period = $siacad->GetPeriodoById($periodId);

        $timezone = new DateTimeZone('America/Caracas');
        $start_date = new DateTime($target_period['fechainicio'], $timezone);
        $end_date = new DateTime($target_period['fechafin'], $timezone);

        $periodMonths = [];
        $periodDates = [];
        while(true){
            $month = intval($start_date->format('m'));
            $date = $start_date->format('Y-m-01');
            array_push($periodMonths, $this->month_translate[strval($month)]);
            array_push($periodDates, $date);

            $start_date->modify('+1 month');
            if($start_date > $end_date)
                break;
        }

        $accountState = $this->GetAccountState($cedula, $periodId);
        $cleanState = [];

        $debt_data = [
            'months' => 0,
            'retard' => 0,
            'foc' => true
        ];

        foreach($accountState as $month => $value){
            if($value['paid'] === 0 && in_array($month, $periodMonths))
                $cleanState[$month] = $value;
        }
        
        $real_today = new DateTime('now', $timezone);
        $fake_today = new DateTime('now', $timezone);
        $fake_today->setDate($fake_today->format('Y'), $fake_today->format('m'), '1');        
        

        foreach($periodDates as $periodDate){
            $date = new DateTime($periodDate, $timezone);

            if($date > $fake_today)
                break;

            $month = intval($date->format('m'));
            $monthName = $this->month_translate[strval($month)];

            if(!isset($cleanState[$monthName]))
                continue;            

            $monthValue = $cleanState[$monthName];

            $monthly_debt = floatval($monthly['price']);
            if($monthValue['partial'] === 0)
                $debt_data['months'] += $monthly_debt;
            else{
                $monthly_debt -= floatval($monthValue['concepts']['Abono Mensualidad']);
                $debt_data['months'] += $monthly_debt;
            }    

            $retard_applies = false;
            if($month === intval($fake_today->format('m'))){
                if(intval($real_today->format('d')) > intval($global_vars['Dia tope mora']))
                    $retard_applies = true;                    
            }
            else{
                $retard_applies = true;                
            }

            if($retard_applies){
                $retard = round($monthly_debt * ($global_vars['Porcentaje mora'] / 100), 2);
                $debt_data['retard'] += $retard;
            }            
        }

        $debt_data['foc'] = $this->AccountPaidFOCOnPeriod($cedula, $periodId);
        return $debt_data;
    }

    public function AccountPaidFOCOnPeriod($cedula, $periodId){
        $sql = "SELECT
            invoices.id as invoice
            FROM
            invoices
            INNER JOIN concepts ON concepts.invoice = invoices.id
            INNER JOIN products ON products.id = concepts.product
            INNER JOIN accounts ON accounts.id = invoices.account
            WHERE
            accounts.cedula = '$cedula' AND
            invoices.period = $periodId AND
            products.name = 'FOC' AND
            invoices.active = 1";

        $result = parent::GetRow($sql);
        if($result !== false)
            $result = true;

        return $result;
    }

    public function AnullInvoice($id){
        return parent::DoQuery("UPDATE invoices SET active = 0 WHERE id = $id");
    }

    /**
     * Borra una factura. 
     * Solo se ejecuta cuando ocurre un error al momento de agregar los conceptos y métodos de pago durante
     * la creación de la factura
     */
    public function DeleteInvoice($id){
        return parent::DoQuery("DELETE FROM invoices WHERE id = $id");
    }
}