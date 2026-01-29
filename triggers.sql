-- account_company_history
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_account_company_history_AFTER_INSERT`
AFTER INSERT ON `account_company_history`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente un historial de cuenta-empresa ',
                ' con los siguientes datos: ',
                ' id:', NEW.id, ',',
                ' account:', NEW.account, ',',
                ' company:', NEW.company, ',',
                ' current:', NEW.current, ',',
                ' created_at:', NEW.created_at
            )
        );
    END IF;
END;

CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_account_company_history_AFTER_UPDATE`
AFTER UPDATE ON `account_company_history`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente un historial de cuenta-empresa de id ',
                OLD.id,
                ' con los siguientes datos: ',
                ' id:', OLD.id, ' => ', NEW.id, ',',
                ' account:', OLD.account, ' => ', NEW.account, ',',
                ' company:', OLD.company, ' => ', NEW.company, ',',
                ' current:', OLD.current, ' => ', NEW.current, ',',
                ' created_at:', OLD.created_at, ' => ', NEW.created_at
            )
        );
    END IF;
END;

CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_account_company_history_AFTER_DELETE`
AFTER DELETE ON `account_company_history`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un historial de cuenta-empresa ',
                ' con los siguientes datos: ',
                ' id:', OLD.id, ',',
                ' account:', OLD.account, ',',
                ' company:', OLD.company, ',',
                ' current:', OLD.current, ',',
                ' created_at:', OLD.created_at
            )
        );
    END IF;
END;

-- account_payment_products
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_account_payment_products_AFTER_INSERT`
AFTER INSERT ON `account_payment_products`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente un producto pagado del carrito de un cliente ',
                ' con los siguientes datos: ',
                ' id:', NEW.id, ',',
                ' product:', NEW.product, ',',
                ' price:', NEW.price, ',',
                ' payment:', NEW.payment
            )
        );
    END IF;
END;

CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_account_payment_products_AFTER_UPDATE`
AFTER UPDATE ON `account_payment_products`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente un producto pagado del carrito de un cliente de id ',
                OLD.id,
                ' con los siguientes datos: ',
                ' id:', OLD.id, ' => ', NEW.id, ',',
                ' product:', OLD.product, ' => ', NEW.product, ',',
                ' price:', OLD.price, ' => ', NEW.price, ',',
                ' payment:', OLD.payment, ' => ', NEW.payment
            )
        );
    END IF;
END;

CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_account_payment_products_AFTER_DELETE`
AFTER DELETE ON `account_payment_products`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un producto pagado del carrito de un cliente ',
                ' con los siguientes datos: ',
                ' id:', OLD.id, ',',
                ' product:', OLD.product, ',',
                ' price:', OLD.price, ',',
                ' payment:', OLD.payment
            )
        );
    END IF;
END;

-- account_payments
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_account_payments_AFTER_INSERT`
AFTER INSERT ON `account_payments`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente un pago de un cliente ',
                ' con los siguientes datos: ',
                ' id:', NEW.id, ',',
                ' related_id:', NEW.related_id, ',',
                ' related_with:', NEW.related_with, ',',
                ' payment_method_type:', NEW.payment_method_type, ',',
                ' payment_method:', NEW.payment_method, ',',
                ' price:', NEW.price, ',',
                ' ref:', NEW.ref, ',',
                ' document:', NEW.document, ',',
                ' state:', NEW.state, ',',
                ' response:', NEW.response, ',',
                ' created_at:', NEW.created_at
            )
        );
    END IF;
END;

CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_account_payments_AFTER_UPDATE`
AFTER UPDATE ON `account_payments`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente un pago de un cliente de id ',
                OLD.id,
                ' con los siguientes datos: ',
                ' id:', OLD.id, ' => ', NEW.id, ',',
                ' related_id:', OLD.related_id, ' => ', NEW.related_id, ',',
                ' related_with:', OLD.related_with, ' => ', NEW.related_with, ',',
                ' payment_method_type:', OLD.payment_method_type, ' => ', NEW.payment_method_type, ',',
                ' payment_method:', OLD.payment_method, ' => ', NEW.payment_method, ',',
                ' price:', OLD.price, ' => ', NEW.price, ',',
                ' ref:', OLD.ref, ' => ', NEW.ref, ',',
                ' document:', OLD.document, ' => ', NEW.document, ',',
                ' state:', OLD.state, ' => ', NEW.state, ',',
                ' response:', OLD.response, ' => ', NEW.response
                ' created_at:', OLD.created_at, ' => ', NEW.created_at, ',',

            )
        );
    END IF;
