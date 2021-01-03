<?php

function email($to, $subject, $body)
{
    mail($to, $subject, $body, 'From: company@gmail.com');
}

function logged_in_redirect()
{
    if (logged_in() === true) {
        header('Location:index.php');
    }
}

function protect_page()
{
    if (logged_in() === false) {
        header('Location:protected.php');
    }
}
function protect_employee_page()
{
    if (employee_logged_in() === false) {
        header('Location:protected.php');
    }
}
function admin_protect()
{
    global $user_data;
    if (is_admin($_SESSION['user_id']) == 1) {
        header('Location:index.php');
    }
}

function array_sanitize($item)
{
    global $link;

    $item = htmlentities(strip_tags(mysqli_real_escape_string($link, $item)));
}
function sanitize($data)
{
    global $link;

    return htmlentities(strip_tags(mysqli_real_escape_string($link, $data)));
}

function output_errors($errors)
{
    return '<ul><li>' . implode('</li><li>', $errors) . '</li></ul>';
}
date_default_timezone_set('Asia/Dhaka');


function convertTime($time)
{
    $convertedTime = strtotime(date("H:i:s", strtotime($time)));

    return $convertedTime;
}

function timeDifference($start_time, $end_time)
{
    if ($end_time < $start_time) {
        $end_time += 24 * 60 * 60;
    }

    $time = $end_time - $start_time;
    $time = number_format(abs($time / 3600),2);

    return $time;
}
