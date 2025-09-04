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
        $complete = $concept['complete'];

        $sql = "INSERT INTO concepts
        (
        product,
        price,
        invoice,
        month,
        complete
        )
        VALUES
        (
        $product_history,
        $price,
        $invoice_id,
        $month,
        $complete
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

    public function GetInvoicesOfAccountOfPeriod(string $account, string $period){
        $sql = "SELECT 
            inv.id,
            inv.created_at,
            SUM(ipm.total) as total
            FROM
            invoices inv
            INNER JOIN accounts ON accounts.id = inv.account 
            INNER JOIN (
                SELECT
                invoice_payment_method.invoice,
                invoice_payment_method.price * coin_history.price as total
                FROM
                invoice_payment_method
                INNER JOIN coin_history ON coin_history.id = invoice_payment_method.coin
            ) as ipm ON ipm.invoice = inv.id
            WHERE 
            accounts.id = $account AND 
            inv.period = $period AND
            inv.active = 1
            GROUP BY
            inv.id";


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
        concepts.month,
        concepts.complete
        FROM
        concepts
        INNER JOIN product_history ON product_history.id = concepts.product
        INNER JOIN products ON products.id = product_history.product
        WHERE
        concepts.invoice = $id";

        return parent::GetRows($sql);
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