END;

CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_account_payments_AFTER_DELETE`
AFTER DELETE ON `account_payments`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un pago de un cliente',
                ' con los siguientes datos: ',
                ' id:', NEW.id, ',',
                ' related_id:', NEW.related_id, ',',
                ' related_with:', NEW.related_with, ',',
                ' payment_method_type:', NEW.payment_method_type, ',',
                ' payment_method:', NEW.payment_method, ',',
                ' price:', NEW.price, ',',
                ' ref:', NEW.ref, ',',
                ' document:', NEW.document, ',',
                ' state:', NEW.state, ',',
                ' response:', NEW.response, ',',
                ' created_at:', NEW.created_at
            )
        );
    END IF;
END;

-- accounts
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_accounts_AFTER_INSERT`
AFTER INSERT ON `accounts`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente un cliente ',
                ' con los siguientes datos: ',
                ' id:', NEW.id, ',',
                ' cedula:', NEW.cedula, ',',
                ' names:', NEW.names, ',',
                ' surnames:', NEW.surnames, ',',
                ' address:', NEW.address, ',',
                ' phone:', NEW.phone, ',',
                ' is_student:', NEW.is_student
                ' scholarship:', NEW.scholarship, ',',
                ' scholarship_coverage:', NEW.scholarship_coverage, ',',
                ' created_at:', NEW.created_at
            )
        );
    END IF;
END;

CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_accounts_AFTER_UPDATE`
AFTER UPDATE ON `accounts`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente un cliente de id ',
                OLD.id,
                ' con los siguientes datos: ',
                ' id:', OLD.id, ' => ', NEW.id, ',',
                ' cedula:', OLD.cedula, ' => ', NEW.cedula, ',',
                ' names:', OLD.names, ' => ', NEW.names, ',',
                ' surnames:', OLD.surnames, ' => ', NEW.surnames, ',',
                ' address:', OLD.address, ' => ', NEW.address, ',',
                ' phone:', OLD.phone, ' => ', NEW.phone, ',',
                ' is_student:', OLD.is_student, ' => ', NEW.is_student, ',',
                ' scholarship:', OLD.scholarship, ' => ', NEW.scholarship, ',',
                ' scholarship_coverage:', OLD.scholarship_coverage, ' => ', NEW.scholarship_coverage, ',',
                ' created_at:', OLD.created_at, ' => ', NEW.created_at
            )
        );
    END IF;
END;

CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_accounts_AFTER_DELETE`
AFTER DELETE ON `accounts`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un cliente ',
                ' con los siguientes datos: ',
                ' id:', OLD.id, ',',
                ' cedula:', OLD.cedula, ',',
                ' names:', OLD.names, ',',
                ' surnames:', OLD.surnames, ',',
                ' address:', OLD.address, ',',
                ' phone:', OLD.phone, ',',
                ' is_student:', OLD.is_student
                ' scholarship:', OLD.scholarship, ',',
                ' scholarship_coverage:', OLD.scholarship_coverage, ',',
                ' created_at:', OLD.created_at
            )
        );
    END IF;
END;

-- admins
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_admins_AFTER_INSERT`
AFTER INSERT ON `admins`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente un administrador ',
                ' con los siguientes datos: ',
                ' id:', NEW.id, ',',
                ' name:', NEW.name, ',',
                ' cedula:', NEW.cedula, ',',
                ' role:', NEW.role, ',',
                ' active:', NEW.active, ',',
                ' created_at:', NEW.created_at
            )
        );
    END IF;
END;

CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_admins_AFTER_UPDATE`
AFTER UPDATE ON `admins`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente un administrador de id ',
                OLD.id,
                ' con los siguientes datos: ',
                ' id:', OLD.id, ' => ', NEW.id, ',',
                ' name:', OLD.name, ' => ', NEW.name, ',',
                ' cedula:', OLD.cedula, ' => ', NEW.cedula, ',',
                ' role:', OLD.role, ' => ', NEW.role, ',',
                ' active:', OLD.active, ' => ', NEW.active
                ' created_at:', OLD.created_at, ' => ', NEW.created_at, ',',
            )
        );
    END IF;
