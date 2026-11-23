<?php
include_once 'SQL_model.php';

class NotificationModel extends SQLModel
{
    public function CreateNotification($data){
        $cedula = $data['cedula'];
        $message = $data['message'];
        $letter = $data['letter'];

        $sql = "INSERT
            INTO
            notifications
            (
                cedula,
                cedula_letter,
                message
            )
            VALUES
            (
                '$cedula',
                '$letter',
                '$message'
            )";

        return parent::DoQuery($sql);
    }

    public function GetPendingNotifications($cedula, $letter){
        return parent::GetRows("SELECT * FROM notifications WHERE cedula = '$cedula' AND cedula_letter = '$letter' AND viewed = 0", true);
    }

    public function GetNotificationsByCedula($cedula, $letter){
        $sql = "SELECT * FROM notifications WHERE cedula = '$cedula' AND cedula_letter = '$letter' ORDER BY viewed, created_at DESC";
        return parent::GetRows($sql, true);
    }

    public function SeeAllNotifications($cedula, $letter){
        $sql = "UPDATE notifications SET viewed = 1 WHERE cedula = '$cedula' AND cedula_letter = '$letter'";
        return parent::DoQuery($sql);
    }

    public function GetAccountsWithNotifications(){
        $sql = "SELECT 
            accounts.cedula,
            accounts.cedula_letter,
            accounts.id as account_id,
            CONCAT(accounts.names, ' ', accounts.surnames) as fullname,
            notifications.message,
            notifications.created_at,
            notifications.viewed
            FROM 
            notifications
            INNER JOIN accounts ON (accounts.cedula = notifications.cedula AND accounts.cedula_letter = notifications.cedula_letter)
            GROUP BY 
            accounts.cedula
            ORDER BY
            notifications.created_at DESC";

        return parent::GetRows($sql, true);
    }
}

$notification_model = new NotificationModel();
