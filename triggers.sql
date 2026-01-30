-- account_company_history
DELIMITER $$ 
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
END$$  
DELIMITER ;

DELIMITER $$ 
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
END$$  
DELIMITER ;

DELIMITER $$ 
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
END$$  
DELIMITER ;

-- account_payment_products
DELIMITER $$ 
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
END$$  
DELIMITER ;

DELIMITER $$ 
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
END$$  
DELIMITER ;

DELIMITER $$ 
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
END$$  
DELIMITER ;

-- account_payments
DELIMITER $$ 
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
END$$  
DELIMITER ;

DELIMITER $$ 
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
                ' response:', OLD.response, ' => ', NEW.response, ',',
                ' created_at:', OLD.created_at, ' => ', NEW.created_at

            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_account_payments_AFTER_DELETE`
AFTER DELETE ON `account_payments`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un pago de un cliente',
                ' con los siguientes datos: ',
                ' id:', OLD.id, ',',
                ' related_id:', OLD.related_id, ',',
                ' related_with:', OLD.related_with, ',',
                ' payment_method_type:', OLD.payment_method_type, ',',
                ' payment_method:', OLD.payment_method, ',',
                ' price:', OLD.price, ',',
                ' ref:', OLD.ref, ',',
                ' document:', OLD.document, ',',
                ' state:', OLD.state, ',',
                ' response:', OLD.response, ',',
                ' created_at:', OLD.created_at
            )
        );
    END IF;
END$$  
DELIMITER ;

-- accounts
DELIMITER $$ 
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
                ' is_student:', NEW.is_student, ',',
                ' scholarship:', NEW.scholarship, ',',
                ' scholarship_coverage:', NEW.scholarship_coverage, ',',
                ' created_at:', NEW.created_at
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
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
END$$  
DELIMITER ;

DELIMITER $$ 
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
                ' is_student:', OLD.is_student, ',',
                ' scholarship:', OLD.scholarship, ',',
                ' scholarship_coverage:', OLD.scholarship_coverage, ',',
                ' created_at:', OLD.created_at
            )
        );
    END IF;
END$$  
DELIMITER ;

-- admins
DELIMITER $$ 
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
END$$  
DELIMITER ;

DELIMITER $$ 
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
                ' active:', OLD.active, ' => ', NEW.active, ',',
                ' created_at:', OLD.created_at, ' => ', NEW.created_at, ','
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
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
END$$  
DELIMITER ;

-- banks
DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_banks_AFTER_INSERT`
AFTER INSERT ON `banks`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente un banco ',
                ' con los siguientes datos: ',
                ' id:', NEW.id, ',',
                ' name:', NEW.name, ',',
                ' active:', NEW.active, ',',
                ' created_at:', NEW.created_at
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
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
                ' id:', OLD.id, ' => ', NEW.id, ',',
                ' name:', OLD.name, ' => ', NEW.name, ',',
                ' active:', OLD.active, ' => ', NEW.active, ',',
                ' created_at:', OLD.created_at, ' => ', NEW.created_at
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_banks_AFTER_DELETE`
AFTER DELETE ON `banks`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un banco ',
                ' con los siguientes datos: ',
                ' id:', OLD.id, ',',
                ' name:', OLD.name, ',',
                ' active:', OLD.active, ',',
                ' created_at:', OLD.created_at
            )
        );
    END IF;
END$$  
DELIMITER ;

-- coin_history
DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_coin_history_AFTER_INSERT`
AFTER INSERT ON `coin_history`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente un historial de moneda ',
                ' con los siguientes datos: ',
                ' id:', NEW.id, ',',
                ' coin:', NEW.coin, ',',
                ' price:', NEW.price, ',',
                ' current:', NEW.current, ',',
                ' created_at:', NEW.created_at
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
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
                ' id:', OLD.id, ' => ', NEW.id, ',',
                ' coin:', OLD.coin, ' => ', NEW.coin, ',',
                ' price:', OLD.price, ' => ', NEW.price, ',',
                ' current:', OLD.current, ' => ', NEW.current, ',',
                ' created_at:', OLD.created_at, ' => ', NEW.created_at
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_coin_history_AFTER_DELETE`
AFTER DELETE ON `coin_history`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un historial de moneda ',
                ' con los siguientes datos: ',
                ' id:', OLD.id, ',',
                ' coin:', OLD.coin, ',',
                ' price:', OLD.price, ',',
                ' current:', OLD.current, ',',
                ' created_at:', OLD.created_at
            )
        );
    END IF;
END$$  
DELIMITER ;

-- coins
DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_coins_AFTER_INSERT`
AFTER INSERT ON `coins`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente una moneda ',
                ' con los siguientes datos: ',
                ' id:', NEW.id, ',',
                ' name:', NEW.name, ',',
                ' url:', NEW.url, ',',
                ' active:', NEW.active, ',',
                ' auto_update:', NEW.auto_update, ',',
                ' created_at:', NEW.created_at
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
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
                ' id:', OLD.id, ' => ', NEW.id, ',',
                ' name:', OLD.name, ' => ', NEW.name, ',',
                ' url:', OLD.url, ' => ', NEW.url, ',',
                ' active:', OLD.active, ' => ', NEW.active, ',',
                ' auto_update:', OLD.auto_update, ' => ', NEW.auto_update, ',',
                ' created_at:', OLD.created_at, ' => ', NEW.created_at, ','
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_coins_AFTER_DELETE`
AFTER DELETE ON `coins`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente una moneda ',
                ' con los siguientes datos: ',
                ' id:', OLD.id, ',',
                ' name:', OLD.name, ',',
                ' url:', OLD.url, ',',
                ' active:', OLD.active, ',',
                ' auto_update:', OLD.auto_update, ',',
                ' created_at:', OLD.created_at
            )
        );
    END IF;
END$$  
DELIMITER ;

-- companies
DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_companies_AFTER_INSERT`
AFTER INSERT ON `companies`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente una empresa ',
                ' con los siguientes datos: ',
                ' id:', NEW.id, ',',
                ' name:', NEW.name, ',',
                ' rif_letter:', NEW.rif_letter, ',',
                ' rif_number:', NEW.rif_number, ',',
                ' address:', NEW.address, ',',
                ' created_at:', NEW.created_at
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
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
                ' id:', OLD.id, ' => ', NEW.id, ',',
                ' name:', OLD.name, ' => ', NEW.name, ',',
                ' rif_letter:', OLD.rif_letter, ' => ', NEW.rif_letter, ',',
                ' rif_number:', OLD.rif_number, ' => ', NEW.rif_number, ',',
                ' address:', OLD.address, ' => ', NEW.address, ',',
                ' created_at:', OLD.created_at, ' => ', NEW.created_at
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_companies_AFTER_DELETE`
AFTER DELETE ON `companies`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente una empresa ',
                ' con los siguientes datos: ',
                ' id:', OLD.id, ',',
                ' name:', OLD.name, ',',
                ' rif_letter:', OLD.rif_letter, ',',
                ' rif_number:', OLD.rif_number, ',',
                ' address:', OLD.address, ',',
                ' created_at:', OLD.created_at
            )
        );
    END IF;
END$$  
DELIMITER ;

-- concepts
DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_concepts_AFTER_INSERT`
AFTER INSERT ON `concepts`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente un concepto ',
                ' con los siguientes datos: ',
                ' id:', NEW.id, ',',
                ' product:', NEW.product, ',',
                ' price:', NEW.price, ',',
                ' invoice:', NEW.invoice, ',',
                ' month:', NEW.month
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
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
                ' id:', OLD.id, ' => ', NEW.id, ',',
                ' product:', OLD.product, ' => ', NEW.product, ',',
                ' price:', OLD.price, ' => ', NEW.price, ',',
                ' invoice:', OLD.invoice, ' => ', NEW.invoice, ',',
                ' month:', OLD.month, ' => ', NEW.month
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_concepts_AFTER_DELETE`
AFTER DELETE ON `concepts`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un concepto ',
                ' con los siguientes datos: ',
                ' id:', OLD.id, ',',
                ' product:', OLD.product, ',',
                ' price:', OLD.price, ',',
                ' invoice:', OLD.invoice, ',',
                ' month:', OLD.month
            )
        );
    END IF;
END$$  
DELIMITER ;

-- global_vars
DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_global_vars_AFTER_INSERT`
AFTER INSERT ON `global_vars`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente una variable global ',
                ' con los siguientes datos: ',
                ' id:', NEW.id, ',',
                ' name:', NEW.name
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
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
                ' id:', OLD.id, ' => ', NEW.id, ',',
                ' name:', OLD.name, ' => ', NEW.name
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_global_vars_AFTER_DELETE`
AFTER DELETE ON `global_vars`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente una variable global ',
                ' con los siguientes datos: ',
                ' id:', OLD.id, ',',
                ' name:', OLD.name
            )
        );
    END IF;
END$$  
DELIMITER ;

-- global_vars_history
DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_global_vars_history_AFTER_INSERT`
AFTER INSERT ON `global_vars_history`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente un historial de variable global ',
                ' con los siguientes datos: ',
                ' id:', NEW.id, ',',
                ' global_var:', NEW.global_var, ',',
                ' value:', NEW.value, ',',
                ' current:', NEW.current, ',',
                ' created_at:', NEW.created_at
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
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
                ' id:', OLD.id, ' => ', NEW.id, ',',
                ' global_var:', OLD.global_var, ' => ', NEW.global_var, ',',
                ' value:', OLD.value, ' => ', NEW.value, ',',
                ' current:', OLD.current, ' => ', NEW.current, ',',
                ' created_at:', OLD.created_at, ' => ', NEW.created_at
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_global_vars_history_AFTER_DELETE`
AFTER DELETE ON `global_vars_history`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un historial de variable global ',
                ' con los siguientes datos: ',
                ' id:', OLD.id, ',',
                ' global_var:', OLD.global_var, ',',
                ' value:', OLD.value, ',',
                ' current:', OLD.current, ',',
                ' created_at:', OLD.created_at
            )
        );
    END IF;
END$$  
DELIMITER ;

-- invoice_payment_method
DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_invoice_payment_method_AFTER_INSERT`
AFTER INSERT ON `invoice_payment_method`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente un método de pago de una factura ',
                ' con los siguientes datos: ',
                ' id:', NEW.id, ',',
                ' invoice:', NEW.invoice, ',',
                ' type:', NEW.type, ',',
                ' price:', NEW.price, ',',
                ' coin:', NEW.coin, ',',
                ' bank:', NEW.bank, ',',
                ' sale_point:', NEW.sale_point, ',',
                ' document_number:', NEW.document_number, ',',
                ' igtf:', NEW.igtf
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_invoice_payment_method_AFTER_UPDATE`
AFTER UPDATE ON `invoice_payment_method`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente un método de pago de una factura de id ', OLD.id,
                ' con los siguientes datos: ',
                ' id:', OLD.id, ' => ', NEW.id, ',',
                ' invoice:', OLD.invoice, ' => ', NEW.invoice, ',',
                ' type:', OLD.type, ' => ', NEW.type, ',',
                ' price:', OLD.price, ' => ', NEW.price, ',',
                ' coin:', OLD.coin, ' => ', NEW.coin, ',',
                ' bank:', OLD.bank, ' => ', NEW.bank, ',',
                ' sale_point:', OLD.sale_point, ' => ', NEW.sale_point, ',',
                ' document_number:', OLD.document_number, ' => ', NEW.document_number, ',',
                ' igtf:', OLD.igtf, ' => ', NEW.igtf
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_invoice_payment_method_AFTER_DELETE`
AFTER DELETE ON `invoice_payment_method`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un método de pago de una factura',
                ' con los siguientes datos: ',
                ' id:', OLD.id, ',',
                ' invoice:', OLD.invoice, ',',
                ' type:', OLD.type, ',',
                ' price:', OLD.price, ',',
                ' coin:', OLD.coin, ',',
                ' bank:', OLD.bank, ',',
                ' sale_point:', OLD.sale_point, ',',
                ' document_number:', OLD.document_number, ',',
                ' igtf:', OLD.igtf
            )
        );
    END IF;
