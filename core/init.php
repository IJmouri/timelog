<?php
session_start();

require 'db/connect.php';
require 'function/users.php';
require 'function/general.php';
require 'function/employee.php';
require 'function/attendance.php';

$current_file = explode('/', $_SERVER['SCRIPT_NAME']);
$current_file = end($current_file);

if (logged_in() === true) {
    $session_user_id = $_SESSION['user_id'];
    $user_data = user_data($session_user_id, 'username', 'password', 'email','notification_email', 'firstname', 'lastname', 'active', 'password_recover', 'type', 'allow_email', 'profile');

    if (user_active($user_data['username']) != 1) {
        session_destroy();
        header('Location:index.php');
    }
    if ($current_file !== 'changepassword.php' && $current_file !== 'logout.php' && $user_data['password_recover'] == 1) {

        header('Location:changepassword.php?force');
    }
}

if (employee_logged_in() === true) {
    $session_employee_id = $_SESSION['employee_id'];
    $employee_data = employee_data($session_employee_id, 'password', 'email', 'name', 'active_status');
}

$errors = array();