END;

CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_admins_AFTER_DELETE`
AFTER DELETE ON `admins`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un administrador ',
                ' con los siguientes datos: ',
                ' id:', OLD.id, ',',
                ' name:', OLD.name, ',',
                ' cedula:', OLD.cedula, ',',
                ' role:', OLD.role, ',',
                ' active:', OLD.active, ',',
                ' created_at:', OLD.created_at
            )
        );
    END IF;
END;

-- banks
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_banks_AFTER_INSERT`
AFTER INSERT ON `banks`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente un banco ',
                ' con los siguientes datos: ',
                ' name:', NEW.name, ',',
                ' active:', NEW.active
            )
        );
    END IF;
END;

CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_banks_AFTER_UPDATE`
AFTER UPDATE ON `banks`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente un banco de id ',
                OLD.id,
                ' con los siguientes datos: ',
                ' name:', OLD.name, ' => ', NEW.name, ',',
                ' active:', OLD.active, ' => ', NEW.active
            )
        );
    END IF;
END;

CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_banks_AFTER_DELETE`
AFTER DELETE ON `banks`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un banco ',
                ' con los siguientes datos: ',
                ' name:', OLD.name, ',',
                ' active:', OLD.active
            )
        );
    END IF;
END;
///////////////////
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_coin_history_AFTER_INSERT`
AFTER INSERT ON `coin_history`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente un historial de moneda ',
                ' con los siguientes datos: ',
                ' coin:', NEW.coin, ',',
                ' price:', NEW.price, ',',
                ' current:', NEW.current
            )
        );
    END IF;
END;

CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_coin_history_AFTER_UPDATE`
AFTER UPDATE ON `coin_history`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente un historial de moneda de id ',
                OLD.id,
                ' con los siguientes datos: ',
                ' price:', OLD.price, ' => ', NEW.price, ',',
                ' current:', OLD.current, ' => ', NEW.current
            )
        );
    END IF;
END;

CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_coin_history_AFTER_DELETE`
AFTER DELETE ON `coin_history`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un historial de moneda ',
                ' con los siguientes datos: ',
                ' coin:', OLD.coin, ',',
                ' price:', OLD.price
            )
        );
    END IF;
END;
//////////////////////////
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_coins_AFTER_INSERT`
AFTER INSERT ON `coins`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente una moneda ',
                ' con los siguientes datos: ',
                ' name:', NEW.name, ',',
                ' url:', NEW.url, ',',
                ' active:', NEW.active
            )
        );
    END IF;
END;

CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_coins_AFTER_UPDATE`
AFTER UPDATE ON `coins`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente una moneda de id ',
                OLD.id,
                ' con los siguientes datos: ',
                ' name:', OLD.name, ' => ', NEW.name, ',',
                ' active:', OLD.active, ' => ', NEW.active
            )
        );
    END IF;
END;

CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_coins_AFTER_DELETE`
AFTER DELETE ON `coins`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente una moneda ',
                ' con los siguientes datos: ',
                ' name:', OLD.name, ',',
                ' active:', OLD.active
            )
        );
    END IF;
END;
/////////////////////////////////
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_companies_AFTER_INSERT`
AFTER INSERT ON `companies`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente una empresa ',
                ' con los siguientes datos: ',
                ' name:', NEW.name, ',',
                ' rif:', NEW.rif_letter, '-', NEW.rif_number, ',',
                ' address:', NEW.address
            )
        );
    END IF;
END;

CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_companies_AFTER_UPDATE`
AFTER UPDATE ON `companies`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente una empresa de id ',
                OLD.id,
                ' con los siguientes datos: ',
                ' name:', OLD.name, ' => ', NEW.name, ',',
                ' address:', OLD.address, ' => ', NEW.address
            )
        );
    END IF;
END;

CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_companies_AFTER_DELETE`
AFTER DELETE ON `companies`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente una empresa ',
                ' con los siguientes datos: ',
                ' name:', OLD.name, ',',
                ' rif:', OLD.rif_letter, '-', OLD.rif_number
            )
        );
    END IF;
END;
/////////////////////////////
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_concepts_AFTER_INSERT`
AFTER INSERT ON `concepts`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente un concepto ',
                ' con los siguientes datos: ',
                ' product:', NEW.product, ',',
                ' price:', NEW.price, ',',
                ' invoice:', NEW.invoice, ',',
                ' month:', NEW.month
            )
        );
    END IF;