END$$  
DELIMITER ;

-- invoices
DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_invoices_AFTER_INSERT`
AFTER INSERT ON `invoices`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente una factura ',
                ' con los siguientes datos: ',
                ' id:', NEW.id, ',',
                ' invoice_number:', NEW.invoice_number, ',',
                ' control_number:', NEW.control_number, ',',
                ' account:', NEW.account, ',',
                ' observation:', NEW.observation, ',',
                ' active:', NEW.active, ',',
                ' period:', NEW.period, ',',
                ' created_at:', NEW.created_at, ',',
                ' rate_date:', NEW.rate_date
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_invoices_AFTER_UPDATE`
AFTER UPDATE ON `invoices`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente una factura de id ', OLD.id,
                ' con los siguientes datos: ',
                ' id:', OLD.id, ' => ', NEW.id, ',',
                ' invoice_number:', OLD.invoice_number, ' => ', NEW.invoice_number, ',',
                ' control_number:', OLD.control_number, ' => ', NEW.control_number, ',',
                ' account:', OLD.account, ' => ', NEW.account, ',',
                ' observation:', OLD.observation, ' => ', NEW.observation, ',',
                ' active:', OLD.active, ' => ', NEW.active, ',',
                ' period:', OLD.period, ' => ', NEW.period, ',',
                ' created_at:', OLD.created_at, ' => ', NEW.created_at, ',',
                ' rate_date:', OLD.rate_date, ' => ', NEW.rate_date
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_invoices_AFTER_DELETE`
AFTER DELETE ON `invoices`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente una factura ',
                ' con los siguientes datos: ',
                ' id:', OLD.id, ',',
                ' invoice_number:', OLD.invoice_number, ',',
                ' control_number:', OLD.control_number, ',',
                ' account:', OLD.account, ',',
                ' observation:', OLD.observation, ',',
                ' active:', OLD.active, ',',
                ' period:', OLD.period, ',',
                ' created_at:', OLD.created_at, ',',
                ' rate_date:', OLD.rate_date
            )
    );
    END IF;
END$$  
DELIMITER ;

-- mobile_payments
DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_mobile_payments_AFTER_INSERT`
AFTER INSERT ON `mobile_payments`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
        CONCAT(
            'Una persona ha agregado manualmente un pago móvil',
            ' con los siguientes datos: ',
            ' id:', NEW.id, ',',
            ' phone:', NEW.phone, ',',
            ' bank:', NEW.bank, ',',
            ' document_letter:', NEW.document_letter, ',',
            ' document_number:', NEW.document_number, ',',
            ' active:', NEW.active, ',',
            ' created_at:', NEW.created_at
        )
    );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_mobile_payments_AFTER_UPDATE`
AFTER UPDATE ON `mobile_payments`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente un pago móvil de id ', OLD.id,
                ' con los siguientes datos: ',
                ' id:', OLD.id, ' => ', NEW.id, ',',
                ' phone:', OLD.phone, ' => ', NEW.phone, ',',
                ' bank:', OLD.bank, ' => ', NEW.bank, ',',
                ' document_letter:', OLD.document_letter, ' => ', NEW.document_letter, ',',
                ' document_number:', OLD.document_number, ' => ', NEW.document_number, ',',
                ' active:', OLD.active, ' => ', NEW.active, ',',
                ' created_at:', OLD.created_at, ' => ', NEW.created_at
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_mobile_payments_AFTER_DELETE`
AFTER DELETE ON `mobile_payments`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un pago móvil',
                ' con los siguientes datos: ',
                ' id:', OLD.id, ',',
                ' phone:', OLD.phone, ',',
                ' bank:', OLD.bank, ',',
                ' document_letter:', OLD.document_letter, ',',
                ' document_number:', OLD.document_number, ',',
                ' active:', OLD.active, ',',
                ' created_at:', OLD.created_at
            )
        );
    END IF;
