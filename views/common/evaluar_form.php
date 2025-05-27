<?php
if($_SESSION['neocaja_rol'] === 'student'){
    include_once 'student_form.php';
}
else{
    include_once 'personal_form.php';
}
?>