END;

CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_concepts_AFTER_UPDATE`
AFTER UPDATE ON `concepts`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente un concepto de id ',
                OLD.id,
                ' con los siguientes datos: ',
                ' price:', OLD.price, ' => ', NEW.price, ',',
                ' month:', OLD.month, ' => ', NEW.month
            )
        );
    END IF;
END;

CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_concepts_AFTER_DELETE`
AFTER DELETE ON `concepts`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un concepto ',
                ' con los siguientes datos: ',
                ' product:', OLD.product, ',',
                ' invoice:', OLD.invoice
            )
        );
    END IF;
END;
///////////////////////////////////////////
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_global_vars_AFTER_INSERT`
AFTER INSERT ON `global_vars`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente una variable global ',
                ' con los siguientes datos: ',
                ' name:', NEW.name
            )
        );
    END IF;
END;

CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_global_vars_AFTER_UPDATE`
AFTER UPDATE ON `global_vars`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente una variable global de id ',
                OLD.id,
                ' con los siguientes datos: ',
                ' name:', OLD.name, ' => ', NEW.name
            )
        );
    END IF;
END;

CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_global_vars_AFTER_DELETE`
AFTER DELETE ON `global_vars`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente una variable global ',
                ' con los siguientes datos: ',
                ' name:', OLD.name
            )
        );
    END IF;
END;
//////////////////////////////////////////
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_global_vars_history_AFTER_INSERT`
AFTER INSERT ON `global_vars_history`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente un historial de variable global ',
                ' con los siguientes datos: ',
                ' global_var:', NEW.global_var, ',',
                ' value:', NEW.value, ',',
                ' current:', NEW.current
            )
        );
    END IF;
END;

CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_global_vars_history_AFTER_UPDATE`
AFTER UPDATE ON `global_vars_history`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente un historial de variable global de id ',
                OLD.id,
                ' con los siguientes datos: ',
                ' value:', OLD.value, ' => ', NEW.value, ',',
                ' current:', OLD.current, ' => ', NEW.current
            )
        );
    END IF;
END;

CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_global_vars_history_AFTER_DELETE`
AFTER DELETE ON `global_vars_history`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un historial de variable global ',
                ' con los siguientes datos: ',
                ' global_var:', OLD.global_var, ',',
                ' value:', OLD.value
            )
        );
    END IF;
END;
//////////////////////////////////
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_invoice_payment_method_AFTER_INSERT`
AFTER INSERT ON `invoice_payment_method`
FOR EACH ROW
BEGIN
IF @app_audit IS NULL THEN
INSERT INTO binnacle (action) VALUES (
CONCAT(
'Una persona ha agregado manualmente un ',
' con los siguientes datos: ',
'invoice:', new.invoice, ',',
'type:', new.type, ',',
'price:', new.price, ',',
'coin:', new.coin, ',',
'bank:', new.bank, ',',
'sale_point:', new.sale_point, ',',
'document_number:', new.document_number, ',',
'igtf:', new.igtf
));
END IF;
END;

CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_invoice_payment_method_AFTER_UPDATE`
AFTER UPDATE ON `invoice_payment_method`
FOR EACH ROW
BEGIN
IF @app_audit IS NULL THEN
INSERT INTO binnacle (action) VALUES (
CONCAT(
'Una persona ha modificado manualmente un  de id ', old.id,
' con los siguientes datos: ',
'invoice:', old.invoice, ' => ', new.invoice, ',',
'type:', old.type, ' => ', new.type, ',',
'price:', old.price, ' => ', new.price, ',',
'coin:', old.coin, ' => ', new.coin, ',',
'bank:', old.bank, ' => ', new.bank, ',',
'sale_point:', old.sale_point, ' => ', new.sale_point, ',',
'document_number:', old.document_number, ' => ', new.document_number, ',',
'igtf:', old.igtf, ' => ', new.igtf
));
END IF;
END;

CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_invoice_payment_method_AFTER_DELETE`
AFTER DELETE ON `invoice_payment_method`
FOR EACH ROW
BEGIN
IF @app_audit IS NULL THEN
INSERT INTO binnacle (action) VALUES (
CONCAT(
'Una persona ha borrado manualmente un ',
' con los siguientes datos: ',
'invoice:', old.invoice, ',',
'type:', old.type, ',',
'price:', old.price
));
END IF;
END;
/////////////////////////////////////
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_invoices_AFTER_INSERT`
AFTER INSERT ON `invoices`
FOR EACH ROW
BEGIN
IF @app_audit IS NULL THEN
INSERT INTO binnacle (action) VALUES (
CONCAT(
'Una persona ha agregado manualmente un ',
' con los siguientes datos: ',
'invoice_number:', new.invoice_number, ',',
'control_number:', new.control_number, ',',
'account:', new.account, ',',
'observation:', new.observation, ',',
'active:', new.active, ',',
'period:', new.period, ',',
'rate_date:', new.rate_date
));
END IF;
END;

CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_invoices_AFTER_UPDATE`
AFTER UPDATE ON `invoices`
FOR EACH ROW
BEGIN
IF @app_audit IS NULL THEN
INSERT INTO binnacle (action) VALUES (
CONCAT(
'Una persona ha modificado manualmente un  de id ', old.id,
' con los siguientes datos: ',
'invoice_number:', old.invoice_number, ' => ', new.invoice_number, ',',
'control_number:', old.control_number, ' => ', new.control_number, ',',
'account:', old.account, ' => ', new.account, ',',
'observation:', old.observation, ' => ', new.observation, ',',
'active:', old.active, ' => ', new.active, ',',
'period:', old.period, ' => ', new.period, ',',
'rate_date:', old.rate_date, ' => ', new.rate_date
));
END IF;
END;

CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_invoices_AFTER_DELETE`
AFTER DELETE ON `invoices`
FOR EACH ROW
BEGIN
IF @app_audit IS NULL THEN
INSERT INTO binnacle (action) VALUES (
CONCAT(
'Una persona ha borrado manualmente un ',
' con los siguientes datos: ',
'invoice_number:', old.invoice_number, ',',
'control_number:', old.control_number
));
END IF;
END;
//////////////////////////////////
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_mobile_payments_AFTER_INSERT`
AFTER INSERT ON `mobile_payments`
FOR EACH ROW
BEGIN
IF @app_audit IS NULL THEN
INSERT INTO binnacle (action) VALUES (
CONCAT(
'Una persona ha agregado manualmente un ',
' con los siguientes datos: ',
'phone:', new.phone, ',',
'bank:', new.bank, ',',
'document_letter:', new.document_letter, ',',
'document_number:', new.document_number, ',',
'active:', new.active
));
END IF;
END;

CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_mobile_payments_AFTER_UPDATE`
AFTER UPDATE ON `mobile_payments`
FOR EACH ROW
BEGIN
IF @app_audit IS NULL THEN
INSERT INTO binnacle (action) VALUES (
CONCAT(
'Una persona ha modificado manualmente un  de id ', old.id,
' con los siguientes datos: ',
'phone:', old.phone, ' => ', new.phone, ',',
'bank:', old.bank, ' => ', new.bank, ',',
'document_letter:', old.document_letter, ' => ', new.document_letter, ',',
'document_number:', old.document_number, ' => ', new.document_number, ',',
'active:', old.active, ' => ', new.active
));
END IF;
END;

CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_mobile_payments_AFTER_DELETE`
AFTER DELETE ON `mobile_payments`
FOR EACH ROW
BEGIN
IF @app_audit IS NULL THEN
INSERT INTO binnacle (action) VALUES (
CONCAT(
'Una persona ha borrado manualmente un ',
' con los siguientes datos: ',
'phone:', old.phone, ',',
'document_number:', old.document_number
));
END IF;
END;
/////////////////////////////////////
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_notifications_AFTER_INSERT`
AFTER INSERT ON `notifications`
FOR EACH ROW
BEGIN
IF @app_audit IS NULL THEN
INSERT INTO binnacle (action) VALUES (
CONCAT(
'Una persona ha agregado manualmente un ',
' con los siguientes datos: ',
'cedula:', new.cedula, ',',
'message:', new.message, ',',
'viewed:', new.viewed
));
END IF;
END;

CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_notifications_AFTER_UPDATE`
AFTER UPDATE ON `notifications`
FOR EACH ROW
BEGIN
IF @app_audit IS NULL THEN
INSERT INTO binnacle (action) VALUES (
CONCAT(
'Una persona ha modificado manualmente un  de id ', old.id,
' con los siguientes datos: ',
'cedula:', old.cedula, ' => ', new.cedula, ',',
'message:', old.message, ' => ', new.message, ',',
'viewed:', old.viewed, ' => ', new.viewed
));
END IF;
END;

CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_notifications_AFTER_DELETE`
AFTER DELETE ON `notifications`
FOR EACH ROW
BEGIN
IF @app_audit IS NULL THEN
INSERT INTO binnacle (action) VALUES (
CONCAT(
'Una persona ha borrado manualmente un ',
' con los siguientes datos: ',
'cedula:', old.cedula, ',',
'message:', old.message
));
END IF;
END;
//////////////////////////////////////////
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_payment_method_types_AFTER_INSERT`
AFTER INSERT ON `payment_method_types`
FOR EACH ROW BEGIN
IF @app_audit IS NULL THEN
INSERT INTO binnacle (action) VALUES (
CONCAT(
'Una persona ha agregado manualmente un ',
' con los siguientes datos: ',
'id:', new.id, ',',
'name:', new.name, ',',
'created_at:', new.created_at
));
END IF;
END;

CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_payment_method_types_AFTER_UPDATE`
AFTER UPDATE ON `payment_method_types`
FOR EACH ROW BEGIN
IF @app_audit IS NULL THEN
INSERT INTO binnacle (action) VALUES (
CONCAT(
'Una persona ha modificado manualmente un  de id ', old.id,
' con los siguientes datos: ',
'name:', old.name, ' => ', new.name, ',',
'created_at:', old.created_at, ' => ', new.created_at
));
END IF;
END;

CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_payment_method_types_AFTER_DELETE`
AFTER DELETE ON `payment_method_types`
FOR EACH ROW BEGIN
IF @app_audit IS NULL THEN
INSERT INTO binnacle (action) VALUES (
CONCAT(
'Una persona ha borrado manualmente un ',
' con los siguientes datos: ',
'id:', old.id, ',',
'name:', old.name
));
END IF;
END;
//////////////////////////
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_product_history_AFTER_INSERT`
AFTER INSERT ON `product_history`
FOR EACH ROW BEGIN
IF @app_audit IS NULL THEN
INSERT INTO binnacle (action) VALUES (
CONCAT(
'Una persona ha agregado manualmente un ',
' con los siguientes datos: ',
'product:', new.product, ',',
'price:', new.price, ',',
'current:', new.current
));
END IF;
END;

CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_product_history_AFTER_UPDATE`
AFTER UPDATE ON `product_history`
FOR EACH ROW BEGIN
IF @app_audit IS NULL THEN
INSERT INTO binnacle (action) VALUES (
CONCAT(
'Una persona ha modificado manualmente un  de id ', old.id,
' con los siguientes datos: ',
'price:', old.price, ' => ', new.price, ',',
'current:', old.current, ' => ', new.current
));
END IF;
END;

CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_product_history_AFTER_DELETE`
AFTER DELETE ON `product_history`
FOR EACH ROW BEGIN
IF @app_audit IS NULL THEN
INSERT INTO binnacle (action) VALUES (
CONCAT(
'Una persona ha borrado manualmente un ',
' con los siguientes datos: ',
'product:', old.product, ',',
'price:', old.price
));
END IF;
END;
/////////////////////////////
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_products_AFTER_INSERT`
AFTER INSERT ON `products`
FOR EACH ROW BEGIN
IF @app_audit IS NULL THEN
INSERT INTO binnacle (action) VALUES (
CONCAT(
'Una persona ha agregado manualmente un ',
' con los siguientes datos: ',
'name:', new.name, ',',
'active:', new.active
));
END IF;
END;

CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_products_AFTER_UPDATE`
AFTER UPDATE ON `products`
FOR EACH ROW BEGIN
IF @app_audit IS NULL THEN
INSERT INTO binnacle (action) VALUES (
CONCAT(
'Una persona ha modificado manualmente un  de id ', old.id,
' con los siguientes datos: ',
'name:', old.name, ' => ', new.name, ',',
'active:', old.active, ' => ', new.active
));
END IF;
END;

CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_products_AFTER_DELETE`
AFTER DELETE ON `products`
FOR EACH ROW BEGIN
IF @app_audit IS NULL THEN
INSERT INTO binnacle (action) VALUES (
CONCAT(
'Una persona ha borrado manualmente un ',
' con los siguientes datos: ',
'name:', old.name
));
END IF;
END;
//////////////////////////////
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_roles_AFTER_INSERT`
AFTER INSERT ON `roles`
FOR EACH ROW BEGIN
IF @app_audit IS NULL THEN
INSERT INTO binnacle (action) VALUES (
CONCAT(
'Una persona ha agregado manualmente un ',
' con los siguientes datos: ',
'name:', new.name
));
END IF;
END;

CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_roles_AFTER_UPDATE`
AFTER UPDATE ON `roles`
FOR EACH ROW BEGIN
IF @app_audit IS NULL THEN
INSERT INTO binnacle (action) VALUES (
CONCAT(
'Una persona ha modificado manualmente un  de id ', old.id,
' con los siguientes datos: ',
'name:', old.name, ' => ', new.name
));
END IF;
END;

CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_roles_AFTER_DELETE`
AFTER DELETE ON `roles`
FOR EACH ROW BEGIN
IF @app_audit IS NULL THEN
INSERT INTO binnacle (action) VALUES (
CONCAT(
'Una persona ha borrado manualmente un ',
' con los siguientes datos: ',
'name:', old.name
));
END IF;
END;
////////////////////////////
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_sale_points_AFTER_INSERT`
AFTER INSERT ON `sale_points`
FOR EACH ROW BEGIN
IF @app_audit IS NULL THEN
INSERT INTO binnacle (action) VALUES (
CONCAT(
'Una persona ha agregado manualmente un ',
' con los siguientes datos: ',
'code:', new.code, ',',
'bank:', new.bank
));
END IF;
END;

CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_sale_points_AFTER_UPDATE`
AFTER UPDATE ON `sale_points`
FOR EACH ROW BEGIN
IF @app_audit IS NULL THEN
INSERT INTO binnacle (action) VALUES (
CONCAT(
'Una persona ha modificado manualmente un  de id ', old.id,
' con los siguientes datos: ',
'code:', old.code, ' => ', new.code, ',',
'bank:', old.bank, ' => ', new.bank
));
END IF;
END;

CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_sale_points_AFTER_DELETE`
AFTER DELETE ON `sale_points`
FOR EACH ROW BEGIN
IF @app_audit IS NULL THEN
INSERT INTO binnacle (action) VALUES (
CONCAT(
'Una persona ha borrado manualmente un ',
' con los siguientes datos: ',
'code:', old.code
));
END IF;
END;
////////////////////////
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_scholarships_AFTER_INSERT`
AFTER INSERT ON `scholarships`
FOR EACH ROW BEGIN
IF @app_audit IS NULL THEN
INSERT INTO binnacle (action) VALUES (
CONCAT(
'Una persona ha agregado manualmente un ',
' con los siguientes datos: ',
'name:', new.name
));
END IF;
END;

CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_scholarships_AFTER_UPDATE`
AFTER UPDATE ON `scholarships`
FOR EACH ROW BEGIN
IF @app_audit IS NULL THEN
INSERT INTO binnacle (action) VALUES (
CONCAT(
'Una persona ha modificado manualmente un  de id ', old.id,
' con los siguientes datos: ',
'name:', old.name, ' => ', new.name
));
END IF;
END;

CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_scholarships_AFTER_DELETE`
AFTER DELETE ON `scholarships`
FOR EACH ROW BEGIN
IF @app_audit IS NULL THEN
INSERT INTO binnacle (action) VALUES (
CONCAT(
'Una persona ha borrado manualmente un ',
' con los siguientes datos: ',
'name:', old.name
));
END IF;
END;
//////////////////////////////////
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_self_data_AFTER_INSERT`
AFTER INSERT ON `self_data`
FOR EACH ROW BEGIN
IF @app_audit IS NULL THEN
INSERT INTO binnacle (action) VALUES (
CONCAT(
'Una persona ha agregado manualmente un ',
' con los siguientes datos: ',
'fullname:', new.fullname, ',',
'city:', new.city
));
END IF;
END;

CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_self_data_AFTER_UPDATE`
AFTER UPDATE ON `self_data`
FOR EACH ROW BEGIN
IF @app_audit IS NULL THEN
INSERT INTO binnacle (action) VALUES (
CONCAT(
'Una persona ha modificado manualmente un  de id ', old.id,
' con los siguientes datos: ',
'fullname:', old.fullname, ' => ', new.fullname, ',',
'city:', old.city, ' => ', new.city
));
END IF;
END;

CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_self_data_AFTER_DELETE`
AFTER DELETE ON `self_data`
FOR EACH ROW BEGIN
IF @app_audit IS NULL THEN
INSERT INTO binnacle (action) VALUES (
CONCAT(
'Una persona ha borrado manualmente un ',
' con los siguientes datos: ',
'fullname:', old.fullname
));
END IF;
END;
//////////////////////////////////
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_transfers_AFTER_INSERT`
AFTER INSERT ON `transfers`
FOR EACH ROW BEGIN
IF @app_audit IS NULL THEN
INSERT INTO binnacle (action) VALUES (
CONCAT(
'Una persona ha agregado manualmente un ',
' con los siguientes datos: ',
'account_number:', new.account_number, ',',
'bank:', new.bank
));
END IF;
END;

CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_transfers_AFTER_UPDATE`
AFTER UPDATE ON `transfers`
FOR EACH ROW BEGIN
IF @app_audit IS NULL THEN
INSERT INTO binnacle (action) VALUES (
CONCAT(
'Una persona ha modificado manualmente un  de id ', old.id,
' con los siguientes datos: ',
'account_number:', old.account_number, ' => ', new.account_number, ',',
'bank:', old.bank, ' => ', new.bank
));
END IF;
END;

CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_transfers_AFTER_DELETE`
AFTER DELETE ON `transfers`
FOR EACH ROW BEGIN
IF @app_audit IS NULL THEN
INSERT INTO binnacle (action) VALUES (
CONCAT(
'Una persona ha borrado manualmente un ',
' con los siguientes datos: ',
'account_number:', old.account_number
));
END IF;
END;
//////////////////////////////////
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_unknown_incomes_AFTER_INSERT`
AFTER INSERT ON `unknown_incomes`
FOR EACH ROW BEGIN
IF @app_audit IS NULL THEN
INSERT INTO binnacle (action) VALUES (
CONCAT(
'Una persona ha agregado manualmente un ',
' con los siguientes datos: ',
'date:', new.date, ',',
'price:', new.price
));
END IF;
END;

CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_unknown_incomes_AFTER_UPDATE`
AFTER UPDATE ON `unknown_incomes`
FOR EACH ROW BEGIN
IF @app_audit IS NULL THEN
INSERT INTO binnacle (action) VALUES (
CONCAT(
'Una persona ha modificado manualmente un  de id ', old.id,
' con los siguientes datos: ',
'price:', old.price, ' => ', new.price
));
END IF;
END;

CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_unknown_incomes_AFTER_DELETE`
AFTER DELETE ON `unknown_incomes`
FOR EACH ROW BEGIN
IF @app_audit IS NULL THEN
INSERT INTO binnacle (action) VALUES (
CONCAT(
'Una persona ha borrado manualmente un ',
' con los siguientes datos: ',
'price:', old.price
));
END IF;
END;
/////////////////////////////
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_unknown_incomes_generations_AFTER_INSERT`
AFTER INSERT ON `unknown_incomes_generations`
FOR EACH ROW BEGIN
IF @app_audit IS NULL THEN
INSERT INTO binnacle (action) VALUES (
CONCAT(
'Una persona ha agregado manualmente un ',
' con los siguientes datos: ',
'created_at:', new.created_at
));
END IF;
END;

CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_unknown_incomes_generations_AFTER_UPDATE`
AFTER UPDATE ON `unknown_incomes_generations`
FOR EACH ROW BEGIN
IF @app_audit IS NULL THEN
INSERT INTO binnacle (action) VALUES (
CONCAT(
'Una persona ha modificado manualmente un  de id ', old.id,
' con los siguientes datos: ',
'created_at:', old.created_at, ' => ', new.created_at
));
END IF;
END;

CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_unknown_incomes_generations_AFTER_DELETE`
AFTER DELETE ON `unknown_incomes_generations`
FOR EACH ROW BEGIN
IF @app_audit IS NULL THEN
INSERT INTO binnacle (action) VALUES (
CONCAT(
'Una persona ha borrado manualmente un ',
' con los siguientes datos: ',
'created_at:', old.created_at
));
END IF;
END;
/////////////////////////////