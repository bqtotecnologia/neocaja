<?php
include_once 'SQL_model.php';

class NotificationModel extends SQLModel
{
    public function CreateNotification($data){
        $cedula = $data['cedula'];
        $message = $data['message'];

        $sql = "INSERT
            INTO
            notifications
            (
                cedula,
                message
            )
            VALUES
            (
                '$cedula',
                '$message'
            )";

        return parent::DoQuery($sql);
    }

    public function GetPendingNotifications($cedula){
        return parent::GetRows("SELECT * FROM notifications WHERE cedula = '$cedula' AND viewed = 0", true);
    }

    public function GetNotificationsByCedula($cedula){
        $sql = "SELECT * FROM notifications WHERE cedula = '$cedula' ORDER BY viewed, created_at DESC";
        return parent::GetRows($sql, true);
    }

    public function SeeAllNotifications($cedula){
        $sql = "UPDATE notifications SET viewed = 1 WHERE cedula = '$cedula'";
        return parent::DoQuery($sql);
    }
}

$notification_model = new NotificationModel();
