-- account_company_history
DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_account_company_history_AFTER_INSERT`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_account_company_history_AFTER_INSERT`
AFTER INSERT ON `account_company_history`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente un historial de cuenta-empresa ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(NEW.id, 'NULL'), ',',
                ' account:', COALESCE(NEW.account, 'NULL'), ',',
                ' company:', COALESCE(NEW.company, 'NULL'), ',',
                ' current:', COALESCE(NEW.current, 'NULL'), ',',
                ' created_at:', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_account_company_history_AFTER_UPDATE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_account_company_history_AFTER_UPDATE`
AFTER UPDATE ON `account_company_history`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente un historial de cuenta-empresa de id ',
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                ' account:', COALESCE(OLD.account, 'NULL'), ' => ', COALESCE(NEW.account, 'NULL'), ',',
                ' company:', COALESCE(OLD.company, 'NULL'), ' => ', COALESCE(NEW.company, 'NULL'), ',',
                ' current:', COALESCE(OLD.current, 'NULL'), ' => ', COALESCE(NEW.current, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL'), ' => ', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_account_company_history_AFTER_DELETE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_account_company_history_AFTER_DELETE`
AFTER DELETE ON `account_company_history`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un historial de cuenta-empresa ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' account:', COALESCE(OLD.account, 'NULL'), ',',
                ' company:', COALESCE(OLD.company, 'NULL'), ',',
                ' current:', COALESCE(OLD.current, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

-- account_payment_products
DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_account_payment_products_AFTER_INSERT`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_account_payment_products_AFTER_INSERT`
AFTER INSERT ON `account_payment_products`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente un producto pagado del carrito de un cliente ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(NEW.id, 'NULL'), ',',
                ' product:', COALESCE(NEW.product, 'NULL'), ',',
                ' price:', COALESCE(NEW.price, 'NULL'), ',',
                ' payment:', COALESCE(NEW.payment, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_account_payment_products_AFTER_UPDATE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_account_payment_products_AFTER_UPDATE`
AFTER UPDATE ON `account_payment_products`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente un producto pagado del carrito de un cliente de id ',
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                ' product:', COALESCE(OLD.product, 'NULL'), ' => ', COALESCE(NEW.product, 'NULL'), ',',
                ' price:', COALESCE(OLD.price, 'NULL'), ' => ', COALESCE(NEW.price, 'NULL'), ',',
                ' payment:', COALESCE(OLD.payment, 'NULL'), ' => ', COALESCE(NEW.payment, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_account_payment_products_AFTER_DELETE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_account_payment_products_AFTER_DELETE`
AFTER DELETE ON `account_payment_products`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un producto pagado del carrito de un cliente ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' product:', COALESCE(OLD.product, 'NULL'), ',',
                ' price:', COALESCE(OLD.price, 'NULL'), ',',
                ' payment:', COALESCE(OLD.payment, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

-- account_payments
DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_account_payments_AFTER_INSERT`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_account_payments_AFTER_INSERT`
AFTER INSERT ON `account_payments`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente un pago de un cliente ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(NEW.id, 'NULL'), ',',
                ' related_id:', COALESCE(NEW.related_id, 'NULL'), ',',
                ' related_with:', COALESCE(NEW.related_with, 'NULL'), ',',
                ' payment_method_type:', COALESCE(NEW.payment_method_type, 'NULL'), ',',
                ' payment_method:', COALESCE(NEW.payment_method, 'NULL'), ',',
                ' price:', COALESCE(NEW.price, 'NULL'), ',',
                ' ref:', COALESCE(NEW.ref, 'NULL'), ',',
                ' document:', COALESCE(NEW.document, 'NULL'), ',',
                ' capture:', COALESCE(NEW.capture, 'NULL'), ',',
                ' state:', COALESCE(NEW.state, 'NULL'), ',',
                ' response:', COALESCE(NEW.response, 'NULL'), ',',
                ' created_at:', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_account_payments_AFTER_UPDATE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_account_payments_AFTER_UPDATE`
AFTER UPDATE ON `account_payments`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente un pago de un cliente de id ',
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                ' related_id:', COALESCE(OLD.related_id, 'NULL'), ' => ', COALESCE(NEW.related_id, 'NULL'), ',',
                ' related_with:', COALESCE(OLD.related_with, 'NULL'), ' => ', COALESCE(NEW.related_with, 'NULL'), ',',
                ' payment_method_type:', COALESCE(OLD.payment_method_type, 'NULL'), ' => ', COALESCE(NEW.payment_method_type, 'NULL'), ',',
                ' payment_method:', COALESCE(OLD.payment_method, 'NULL'), ' => ', COALESCE(NEW.payment_method, 'NULL'), ',',
                ' price:', COALESCE(OLD.price, 'NULL'), ' => ', COALESCE(NEW.price, 'NULL'), ',',
                ' ref:', COALESCE(OLD.ref, 'NULL'), ' => ', COALESCE(NEW.ref, 'NULL'), ',',
                ' document:', COALESCE(OLD.document, 'NULL'), ' => ', COALESCE(NEW.document, 'NULL'), ',',
                ' capture:', COALESCE(OLD.capture, 'NULL'), ' => ', COALESCE(NEW.capture, 'NULL'), ',',
                ' state:', COALESCE(OLD.state, 'NULL'), ' => ', COALESCE(NEW.state, 'NULL'), ',',
                ' response:', COALESCE(OLD.response, 'NULL'), ' => ', COALESCE(NEW.response, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL'), ' => ', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_account_payments_AFTER_DELETE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_account_payments_AFTER_DELETE`
AFTER DELETE ON `account_payments`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un pago de un cliente',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' related_id:', COALESCE(OLD.related_id, 'NULL'), ',',
                ' related_with:', COALESCE(OLD.related_with, 'NULL'), ',',
                ' payment_method_type:', COALESCE(OLD.payment_method_type, 'NULL'), ',',
                ' payment_method:', COALESCE(OLD.payment_method, 'NULL'), ',',
                ' price:', COALESCE(OLD.price, 'NULL'), ',',
                ' ref:', COALESCE(OLD.ref, 'NULL'), ',',
                ' document:', COALESCE(OLD.document, 'NULL'), ',',
                ' capture:', COALESCE(OLD.capture, 'NULL'), ',',
                ' state:', COALESCE(OLD.state, 'NULL'), ',',
                ' response:', COALESCE(OLD.response, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

-- accounts
DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_accounts_AFTER_INSERT`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_accounts_AFTER_INSERT`
AFTER INSERT ON `accounts`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente un cliente ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(NEW.id, 'NULL'), ',',
                ' cedula:', COALESCE(NEW.cedula, 'NULL'), ',',
                ' names:', COALESCE(NEW.names, 'NULL'), ',',
                ' surnames:', COALESCE(NEW.surnames, 'NULL'), ',',
                ' address:', COALESCE(NEW.address, 'NULL'), ',',
                ' phone:', COALESCE(NEW.phone, 'NULL'), ',',
                ' is_student:', COALESCE(NEW.is_student, 'NULL'), ',',
                ' scholarship:', COALESCE(NEW.scholarship, 'NULL'), ',',
                ' scholarship_coverage:', COALESCE(NEW.scholarship_coverage, 'NULL'), ',',
                ' created_at:', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_accounts_AFTER_UPDATE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_accounts_AFTER_UPDATE`
AFTER UPDATE ON `accounts`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente un cliente de id ',
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                ' cedula:', COALESCE(OLD.cedula, 'NULL'), ' => ', COALESCE(NEW.cedula, 'NULL'), ',',
                ' names:', COALESCE(OLD.names, 'NULL'), ' => ', COALESCE(NEW.names, 'NULL'), ',',
                ' surnames:', COALESCE(OLD.surnames, 'NULL'), ' => ', COALESCE(NEW.surnames, 'NULL'), ',',
                ' address:', COALESCE(OLD.address, 'NULL'), ' => ', COALESCE(NEW.address, 'NULL'), ',',
                ' phone:', COALESCE(OLD.phone, 'NULL'), ' => ', COALESCE(NEW.phone, 'NULL'), ',',
                ' is_student:', COALESCE(OLD.is_student, 'NULL'), ' => ', COALESCE(NEW.is_student, 'NULL'), ',',
                ' scholarship:', COALESCE(OLD.scholarship, 'NULL'), ' => ', COALESCE(NEW.scholarship, 'NULL'), ',',
                ' scholarship_coverage:', COALESCE(OLD.scholarship_coverage, 'NULL'), ' => ', COALESCE(NEW.scholarship_coverage, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL'), ' => ', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_accounts_AFTER_DELETE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_accounts_AFTER_DELETE`
AFTER DELETE ON `accounts`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un cliente ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' cedula:', COALESCE(OLD.cedula, 'NULL'), ',',
                ' names:', COALESCE(OLD.names, 'NULL'), ',',
                ' surnames:', COALESCE(OLD.surnames, 'NULL'), ',',
                ' address:', COALESCE(OLD.address, 'NULL'), ',',
                ' phone:', COALESCE(OLD.phone, 'NULL'), ',',
                ' is_student:', COALESCE(OLD.is_student, 'NULL'), ',',
                ' scholarship:', COALESCE(OLD.scholarship, 'NULL'), ',',
                ' scholarship_coverage:', COALESCE(OLD.scholarship_coverage, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

-- admins
DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_admins_AFTER_INSERT`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_admins_AFTER_INSERT`
AFTER INSERT ON `admins`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente un administrador ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(NEW.id, 'NULL'), ',',
                ' name:', COALESCE(NEW.name, 'NULL'), ',',
                ' cedula:', COALESCE(NEW.cedula, 'NULL'), ',',
                ' role:', COALESCE(NEW.role, 'NULL'), ',',
                ' active:', COALESCE(NEW.active, 'NULL'), ',',
                ' created_at:', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_admins_AFTER_UPDATE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_admins_AFTER_UPDATE`
AFTER UPDATE ON `admins`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente un administrador de id ',
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                ' name:', COALESCE(OLD.name, 'NULL'), ' => ', COALESCE(NEW.name, 'NULL'), ',',
                ' cedula:', COALESCE(OLD.cedula, 'NULL'), ' => ', COALESCE(NEW.cedula, 'NULL'), ',',
                ' role:', COALESCE(OLD.role, 'NULL'), ' => ', COALESCE(NEW.role, 'NULL'), ',',
                ' active:', COALESCE(OLD.active, 'NULL'), ' => ', COALESCE(NEW.active, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL'), ' => ', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_admins_AFTER_DELETE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_admins_AFTER_DELETE`
AFTER DELETE ON `admins`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un administrador ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' name:', COALESCE(OLD.name, 'NULL'), ',',
                ' cedula:', COALESCE(OLD.cedula, 'NULL'), ',',
                ' role:', COALESCE(OLD.role, 'NULL'), ',',
                ' active:', COALESCE(OLD.active, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

-- authentications
DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_authentications_AFTER_INSERT`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_authentications_AFTER_INSERT`
AFTER INSERT ON `authentications`
FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente una información de autenticación',
                ' con los siguientes datos: ',
                ' id:', COALESCE(NEW.id, 'NULL'), ',',
                ' cedula:', COALESCE(NEW.cedula, 'NULL'), ',',
                ' last_attempt:', COALESCE(NEW.last_attempt, 'NULL'), ',',
                ' login_attempts:', COALESCE(NEW.login_attempts, 'NULL'), ',',
                ' login_cooldown:', COALESCE(NEW.login_cooldown, 'NULL'), ',',
                ' last_connection:', COALESCE(NEW.last_connection, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_authentications_AFTER_UPDATE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_authentications_AFTER_UPDATE`
AFTER UPDATE ON `authentications`
FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente una información de autenticación de id ', 
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                ' cedula:', COALESCE(OLD.cedula, 'NULL'), ' => ', COALESCE(NEW.cedula, 'NULL'), ',',
                ' last_attempt:', COALESCE(OLD.last_attempt, 'NULL'), ' => ', COALESCE(NEW.last_attempt, 'NULL'), ',',
                ' login_attempts:', COALESCE(OLD.login_attempts, 'NULL'), ' => ', COALESCE(NEW.login_attempts, 'NULL'), ',',
                ' login_cooldown:', COALESCE(OLD.login_cooldown, 'NULL'), ' => ', COALESCE(NEW.login_cooldown, 'NULL'), ',',
                ' last_connection:', COALESCE(OLD.last_connection, 'NULL'), ' => ', COALESCE(NEW.last_connection, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_authentications_AFTER_DELETE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_authentications_AFTER_DELETE`
AFTER DELETE ON `authentications`
FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' cedula:', COALESCE(OLD.cedula, 'NULL'), ',',
                ' last_attempt:', COALESCE(OLD.last_attempt, 'NULL'), ',',
                ' login_attempts:', COALESCE(OLD.login_attempts, 'NULL'), ',',
                ' login_cooldown:', COALESCE(OLD.login_cooldown, 'NULL'), ',',
                ' last_connection:', COALESCE(OLD.last_connection, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

-- banks
DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_banks_AFTER_INSERT`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_banks_AFTER_INSERT`
AFTER INSERT ON `banks`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente un banco ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(NEW.id, 'NULL'), ',',
                ' name:', COALESCE(NEW.name, 'NULL'), ',',
                ' active:', COALESCE(NEW.active, 'NULL'), ',',
                ' created_at:', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_banks_AFTER_UPDATE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_banks_AFTER_UPDATE`
AFTER UPDATE ON `banks`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente un banco de id ',
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                ' name:', COALESCE(OLD.name, 'NULL'), ' => ', COALESCE(NEW.name, 'NULL'), ',',
                ' active:', COALESCE(OLD.active, 'NULL'), ' => ', COALESCE(NEW.active, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL'), ' => ', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_banks_AFTER_DELETE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_banks_AFTER_DELETE`
AFTER DELETE ON `banks`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un banco ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' name:', COALESCE(OLD.name, 'NULL'), ',',
                ' active:', COALESCE(OLD.active, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

-- coin_history
DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_coin_history_AFTER_INSERT`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_coin_history_AFTER_INSERT`
AFTER INSERT ON `coin_history`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente un historial de moneda ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(NEW.id, 'NULL'), ',',
                ' coin:', COALESCE(NEW.coin, 'NULL'), ',',
                ' price:', COALESCE(NEW.price, 'NULL'), ',',
                ' current:', COALESCE(NEW.current, 'NULL'), ',',
                ' created_at:', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_coin_history_AFTER_UPDATE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_coin_history_AFTER_UPDATE`
AFTER UPDATE ON `coin_history`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente un historial de moneda de id ',
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                ' coin:', COALESCE(OLD.coin, 'NULL'), ' => ', COALESCE(NEW.coin, 'NULL'), ',',
                ' price:', COALESCE(OLD.price, 'NULL'), ' => ', COALESCE(NEW.price, 'NULL'), ',',
                ' current:', COALESCE(OLD.current, 'NULL'), ' => ', COALESCE(NEW.current, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL'), ' => ', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_coin_history_AFTER_DELETE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_coin_history_AFTER_DELETE`
AFTER DELETE ON `coin_history`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un historial de moneda ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' coin:', COALESCE(OLD.coin, 'NULL'), ',',
                ' price:', COALESCE(OLD.price, 'NULL'), ',',
                ' current:', COALESCE(OLD.current, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

-- coins
DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_coins_AFTER_INSERT`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_coins_AFTER_INSERT`
AFTER INSERT ON `coins`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente una moneda ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(NEW.id, 'NULL'), ',',
                ' name:', COALESCE(NEW.name, 'NULL'), ',',
                ' url:', COALESCE(NEW.url, 'NULL'), ',',
                ' active:', COALESCE(NEW.active, 'NULL'), ',',
                ' auto_update:', COALESCE(NEW.auto_update, 'NULL'), ',',
                ' created_at:', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_coins_AFTER_UPDATE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_coins_AFTER_UPDATE`
AFTER UPDATE ON `coins`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente una moneda de id ',
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                ' name:', COALESCE(OLD.name, 'NULL'), ' => ', COALESCE(NEW.name, 'NULL'), ',',
                ' url:', COALESCE(OLD.url, 'NULL'), ' => ', COALESCE(NEW.url, 'NULL'), ',',
                ' active:', COALESCE(OLD.active, 'NULL'), ' => ', COALESCE(NEW.active, 'NULL'), ',',
                ' auto_update:', COALESCE(OLD.auto_update, 'NULL'), ' => ', COALESCE(NEW.auto_update, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL'), ' => ', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_coins_AFTER_DELETE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_coins_AFTER_DELETE`
AFTER DELETE ON `coins`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente una moneda ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' name:', COALESCE(OLD.name, 'NULL'), ',',
                ' url:', COALESCE(OLD.url, 'NULL'), ',',
                ' active:', COALESCE(OLD.active, 'NULL'), ',',
                ' auto_update:', COALESCE(OLD.auto_update, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

-- companies
DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_companies_AFTER_INSERT`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_companies_AFTER_INSERT`
AFTER INSERT ON `companies`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente una empresa ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(NEW.id, 'NULL'), ',',
                ' name:', COALESCE(NEW.name, 'NULL'), ',',
                ' rif_letter:', COALESCE(NEW.rif_letter, 'NULL'), ',',
                ' rif_number:', COALESCE(NEW.rif_number, 'NULL'), ',',
                ' address:', COALESCE(NEW.address, 'NULL'), ',',
                ' created_at:', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_companies_AFTER_UPDATE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_companies_AFTER_UPDATE`
AFTER UPDATE ON `companies`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente una empresa de id ',
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                ' name:', COALESCE(OLD.name, 'NULL'), ' => ', COALESCE(NEW.name, 'NULL'), ',',
                ' rif_letter:', COALESCE(OLD.rif_letter, 'NULL'), ' => ', COALESCE(NEW.rif_letter, 'NULL'), ',',
                ' rif_number:', COALESCE(OLD.rif_number, 'NULL'), ' => ', COALESCE(NEW.rif_number, 'NULL'), ',',
                ' address:', COALESCE(OLD.address, 'NULL'), ' => ', COALESCE(NEW.address, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL'), ' => ', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_companies_AFTER_DELETE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_companies_AFTER_DELETE`
AFTER DELETE ON `companies`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente una empresa ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' name:', COALESCE(OLD.name, 'NULL'), ',',
                ' rif_letter:', COALESCE(OLD.rif_letter, 'NULL'), ',',
                ' rif_number:', COALESCE(OLD.rif_number, 'NULL'), ',',
                ' address:', COALESCE(OLD.address, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

-- concepts
DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_concepts_AFTER_INSERT`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_concepts_AFTER_INSERT`
AFTER INSERT ON `concepts`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente un concepto ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(NEW.id, 'NULL'), ',',
                ' product:', COALESCE(NEW.product, 'NULL'), ',',
                ' price:', COALESCE(NEW.price, 'NULL'), ',',
                ' invoice:', COALESCE(NEW.invoice, 'NULL'), ',',
                ' month:', COALESCE(NEW.month, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_concepts_AFTER_UPDATE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_concepts_AFTER_UPDATE`
AFTER UPDATE ON `concepts`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente un concepto de id ',
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                ' product:', COALESCE(OLD.product, 'NULL'), ' => ', COALESCE(NEW.product, 'NULL'), ',',
                ' price:', COALESCE(OLD.price, 'NULL'), ' => ', COALESCE(NEW.price, 'NULL'), ',',
                ' invoice:', COALESCE(OLD.invoice, 'NULL'), ' => ', COALESCE(NEW.invoice, 'NULL'), ',',
                ' month:', COALESCE(OLD.month, 'NULL'), ' => ', COALESCE(NEW.month, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_concepts_AFTER_DELETE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_concepts_AFTER_DELETE`
AFTER DELETE ON `concepts`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un concepto ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' product:', COALESCE(OLD.product, 'NULL'), ',',
                ' price:', COALESCE(OLD.price, 'NULL'), ',',
                ' invoice:', COALESCE(OLD.invoice, 'NULL'), ',',
                ' month:', COALESCE(OLD.month, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

-- global_vars
DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_global_vars_AFTER_INSERT`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_global_vars_AFTER_INSERT`
AFTER INSERT ON `global_vars`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente una variable global ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(NEW.id, 'NULL'), ',',
                ' name:', COALESCE(NEW.name, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_global_vars_AFTER_UPDATE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_global_vars_AFTER_UPDATE`
AFTER UPDATE ON `global_vars`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente una variable global de id ',
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                ' name:', COALESCE(OLD.name, 'NULL'), ' => ', COALESCE(NEW.name, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_global_vars_AFTER_DELETE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_global_vars_AFTER_DELETE`
AFTER DELETE ON `global_vars`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente una variable global ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' name:', COALESCE(OLD.name, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

-- global_vars_history
DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_global_vars_history_AFTER_INSERT`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_global_vars_history_AFTER_INSERT`
AFTER INSERT ON `global_vars_history`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente un historial de variable global ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(NEW.id, 'NULL'), ',',
                ' global_var:', COALESCE(NEW.global_var, 'NULL'), ',',
                ' value:', COALESCE(NEW.value, 'NULL'), ',',
                ' current:', COALESCE(NEW.current, 'NULL'), ',',
                ' created_at:', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_global_vars_history_AFTER_UPDATE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_global_vars_history_AFTER_UPDATE`
AFTER UPDATE ON `global_vars_history`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente un historial de variable global de id ',
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                ' global_var:', COALESCE(OLD.global_var, 'NULL'), ' => ', COALESCE(NEW.global_var, 'NULL'), ',',
                ' value:', COALESCE(OLD.value, 'NULL'), ' => ', COALESCE(NEW.value, 'NULL'), ',',
                ' current:', COALESCE(OLD.current, 'NULL'), ' => ', COALESCE(NEW.current, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL'), ' => ', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_global_vars_history_AFTER_DELETE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_global_vars_history_AFTER_DELETE`
AFTER DELETE ON `global_vars_history`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un historial de variable global ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' global_var:', COALESCE(OLD.global_var, 'NULL'), ',',
                ' value:', COALESCE(OLD.value, 'NULL'), ',',
                ' current:', COALESCE(OLD.current, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

-- invoice_payment_method
DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_invoice_payment_method_AFTER_INSERT`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_invoice_payment_method_AFTER_INSERT`
AFTER INSERT ON `invoice_payment_method`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente un método de pago de una factura ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(NEW.id, 'NULL'), ',',
                ' invoice:', COALESCE(NEW.invoice, 'NULL'), ',',
                ' type:', COALESCE(NEW.type, 'NULL'), ',',
                ' price:', COALESCE(NEW.price, 'NULL'), ',',
                ' coin:', COALESCE(NEW.coin, 'NULL'), ',',
                ' bank:', COALESCE(NEW.bank, 'NULL'), ',',
                ' sale_point:', COALESCE(NEW.sale_point, 'NULL'), ',',
                ' document_number:', COALESCE(NEW.document_number, 'NULL'), ',',
                ' igtf:', COALESCE(NEW.igtf, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_invoice_payment_method_AFTER_UPDATE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_invoice_payment_method_AFTER_UPDATE`
AFTER UPDATE ON `invoice_payment_method`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente un método de pago de una factura de id ', 
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                ' invoice:', COALESCE(OLD.invoice, 'NULL'), ' => ', COALESCE(NEW.invoice, 'NULL'), ',',
                ' type:', COALESCE(OLD.type, 'NULL'), ' => ', COALESCE(NEW.type, 'NULL'), ',',
                ' price:', COALESCE(OLD.price, 'NULL'), ' => ', COALESCE(NEW.price, 'NULL'), ',',
                ' coin:', COALESCE(OLD.coin, 'NULL'), ' => ', COALESCE(NEW.coin, 'NULL'), ',',
                ' bank:', COALESCE(OLD.bank, 'NULL'), ' => ', COALESCE(NEW.bank, 'NULL'), ',',
                ' sale_point:', COALESCE(OLD.sale_point, 'NULL'), ' => ', COALESCE(NEW.sale_point, 'NULL'), ',',
                ' document_number:', COALESCE(OLD.document_number, 'NULL'), ' => ', COALESCE(NEW.document_number, 'NULL'), ',',
                ' igtf:', COALESCE(OLD.igtf, 'NULL'), ' => ', COALESCE(NEW.igtf, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_invoice_payment_method_AFTER_DELETE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_invoice_payment_method_AFTER_DELETE`
AFTER DELETE ON `invoice_payment_method`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un método de pago de una factura',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' invoice:', COALESCE(OLD.invoice, 'NULL'), ',',
                ' type:', COALESCE(OLD.type, 'NULL'), ',',
                ' price:', COALESCE(OLD.price, 'NULL'), ',',
                ' coin:', COALESCE(OLD.coin, 'NULL'), ',',
                ' bank:', COALESCE(OLD.bank, 'NULL'), ',',
                ' sale_point:', COALESCE(OLD.sale_point, 'NULL'), ',',
                ' document_number:', COALESCE(OLD.document_number, 'NULL'), ',',
                ' igtf:', COALESCE(OLD.igtf, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

-- invoices
DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_invoices_AFTER_INSERT`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_invoices_AFTER_INSERT`
AFTER INSERT ON `invoices`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente una factura ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(NEW.id, 'NULL'), ',',
                ' invoice_number:', COALESCE(NEW.invoice_number, 'NULL'), ',',
                ' control_number:', COALESCE(NEW.control_number, 'NULL'), ',',
                ' account:', COALESCE(NEW.account, 'NULL'), ',',
                ' observation:', COALESCE(NEW.observation, 'NULL'), ',',
                ' active:', COALESCE(NEW.active, 'NULL'), ',',
                ' period:', COALESCE(NEW.period, 'NULL'), ',',
                ' created_at:', COALESCE(NEW.created_at, 'NULL'), ',',
                ' rate_date:', COALESCE(NEW.rate_date, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_invoices_AFTER_UPDATE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_invoices_AFTER_UPDATE`
AFTER UPDATE ON `invoices`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente una factura de id ', 
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                ' invoice_number:', COALESCE(OLD.invoice_number, 'NULL'), ' => ', COALESCE(NEW.invoice_number, 'NULL'), ',',
                ' control_number:', COALESCE(OLD.control_number, 'NULL'), ' => ', COALESCE(NEW.control_number, 'NULL'), ',',
                ' account:', COALESCE(OLD.account, 'NULL'), ' => ', COALESCE(NEW.account, 'NULL'), ',',
                ' observation:', COALESCE(OLD.observation, 'NULL'), ' => ', COALESCE(NEW.observation, 'NULL'), ',',
                ' active:', COALESCE(OLD.active, 'NULL'), ' => ', COALESCE(NEW.active, 'NULL'), ',',
                ' period:', COALESCE(OLD.period, 'NULL'), ' => ', COALESCE(NEW.period, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL'), ' => ', COALESCE(NEW.created_at, 'NULL'), ',',
                ' rate_date:', COALESCE(OLD.rate_date, 'NULL'), ' => ', COALESCE(NEW.rate_date, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_invoices_AFTER_DELETE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_invoices_AFTER_DELETE`
AFTER DELETE ON `invoices`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente una factura ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' invoice_number:', COALESCE(OLD.invoice_number, 'NULL'), ',',
                ' control_number:', COALESCE(OLD.control_number, 'NULL'), ',',
                ' account:', COALESCE(OLD.account, 'NULL'), ',',
                ' observation:', COALESCE(OLD.observation, 'NULL'), ',',
                ' active:', COALESCE(OLD.active, 'NULL'), ',',
                ' period:', COALESCE(OLD.period, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL'), ',',
                ' rate_date:', COALESCE(OLD.rate_date, 'NULL')
            )
    );
    END IF;
END$$  
DELIMITER ;

-- login_history
DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_login_history_AFTER_INSERT`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_login_history_AFTER_INSERT`
AFTER INSERT ON `login_history`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente un historial de login ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(NEW.id, 'NULL'), ',',
                ' cedula:', COALESCE(NEW.cedula, 'NULL'), ',',
                ' success:', COALESCE(NEW.success, 'NULL'), ',',
                ' created_at:', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_login_history_AFTER_UPDATE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_login_history_AFTER_UPDATE`
AFTER UPDATE ON `login_history`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente un historial de login de id ', 
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                ' cedula:', COALESCE(OLD.cedula, 'NULL'), ' => ', COALESCE(NEW.cedula, 'NULL'), ',',
                ' success:', COALESCE(OLD.success, 'NULL'), ' => ', COALESCE(NEW.success, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL'), ' => ', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_login_history_AFTER_DELETE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_login_history_AFTER_DELETE`
AFTER DELETE ON `login_history`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente una factura ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' cedula:', COALESCE(OLD.cedula, 'NULL'), ',',
                ' success:', COALESCE(OLD.success, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL')
            )
    );
    END IF;
END$$  
DELIMITER ;

-- mobile_payments
DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_mobile_payments_AFTER_INSERT`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_mobile_payments_AFTER_INSERT`
AFTER INSERT ON `mobile_payments`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
        CONCAT(
            'Una persona ha agregado manualmente un pago móvil',
            ' con los siguientes datos: ',
            ' id:', COALESCE(NEW.id, 'NULL'), ',',
            ' phone:', COALESCE(NEW.phone, 'NULL'), ',',
            ' bank:', COALESCE(NEW.bank, 'NULL'), ',',
            ' document_letter:', COALESCE(NEW.document_letter, 'NULL'), ',',
            ' document_number:', COALESCE(NEW.document_number, 'NULL'), ',',
            ' active:', COALESCE(NEW.active, 'NULL'), ',',
            ' created_at:', COALESCE(NEW.created_at, 'NULL')
        )
    );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_mobile_payments_AFTER_UPDATE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_mobile_payments_AFTER_UPDATE`
AFTER UPDATE ON `mobile_payments`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente un pago móvil de id ', 
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                ' phone:', COALESCE(OLD.phone, 'NULL'), ' => ', COALESCE(NEW.phone, 'NULL'), ',',
                ' bank:', COALESCE(OLD.bank, 'NULL'), ' => ', COALESCE(NEW.bank, 'NULL'), ',',
                ' document_letter:', COALESCE(OLD.document_letter, 'NULL'), ' => ', COALESCE(NEW.document_letter, 'NULL'), ',',
                ' document_number:', COALESCE(OLD.document_number, 'NULL'), ' => ', COALESCE(NEW.document_number, 'NULL'), ',',
                ' active:', COALESCE(OLD.active, 'NULL'), ' => ', COALESCE(NEW.active, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL'), ' => ', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_mobile_payments_AFTER_DELETE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_mobile_payments_AFTER_DELETE`
AFTER DELETE ON `mobile_payments`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un pago móvil',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' phone:', COALESCE(OLD.phone, 'NULL'), ',',
                ' bank:', COALESCE(OLD.bank, 'NULL'), ',',
                ' document_letter:', COALESCE(OLD.document_letter, 'NULL'), ',',
                ' document_number:', COALESCE(OLD.document_number, 'NULL'), ',',
                ' active:', COALESCE(OLD.active, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

-- notifications
DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_notifications_AFTER_INSERT`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_notifications_AFTER_INSERT`
AFTER INSERT ON `notifications`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente una notificación ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(NEW.id, 'NULL'), ',',
                ' cedula:', COALESCE(NEW.cedula, 'NULL'), ',',
                ' message:', COALESCE(NEW.message, 'NULL'), ',',
                ' viewed:', COALESCE(NEW.viewed, 'NULL'), ',',
                ' created_at:', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_notifications_AFTER_UPDATE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_notifications_AFTER_UPDATE`
AFTER UPDATE ON `notifications`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente una notificación de id ', 
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                ' cedula:', COALESCE(OLD.cedula, 'NULL'), ' => ', COALESCE(NEW.cedula, 'NULL'), ',',
                ' message:', COALESCE(OLD.message, 'NULL'), ' => ', COALESCE(NEW.message, 'NULL'), ',',
                ' viewed:', COALESCE(OLD.viewed, 'NULL'), ' => ', COALESCE(NEW.viewed, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL'), ' => ', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_notifications_AFTER_DELETE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_notifications_AFTER_DELETE`
AFTER DELETE ON `notifications`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente una notificación',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' cedula:', COALESCE(OLD.cedula, 'NULL'), ',',
                ' message:', COALESCE(OLD.message, 'NULL'), ',',
                ' viewed:', COALESCE(OLD.viewed, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

-- payment_method_types
DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_payment_method_types_AFTER_INSERT`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_payment_method_types_AFTER_INSERT`
AFTER INSERT ON `payment_method_types`
FOR EACH ROW
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente un método de pago',
                ' con los siguientes datos: ',
                ' id:', COALESCE(NEW.id, 'NULL'), ',',
                ' name:', COALESCE(NEW.name, 'NULL'), ',',
                ' created_at:', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_payment_method_types_AFTER_UPDATE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_payment_method_types_AFTER_UPDATE`
AFTER UPDATE ON `payment_method_types`
FOR EACH ROW 
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente un método de pago de id ', 
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                ' name:', COALESCE(OLD.name, 'NULL'), ' => ', COALESCE(NEW.name, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL'), ' => ', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_payment_method_types_AFTER_DELETE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_payment_method_types_AFTER_DELETE`
AFTER DELETE ON `payment_method_types`
FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un método de pago',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' name:', COALESCE(OLD.name, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

-- product_history
DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_product_history_AFTER_INSERT`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_product_history_AFTER_INSERT`
AFTER INSERT ON `product_history`
FOR EACH ROW 
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente un historial de producto',
                ' con los siguientes datos: ',
                ' id:', COALESCE(NEW.id, 'NULL'), ',',
                ' product:', COALESCE(NEW.product, 'NULL'), ',',
                ' price:', COALESCE(NEW.price, 'NULL'), ',',
                ' current:', COALESCE(NEW.current, 'NULL'), ',',
                ' created_at:', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_product_history_AFTER_UPDATE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_product_history_AFTER_UPDATE`
AFTER UPDATE ON `product_history`
FOR EACH ROW 
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente un historial de producto de id ', 
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                ' product:', COALESCE(OLD.product, 'NULL'), ' => ', COALESCE(NEW.product, 'NULL'), ',',
                ' price:', COALESCE(OLD.price, 'NULL'), ' => ', COALESCE(NEW.price, 'NULL'), ',',
                ' current:', COALESCE(OLD.current, 'NULL'), ' => ', COALESCE(NEW.current, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL'), ' => ', COALESCE(NEW.created_at, 'NULL')
            )
            );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_product_history_AFTER_DELETE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_product_history_AFTER_DELETE`
AFTER DELETE ON `product_history`
FOR EACH ROW 
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un historial de producto',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' product:', COALESCE(OLD.product, 'NULL'), ',',
                ' price:', COALESCE(OLD.price, 'NULL'), ',',
                ' current:', COALESCE(OLD.current, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

-- products
DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_products_AFTER_INSERT`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_products_AFTER_INSERT`
AFTER INSERT ON `products`
FOR EACH ROW 
BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente un producto',
                ' con los siguientes datos: ',
                ' id:', COALESCE(NEW.id, 'NULL'), ',',
                ' name:', COALESCE(NEW.name, 'NULL'), ',',
                ' active:', COALESCE(NEW.active, 'NULL'), ',',
                ' created_at:', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_products_AFTER_UPDATE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_products_AFTER_UPDATE`
AFTER UPDATE ON `products`
FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente un producto de id ', 
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                ' name:', COALESCE(OLD.name, 'NULL'), ' => ', COALESCE(NEW.name, 'NULL'), ',',
                ' active:', COALESCE(OLD.active, 'NULL'), ' => ', COALESCE(NEW.active, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL'), ' => ', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_products_AFTER_DELETE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_products_AFTER_DELETE`
AFTER DELETE ON `products`
FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un producto',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' name:', COALESCE(OLD.name, 'NULL'), ',',
                ' active:', COALESCE(OLD.active, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL')
            )
            );
    END IF;
END$$  
DELIMITER ;

-- roles
DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_roles_AFTER_INSERT`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_roles_AFTER_INSERT`
AFTER INSERT ON `roles`
FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente un rol',
                ' con los siguientes datos: ',
                ' id:', COALESCE(NEW.id, 'NULL'), ',',
                ' name:', COALESCE(NEW.name, 'NULL'), ',',
                ' created_at:', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_roles_AFTER_UPDATE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_roles_AFTER_UPDATE`
AFTER UPDATE ON `roles`
FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente un rol de id ', 
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                ' name:', COALESCE(OLD.name, 'NULL'), ' => ', COALESCE(NEW.name, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL'), ' => ', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_roles_AFTER_DELETE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_roles_AFTER_DELETE`
AFTER DELETE ON `roles`
FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un rol',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' name:', COALESCE(OLD.name, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

-- sale_points
DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_sale_points_AFTER_INSERT`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_sale_points_AFTER_INSERT`
AFTER INSERT ON `sale_points`
FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente un punto de venta',
                ' con los siguientes datos: ',
                ' id:', COALESCE(NEW.id, 'NULL'), ',',
                ' code:', COALESCE(NEW.code, 'NULL'), ',',
                ' bank:', COALESCE(NEW.bank, 'NULL'), ',',
                ' created_at:', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_sale_points_AFTER_UPDATE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_sale_points_AFTER_UPDATE`
AFTER UPDATE ON `sale_points`
FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente un punto de venta de id ', 
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                'id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                'code:', COALESCE(OLD.code, 'NULL'), ' => ', COALESCE(NEW.code, 'NULL'), ',',
                'bank:', COALESCE(OLD.bank, 'NULL'), ' => ', COALESCE(NEW.bank, 'NULL'), ',',
                'created_at:', COALESCE(OLD.created_at, 'NULL'), ' => ', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_sale_points_AFTER_DELETE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_sale_points_AFTER_DELETE`
AFTER DELETE ON `sale_points`
FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un punto de venta',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' code:', COALESCE(OLD.code, 'NULL'), ',',
                ' bank:', COALESCE(OLD.bank, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

-- sholarships
DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_scholarships_AFTER_INSERT`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_scholarships_AFTER_INSERT`
AFTER INSERT ON `scholarships`
FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente una beca',
                ' con los siguientes datos: ',
                ' id:', COALESCE(NEW.id, 'NULL'), ',',
                ' name:', COALESCE(NEW.name, 'NULL'), ',',
                ' created_at:', COALESCE(NEW.created_at, 'NULL')
            )
            );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_scholarships_AFTER_UPDATE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_scholarships_AFTER_UPDATE`
AFTER UPDATE ON `scholarships`
FOR EACH ROW BEGIN
        IF @app_audit IS NULL THEN
            INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente una beca de id ', 
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                ' name:', COALESCE(OLD.name, 'NULL'), ' => ', COALESCE(NEW.name, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL'), ' => ', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_scholarships_AFTER_DELETE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_scholarships_AFTER_DELETE`
AFTER DELETE ON `scholarships`
FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente una beca',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' name:', COALESCE(OLD.name, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

-- self_data
DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_self_data_AFTER_INSERT`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_self_data_AFTER_INSERT`
AFTER INSERT ON `self_data`
FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente una información propia ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(NEW.id, 'NULL'), ',',
                ' fullname:', COALESCE(NEW.fullname, 'NULL'), ',',
                ' city:', COALESCE(NEW.city, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_self_data_AFTER_UPDATE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_self_data_AFTER_UPDATE`
AFTER UPDATE ON `self_data`
FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente una información propia de id ', 
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                ' fullname:', COALESCE(OLD.fullname, 'NULL'), ' => ', COALESCE(NEW.fullname, 'NULL'), ',',
                ' city:', COALESCE(OLD.city, 'NULL'), ' => ', COALESCE(NEW.city, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_self_data_AFTER_DELETE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_self_data_AFTER_DELETE`
AFTER DELETE ON `self_data`
FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente una información propia',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' fullname:', COALESCE(OLD.fullname, 'NULL'), ',',
                ' city:', COALESCE(OLD.city, 'NULL')
            ));
    END IF;
END$$  
DELIMITER ;

-- transfers
DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_transfers_AFTER_INSERT`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_transfers_AFTER_INSERT`
AFTER INSERT ON `transfers`
FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente una cuenta de transferencias',
                ' con los siguientes datos: ',
                ' id:', COALESCE(NEW.id, 'NULL'), ',',
                ' account_number:', COALESCE(NEW.account_number, 'NULL'), ',',
                ' document_letter:', COALESCE(NEW.document_letter, 'NULL'), ',',
                ' document_number:', COALESCE(NEW.document_number, 'NULL'), ',',
                ' bank:', COALESCE(NEW.bank, 'NULL'), ',',
                ' active:', COALESCE(NEW.active, 'NULL'), ',',
                ' created_at:', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_transfers_AFTER_UPDATE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_transfers_AFTER_UPDATE`
AFTER UPDATE ON `transfers`
FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente una cuenta de transferencias de id ', 
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                ' account_number:', COALESCE(OLD.account_number, 'NULL'), ' => ', COALESCE(NEW.account_number, 'NULL'), ',',
                ' document_letter:', COALESCE(OLD.document_letter, 'NULL'), ' => ', COALESCE(NEW.document_letter, 'NULL'), ',',
                ' document_number:', COALESCE(OLD.document_number, 'NULL'), ' => ', COALESCE(NEW.document_number, 'NULL'), ',',
                ' bank:', COALESCE(OLD.bank, 'NULL'), ' => ', COALESCE(NEW.bank, 'NULL'), ',',
                ' active:', COALESCE(OLD.active, 'NULL'), ' => ', COALESCE(NEW.active, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL'), ' => ', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_transfers_AFTER_DELETE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_transfers_AFTER_DELETE`
AFTER DELETE ON `transfers`
FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente una cuenta de transferencias',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' account_number:', COALESCE(OLD.account_number, 'NULL'), ',',
                ' document_letter:', COALESCE(OLD.document_letter, 'NULL'), ',',
                ' document_number:', COALESCE(OLD.document_number, 'NULL'), ',',
                ' bank:', COALESCE(OLD.bank, 'NULL'), ',',
                ' active:', COALESCE(OLD.active, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

-- unknown_incomes
DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_unknown_incomes_AFTER_INSERT`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_unknown_incomes_AFTER_INSERT`
AFTER INSERT ON `unknown_incomes`
FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente un ingreso desconocido',
                ' con los siguientes datos: ',
                ' id:', COALESCE(NEW.id, 'NULL'), ',',
                ' date:', COALESCE(NEW.date, 'NULL'), ',',
                ' price:', COALESCE(NEW.price, 'NULL'), ',',
                ' ref:', COALESCE(NEW.ref, 'NULL'), ',',
                ' description:', COALESCE(NEW.description, 'NULL'), ',',
                ' account:', COALESCE(NEW.account, 'NULL'), ',',
                ' generation:', COALESCE(NEW.generation, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_unknown_incomes_AFTER_UPDATE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_unknown_incomes_AFTER_UPDATE`
AFTER UPDATE ON `unknown_incomes`
FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente un ingreso desconocido de id ', 
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                'id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                'date:', COALESCE(OLD.date, 'NULL'), ' => ', COALESCE(NEW.date, 'NULL'), ',',
                'price:', COALESCE(OLD.price, 'NULL'), ' => ', COALESCE(NEW.price, 'NULL'), ',',
                'ref:', COALESCE(OLD.ref, 'NULL'), ' => ', COALESCE(NEW.ref, 'NULL'), ',',
                'description:', COALESCE(OLD.description, 'NULL'), ' => ', COALESCE(NEW.description, 'NULL'), ',',
                'account:', COALESCE(OLD.account, 'NULL'), ' => ', COALESCE(NEW.account, 'NULL'), ',',
                'generation:', COALESCE(OLD.generation, 'NULL'), ' => ', COALESCE(NEW.generation, 'NULL')
            ));
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_unknown_incomes_AFTER_DELETE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_unknown_incomes_AFTER_DELETE`
AFTER DELETE ON `unknown_incomes`
FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un ingreso desconocido',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' date:', COALESCE(OLD.date, 'NULL'), ',',
                ' price:', COALESCE(OLD.price, 'NULL'), ',',
                ' ref:', COALESCE(OLD.ref, 'NULL'), ',',
                ' description:', COALESCE(OLD.description, 'NULL'), ',',
                ' account:', COALESCE(OLD.account, 'NULL'), ',',
                ' generation:', COALESCE(OLD.generation, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

-- unknown_incomes_generations
DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_unknown_incomes_generations_AFTER_INSERT`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_unknown_incomes_generations_AFTER_INSERT`
AFTER INSERT ON `unknown_incomes_generations`
FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha agregado manualmente una generación de ingresos desconocidos',
                ' con los siguientes datos: ',
                ' id:', COALESCE(NEW.id, 'NULL'), ',',
                ' created_at:', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_unknown_incomes_generations_AFTER_UPDATE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_unknown_incomes_generations_AFTER_UPDATE`
AFTER UPDATE ON `unknown_incomes_generations`
FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha modificado manualmente una generación de ingresos desconocidos de id ', 
                COALESCE(OLD.id, 'NULL'),
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ' => ', COALESCE(NEW.id, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL'), ' => ', COALESCE(NEW.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `MANUAL_unknown_incomes_generations_AFTER_DELETE`;
CREATE DEFINER=`root`@`localhost` TRIGGER `MANUAL_unknown_incomes_generations_AFTER_DELETE`
AFTER DELETE ON `unknown_incomes_generations`
FOR EACH ROW BEGIN
    IF @app_audit IS NULL THEN
        INSERT INTO binnacle (action) VALUES (
            CONCAT(
                'Una persona ha borrado manualmente un ',
                ' con los siguientes datos: ',
                ' id:', COALESCE(OLD.id, 'NULL'), ',',
                ' created_at:', COALESCE(OLD.created_at, 'NULL')
            )
        );
    END IF;
END$$  
DELIMITER ;