END$$  
DELIMITER ;

-- notifications
DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_notifications_AFTER_INSERT`
AFTER INSERT ON `notifications`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente una notificación ',
                ' con los siguientes datos: ',
                ' id:', NEW.id, ',',
                ' cedula:', NEW.cedula, ',',
                ' message:', NEW.message, ',',
                ' viewed:', NEW.viewed, ',',
                ' created_at:', NEW.created_at
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_notifications_AFTER_UPDATE`
AFTER UPDATE ON `notifications`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente una notificación de id ', OLD.id,
                ' con los siguientes datos: ',
                ' id:', OLD.id, ' => ', NEW.id, ',',
                ' cedula:', OLD.cedula, ' => ', NEW.cedula, ',',
                ' message:', OLD.message, ' => ', NEW.message, ',',
                ' viewed:', OLD.viewed, ' => ', NEW.viewed, ',',
                ' created_at:', OLD.created_at, ' => ', NEW.created_at
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_notifications_AFTER_DELETE`
AFTER DELETE ON `notifications`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente una notificación',
                ' con los siguientes datos: ',
                ' id:', OLD.id, ',',
                ' cedula:', OLD.cedula, ',',
                ' message:', OLD.message, ',',
                ' viewed:', OLD.viewed, ',',
                ' created_at:', OLD.created_at
            )
        );
    END IF;
END$$  
DELIMITER ;

-- payment_method_types
DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_payment_method_types_AFTER_INSERT`
AFTER INSERT ON `payment_method_types`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente un método de pago',
                ' con los siguientes datos: ',
                ' id:', NEW.id, ',',
                ' name:', NEW.name, ',',
                ' created_at:', NEW.created_at
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_payment_method_types_AFTER_UPDATE`
AFTER UPDATE ON `payment_method_types`
FOR EACH ROW 
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente un método de pago de id ', OLD.id,
                ' con los siguientes datos: ',
                ' id:', OLD.id, ' => ', NEW.id, ',',
                ' name:', OLD.name, ' => ', NEW.name, ',',
                ' created_at:', OLD.created_at, ' => ', NEW.created_at
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_payment_method_types_AFTER_DELETE`
AFTER DELETE ON `payment_method_types`
FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un método de pago',
                ' con los siguientes datos: ',
                ' id:', OLD.id, ',',
                ' name:', OLD.name, ',',
                ' created_at:', OLD.created_at
            )
        );
    END IF;
