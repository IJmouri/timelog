<?php
date_default_timezone_set('Asia/Dhaka');

$link = mysqli_connect('localhost', 'root', '', 'timelog');

if (isset($_POST['employee_exists'])) {
    echo employee_exists($_POST['employee_exists']);
}

if (isset($_POST['checkin'])) {
    echo checkin($_POST['checkin']);
}

if (isset($_POST['get_current_session'])) {
    echo get_current_session($_POST['get_current_session']);
}

if (isset($_POST['checkout']) && isset($_POST['employee_id'])) {
    echo checkout($_POST['checkout'],$_POST['employee_id']);
}

function employee_exists($employee_id)
{
    global $link;

    $query = mysqli_query($link, "SELECT COUNT(`employee_id`) FROM `employees` WHERE `employee_id` = $employee_id AND `active_status` = 1 AND `recorded` != 2 ");
    $data = mysqli_fetch_array($query);
    return $data[0];
}

function checkin($employee_id)
{
    global $link;

    $query = mysqli_query(
        $link,
        "INSERT INTO attendance 
                (`employee_id`,`date`,`check_in`) 
                VALUES ($employee_id,now(),now())"
    );

    $company_notification_email = get_company_notification_email($employee_id);    
    //sending email notification
    mail(
        $company_notification_email,
        'Checkin Notification',
        'Employee id:'.$employee_id.' just checkedin!',
        'From:mouri2018@gmail.com'
    );

    return "true";
}

function get_company_notification_email($employee_id)
{
    
    $link = mysqli_connect('localhost', 'root', '', 'timelog');

    $query = mysqli_query($link, "SELECT company.notification_email      
    FROM company
    JOIN employees
    ON company.user_id = employees.company_id 
    WHERE employees.employee_id = $employee_id");

    $result = mysqli_fetch_array($query);

    return $result[0];
}

function get_current_session($employee_id)
{
    global $link;

    $query = mysqli_query(
        $link,
        "SELECT attendance_id,check_in
               From `attendance`
               WHERE employee_id = $employee_id
               AND check_out IS NULL
               ORDER BY attendance_id DESC
               LIMIT 1"
    );

    $result = mysqli_fetch_assoc($query);
    if ($result != NULL) {
        $minimumSessionTime = strtotime(date("Y-m-d H:i:s")) - strtotime(date("H:i:s", strtotime($result['check_in'])));
        $result["minimumSessionTime"] = $minimumSessionTime;
    }

    return ($result != NULL ?  json_encode($result) : "false");
}

function checkout($session_employee_id, $employee_id)
{
    global $link;

    $query = mysqli_query($link, "UPDATE `attendance` SET `check_out` = now()   WHERE `attendance_id` = '$session_employee_id'");
    
    $company_notification_email = get_company_notification_email($employee_id);    
    //sending email notification
    mail(
        $company_notification_email,
        'Checkout Notification',
        'Employee id:'.$employee_id.' just checkedout!',
        'From:mouri2018@gmail.com'
    );
    return "true";
}

// attendance by day

function monthly_attedance($employee_id, $start_date, $end_date, $start, $limit)
{
    global $link;
    $query = mysqli_query($link, "SELECT date,SUM(TIMESTAMPDIFF(SECOND,check_in ,check_out)/3600) as 'Total Hours'
                                        FROM `attendance` 
                                        WHERE employee_id = $employee_id 
                                        AND date BETWEEN '$start_date' AND '$end_date'
                                        GROUP BY date
                                        LIMIT $start, $limit");

    $data = mysqli_fetch_all($query, MYSQLI_ASSOC);
    return $data;
}

function  get_number_of_rows_of_monthly_record($employee_id, $start_date, $end_date)
{
    global $link;
    $query = mysqli_query($link, "SELECT date,SUM(TIMESTAMPDIFF(SECOND,check_in ,check_out)/3600) as 'Total Hours'
                                        FROM `attendance` 
                                        WHERE employee_id = $employee_id 
                                        AND date BETWEEN '$start_date' AND '$end_date'
                                        GROUP BY date");

    $row_db = mysqli_num_rows($query);
    $total_records = $row_db;

    return $total_records;
}
