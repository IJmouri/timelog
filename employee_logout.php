<?php
include 'core/init.php';

//session_start();

if (employee_logged_in() === true) {
    $current_session = get_current_session($_SESSION['employee_id']);
    if ($current_session !== false) {
        //        checkout($current_session);


    };
}
session_destroy();
header('Location: index.php');