END$$  
DELIMITER ;

-- product_history
DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_product_history_AFTER_INSERT`
AFTER INSERT ON `product_history`
FOR EACH ROW 
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente un historial de producto',
                ' con los siguientes datos: ',
                ' id:', NEW.id, ',',
                ' product:', NEW.product, ',',
                ' price:', NEW.price, ',',
                ' current:', NEW.current, ',',
                ' created_at:', NEW.created_at
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_product_history_AFTER_UPDATE`
AFTER UPDATE ON `product_history`
FOR EACH ROW 
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente un historial de producto de id ', OLD.id,
                ' con los siguientes datos: ',
                ' id:', OLD.id, ' => ', NEW.id, ',',
                ' product:', OLD.product, ' => ', NEW.product, ',',
                ' price:', OLD.price, ' => ', NEW.price, ',',
                ' current:', OLD.current, ' => ', NEW.current, ',',
                ' created_at:', OLD.created_at, ' => ', NEW.created_at
            )
            );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_product_history_AFTER_DELETE`
AFTER DELETE ON `product_history`
FOR EACH ROW 
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un historial de producto',
                ' con los siguientes datos: ',
                ' id:', OLD.id, ',',
                ' product:', OLD.product, ',',
                ' price:', OLD.price, ',',
                ' current:', OLD.current, ',',
                ' created_at:', OLD.created_at
            )
        );
    END IF;
END$$  
DELIMITER ;

-- products
DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_products_AFTER_INSERT`
AFTER INSERT ON `products`
FOR EACH ROW 
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente un producto',
                ' con los siguientes datos: ',
                ' id:', NEW.id, ',',
                ' name:', NEW.name, ',',
                ' active:', NEW.active, ',',
                ' created_at:', NEW.created_at
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_products_AFTER_UPDATE`
AFTER UPDATE ON `products`
FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente un producto de id ', OLD.id,
                ' con los siguientes datos: ',
                ' id:', OLD.id, ' => ', NEW.id, ',',
                ' name:', OLD.name, ' => ', NEW.name, ',',
                ' active:', OLD.active, ' => ', NEW.active, ',',
                ' created_at:', OLD.created_at, ' => ', NEW.created_at
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_products_AFTER_DELETE`
AFTER DELETE ON `products`
FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un producto',
                ' con los siguientes datos: ',
                ' id:', OLD.id, ',',
                ' name:', OLD.name, ',',
                ' active:', OLD.active, ',',
                ' created_at:', OLD.created_at
            )
            );
    END IF;
