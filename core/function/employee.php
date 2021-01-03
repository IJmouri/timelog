<?php

//qrcode generate function call ajax
if (isset($_POST['qrcode'])) {
    echo generate_qrcode($_POST['qrcode']);
}
if (isset($_POST['qrcode_exist'])) {
    echo check_qrcode_exist($_POST['qrcode_exist']);
}

if (isset($_POST['remove_id'])) {
    echo remove_employee($_POST['remove_id'], $_POST['user_id']);
}

function add_employee($employee_data)
{
    global $link;

    array_walk($employee_data, 'array_sanitize');

    $fields = '`' . implode('`, `', array_keys($employee_data)) . '`';
    $data = '\'' . implode('\', \'', $employee_data) . '\'';

    $query = mysqli_query($link, "INSERT INTO employees ($fields) VALUES ($data)");

    email($employee_data['email'], 'Activate your account', "Hello " . $employee_data['name'] . ",\n\n 
                                           You need to activate your account,use the link below:\n\n
                                           http://localhost/rankmylist/timelog/set_employee_password.php?email=
                                           " . $employee_data['email'] . "&activation_code=" . $employee_data['activation_code']);
}

//generate qrcode
function generate_qrcode($qrcode)
{
    include('../../phpqrcode/qrlib.php');
    // qr code generate
    $PNG_TEMP_DIR = dirname(__FILE__) . DIRECTORY_SEPARATOR . '../../image/qrcode' . DIRECTORY_SEPARATOR;

    //html PNG location prefix
    $PNG_WEB_DIR = '../../image/qrcode';
    $filename = $PNG_TEMP_DIR . 'test.png';
    if (trim($qrcode) == '')
        die('data cannot be empty! <a href="?">back</a>');

    // employee data
    $imagename =  $qrcode . "test" . md5($qrcode) . '.png';
    $filename = $PNG_TEMP_DIR . $imagename;
    $imagepath = "image/qrcode/" . $imagename;

    QRcode::png((int)($qrcode), $filename);

    $save_in_db = save_qrcode($imagepath, $qrcode); //save qrcode in db
    return $imagepath;
}

//save qrcode in db
function save_qrcode($image_path, $employee_id)
{
    $link = mysqli_connect('localhost', 'root', '', 'timelog');
    $query = mysqli_query($link, "UPDATE `employees` 
                                        SET `qrcode` = '$image_path' 
                                        WHERE employee_id = $employee_id");
}

function check_qrcode_exist($employee_id)
{
    $link = mysqli_connect('localhost', 'root', '', 'timelog');
    $query = mysqli_query($link, "SELECT `qrcode`
                                        FROM `employees` 
                                        WHERE employee_id = $employee_id");
    $data = mysqli_fetch_array($query);
    return $data[0];
}

//check if employee email exists or not
function employee_email_exists($email)
{
    global $link;

    $email = sanitize($email);
    $query = mysqli_query($link, "SELECT COUNT(`employee_id`) 
                                        FROM `employees` 
                                        WHERE `email` = '$email'");
    $data = mysqli_fetch_array($query);
    return $data[0];
}

//set employee password after adding employee
function set_password($email, $activation_code, $password)
{
    global $link;
    $email = mysqli_real_escape_string($link, $email);
    $activation_code = mysqli_real_escape_string($link, $activation_code);
    $query = mysqli_query($link, "SELECT COUNT(`employee_id`) 
                                        FROM `employees` 
                                        WHERE `email` = '$email' 
                                        AND `activation_code` = '$activation_code' 
                                        AND `active_status` = 0 
                                        AND `password` = ''");

    if ($query) {

        $match = mysqli_fetch_array($query);
        $password = sha1($password);
        if ($match[0] == 1) {
            $query = mysqli_query($link, "UPDATE `employees` 
                                                SET `password` = '$password',
                                                `active_status` = 1, 
                                                `recorded` = 1 , 
                                                `logintosite` = 1, `logintoapp` = 1  
                                                WHERE `email` = '$email'");
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

//check if employee active or not
function employee_active($email)
{
    global $link;

    $email = sanitize($email);
    $query = mysqli_query($link, "SELECT COUNT(`employee_id`) 
                                        FROM `employees` 
                                        WHERE `email` = '$email' 
                                        AND `active_status` = 1 
                                        AND `recorded` = 1 ");

    $data = mysqli_fetch_array($query);
    return $data[0];
}

function employee_logged_in()
{
    return (isset($_SESSION['employee_id']) ? true : false);
}

function employee_login($email, $password)
{
    global $link;

    $email = sanitize($email);
    $password = sha1($password);

    $query = mysqli_query($link, "SELECT `employee_id` 
                                        FROM `employees` 
                                        WHERE `email` = '$email' 
                                        AND `password` = '$password'");
    $data = mysqli_fetch_array($query);

    return ($data[0] != 0 ? $data[0] : false);
}

function employee_data($employee_id)
{
    $link = mysqli_connect('localhost', 'root', '', 'timelog');

    $data = array();
    $employee_id = (int)$employee_id;

    $func_num_args = func_num_args();
    $func_get_args = func_get_args();
    if ($func_num_args > 1) {

        unset($func_get_args[0]);

        $fields = '`' . implode('`, `', $func_get_args) . '`';
        $data = mysqli_fetch_assoc(mysqli_query($link, "SELECT $fields 
                                                             FROM `employees` 
                                                             WHERE `employee_id` = $employee_id"));

        return $data;
    }
}


function change_password_employee($employee_id, $password)
{
    global $link;

    $empoloyee_id = (int)$employee_id;
    $password = sha1($password);

    $query = mysqli_query($link, "UPDATE `employees` SET `password` = '$password' WHERE `employee_id` = '$employee_id' ");
}

function company_individual_employee_data($company_id, $employee_id)
{
    global $link;

    $employee_id = (int)$employee_id;

    $data = mysqli_fetch_assoc(mysqli_query($link, "SELECT * 
                                                             FROM `employees` 
                                                             WHERE `employee_id` = $employee_id
                                                             AND `company_id`  = $company_id"));

    return $data;
}

function get_employees($user_id, $start, $limit)
{
    global $link;

    $query = mysqli_query($link, "SELECT * 
                                        FROM `employees` 
                                        WHERE company_id = $user_id
                                        AND `recorded` != 2
                                        LIMIT $start, $limit");

    $data = mysqli_fetch_all($query, MYSQLI_ASSOC);
    return $data;
}
function get_all_employees($user_id)
{
    global $link;

    $query = mysqli_query($link, "SELECT * 
                                        FROM `employees` 
                                        WHERE company_id = $user_id
                                        AND `recorded` != 2");

    $data = mysqli_fetch_all($query, MYSQLI_ASSOC);
    return $data;
}
function get_number_of_rows_of_all_employees($user_id)
{
    // global $link;
    $link = mysqli_connect('localhost', 'root', '', 'timelog');

    $result_db = mysqli_query($link, "SELECT COUNT(employee_id) 
                                        FROM `employees` 
                                        WHERE company_id = $user_id
                                        AND `recorded` != 2");

    $row_db = mysqli_fetch_row($result_db);
    $total_records = $row_db[0];

    return $total_records;
}

function get_employee_record($employeeId, $start, $limit)
{
    global $link;

    if (empty($_SESSION['employee_id']) && empty($_SESSION['user_id'])) {
        protect_page();
    } else if (!empty($_SESSION['employee_id'])) {
        if ($_SESSION['employee_id'] != $employeeId) {
            header('Location: employeerecord.php?employeeId=' . $_SESSION['employee_id']);
        }
    }

    $query = mysqli_query($link, "SELECT * 
                                        FROM `attendance` 
                                        WHERE employee_id = $employeeId
                                        ORDER BY attendance_id DESC
                                        LIMIT $start, $limit");

    $data = mysqli_fetch_all($query, MYSQLI_ASSOC);
    return $data;
}

function get_number_of_rows_of_employee_records($employee_id)
{
    global $link;
    $result_db = mysqli_query($link, "SELECT COUNT(attendance_id) 
                                        FROM `attendance` 
                                        WHERE employee_id = $employee_id");

    $row_db = mysqli_fetch_row($result_db);
    $total_records = $row_db[0];

    return $total_records;
}

function remove_employee($employee_id, $company_id)
{
    $link = mysqli_connect('localhost', 'root', '', 'timelog');
    $match = mysqli_fetch_array(mysqli_query($link, "SELECT COUNT(`employee_id`) 
                                                            FROM `employees` 
                                                            WHERE `employee_id` = $employee_id"));
    if ($match[0] == 1) {
        $query = mysqli_query($link, "UPDATE `employees` 
                                            SET `active_status` = 0, `recorded` = 2 ,`logintosite` = 0, `logintoapp` = 0  
                                            WHERE `employee_id` = $employee_id");

        $total_existing_records = get_number_of_rows_of_all_employees($company_id);
        return $total_existing_records;
    } else {
        return "false";
    }
}

function get_all_employees_record($company_id, $start, $limit)
{
    global $link;

    // limit for pagination
    $query = mysqli_query($link, "SELECT employees.employee_id, employees.name , employees.title,
                                       attendance.date , attendance.check_in , attendance.check_out
                                       FROM attendance 
                                       LEFT JOIN employees 
                                       ON attendance.employee_id = employees.employee_id 
                                       WHERE employees.company_id = $company_id
                                       AND employees.recorded != 2
                                       ORDER BY attendance.attendance_id DESC 
                                       LIMIT $start, $limit");

    $result = mysqli_fetch_all($query, MYSQLI_ASSOC);

    return $result;
}

// get number of rows of all records for pagination
function get_number_of_rows_of_all_records($company_id)
{
    global $link;
    $result_db = mysqli_query($link, "SELECT COUNT(employees.employee_id) AS total_id
                                          FROM attendance 
                                          LEFT JOIN employees 
                                          ON attendance.employee_id = employees.employee_id 
                                          WHERE employees.company_id = $company_id
                                          AND employees.recorded != 2
                                          ORDER BY attendance.attendance_id DESC ");

    $row_db = mysqli_fetch_row($result_db);
    $total_records = $row_db[0];

    return $total_records;
}