END$$  
DELIMITER ;

-- roles
DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_roles_AFTER_INSERT`
AFTER INSERT ON `roles`
FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente un rol',
                ' con los siguientes datos: ',
                ' id:', NEW.id, ',',
                ' name:', NEW.name, ',',
                ' created_at:', NEW.created_at
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_roles_AFTER_UPDATE`
AFTER UPDATE ON `roles`
FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente un rol de id ', OLD.id,
                ' con los siguientes datos: ',
                ' id:', OLD.id, ' => ', NEW.id, ',',
                ' name:', OLD.name, ' => ', NEW.name, ',',
                ' created_at:', OLD.created_at, ' => ', NEW.created_at
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_roles_AFTER_DELETE`
AFTER DELETE ON `roles`
FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un rol',
                ' con los siguientes datos: ',
                ' id:', OLD.id, ',',
                ' name:', OLD.name, ',',
                ' created_at:', OLD.created_at
            )
        );
    END IF;
END$$  
DELIMITER ;

-- sale_points
DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_sale_points_AFTER_INSERT`
AFTER INSERT ON `sale_points`
FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente un punto de venta',
                ' con los siguientes datos: ',
                ' id:', NEW.id, ',',
                ' code:', NEW.code, ',',
                ' bank:', NEW.bank, ',',
                ' created_at:', NEW.created_at
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_sale_points_AFTER_UPDATE`
AFTER UPDATE ON `sale_points`
FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente un punto de venta de id ', OLD.id,
                ' con los siguientes datos: ',
                'id:', OLD.id, ' => ', NEW.id, ',',
                'code:', OLD.code, ' => ', NEW.code, ',',
                'bank:', OLD.bank, ' => ', NEW.bank, ',',
                'created_at:', OLD.created_at, ' => ', NEW.created_at
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_sale_points_AFTER_DELETE`
AFTER DELETE ON `sale_points`
FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un punto de venta',
                ' con los siguientes datos: ',
                ' id:', OLD.id, ',',
                ' code:', OLD.code, ',',
                ' bank:', OLD.bank, ',',
                ' created_at:', OLD.created_at
            )
        );
    END IF;
END$$  
DELIMITER ;

-- sholarships
DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_scholarships_AFTER_INSERT`
AFTER INSERT ON `scholarships`
FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente una beca',
                ' con los siguientes datos: ',
                ' id:', NEW.id, ',',
                ' name:', NEW.name, ',',
                ' created_at:', NEW.created_at
            )
            );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_scholarships_AFTER_UPDATE`
AFTER UPDATE ON `scholarships`
FOR EACH ROW BEGIN
        IF @app_audit IS NULL THEN
            INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente una beca de id ', OLD.id,
                ' con los siguientes datos: ',
                ' id:', OLD.id, ' => ', NEW.id, ',',
                ' name:', OLD.name, ' => ', NEW.name, ',',
                ' created_at:', OLD.created_at, ' => ', NEW.created_at
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_scholarships_AFTER_DELETE`
AFTER DELETE ON `scholarships`
FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente una beca',
                ' con los siguientes datos: ',
                ' id:', OLD.id, ',',
                ' name:', OLD.name, ',',
                ' created_at:', OLD.created_at
            )
        );
    END IF;
END$$  
DELIMITER ;

-- self_data
DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_self_data_AFTER_INSERT`
AFTER INSERT ON `self_data`
FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente una información propia ',
                ' con los siguientes datos: ',
                ' id:', NEW.id, ',',
                ' fullname:', NEW.fullname, ',',
                ' city:', NEW.city
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_self_data_AFTER_UPDATE`
AFTER UPDATE ON `self_data`
FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente una información propia de id ', OLD.id,
                ' con los siguientes datos: ',
                ' id:', OLD.id, ' => ', NEW.id, ',',
                ' fullname:', OLD.fullname, ' => ', NEW.fullname, ',',
                ' city:', OLD.city, ' => ', NEW.city
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_self_data_AFTER_DELETE`
AFTER DELETE ON `self_data`
FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente una información propia',
                ' con los siguientes datos: ',
                ' id:', OLD.id, ',',
                ' fullname:', OLD.fullname, ',',
                ' city:', OLD.city
            ));
    END IF;
END$$  
DELIMITER ;

-- transfers
DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_transfers_AFTER_INSERT`
AFTER INSERT ON `transfers`
FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente una cuenta de transferencias',
                ' con los siguientes datos: ',
                ' id:', NEW.id, ',',
                ' account_number:', NEW.account_number, ',',
                ' document_letter:', NEW.document_letter, ',',
                ' document_number:', NEW.document_number, ',',
                ' bank:', NEW.bank, ',',
                ' active:', NEW.active, ',',
                ' created_at:', NEW.created_at
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_transfers_AFTER_UPDATE`
AFTER UPDATE ON `transfers`
FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente una cuenta de transferencias de id ', OLD.id,
                ' con los siguientes datos: ',
                ' id:', OLD.id, ' => ', NEW.id, ',',
                ' account_number:', OLD.account_number, ' => ', NEW.account_number, ',',
                ' document_letter:', OLD.document_letter, ' => ', NEW.document_letter, ',',
                ' document_number:', OLD.document_number, ' => ', NEW.document_number, ',',
                ' bank:', OLD.bank, ' => ', NEW.bank, ',',
                ' active:', OLD.active, ' => ', NEW.active, ',',
                ' created_at:', OLD.created_at, ' => ', NEW.created_at
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_transfers_AFTER_DELETE`
AFTER DELETE ON `transfers`
FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente una cuenta de transferencias',
                ' con los siguientes datos: ',
                ' id:', OLD.id, ',',
                ' account_number:', OLD.account_number, ',',
                ' document_letter:', OLD.document_letter, ',',
                ' document_number:', OLD.document_number, ',',
                ' bank:', OLD.bank, ',',
                ' active:', OLD.active, ',',
                ' created_at:', OLD.created_at
            )
        );
    END IF;
END$$  
DELIMITER ;

-- unknown_incomes
DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_unknown_incomes_AFTER_INSERT`
AFTER INSERT ON `unknown_incomes`
FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente un ingreso desconocido',
                ' con los siguientes datos: ',
                ' id:', NEW.id, ',',
                ' date:', NEW.date, ',',
                ' price:', NEW.price, ',',
                ' ref:', NEW.ref, ',',
                ' description:', NEW.description, ',',
                ' account:', NEW.account, ',',
                ' generation:', NEW.generation
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_unknown_incomes_AFTER_UPDATE`
AFTER UPDATE ON `unknown_incomes`
FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente un ingreso desconocido de id ', OLD.id,
                ' con los siguientes datos: ',
                'id:', OLD.id, ' => ', NEW.id, ',',
                'date:', OLD.date, ' => ', NEW.date, ',',
                'price:', OLD.price, ' => ', NEW.price, ',',
                'ref:', OLD.ref, ' => ', NEW.ref, ',',
                'description:', OLD.description, ' => ', NEW.description, ',',
                'account:', OLD.account, ' => ', NEW.account, ',',
                'generation:', OLD.generation, ' => ', NEW.generation
            ));
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_unknown_incomes_AFTER_DELETE`
AFTER DELETE ON `unknown_incomes`
FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un ingreso desconocido',
                ' con los siguientes datos: ',
                ' id:', OLD.id, ',',
                ' date:', OLD.date, ',',
                ' price:', OLD.price, ',',
                ' ref:', OLD.ref, ',',
                ' description:', OLD.description, ',',
                ' account:', OLD.account, ',',
                ' generation:', OLD.generation
            )
        );
    END IF;
END$$  
DELIMITER ;

-- unknown_incomes_generations
DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_unknown_incomes_generations_AFTER_INSERT`
AFTER INSERT ON `unknown_incomes_generations`
FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente una generación de ingresos desconocidos',
                ' con los siguientes datos: ',
                ' id:', NEW.id, ',',
                ' created_at:', NEW.created_at
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_unknown_incomes_generations_AFTER_UPDATE`
AFTER UPDATE ON `unknown_incomes_generations`
FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente una generación de ingresos desconocidos de id ', OLD.id,
                ' con los siguientes datos: ',
                ' id:', OLD.id, ' => ', NEW.id, ',',
                ' created_at:', OLD.created_at, ' => ', NEW.created_at
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_unknown_incomes_generations_AFTER_DELETE`
AFTER DELETE ON `unknown_incomes_generations`
FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un ',
                ' con los siguientes datos: ',
                ' id:', OLD.id, ',',
                ' created_at:', OLD.created_at
            )
        );
    END IF;
END$$  
DELIMITER ;