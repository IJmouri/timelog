<?php

function mail_users($subject, $body)
{
    global $link;
    $query = mysqli_query($link, "SELECT `email`,`firstname` FROM `company` WHERE `allow_email` = 1");
    while ($row = mysqli_fetch_assoc($query)) {
        $body = "Hello " . $row['firstname'] . ",\n\n" . $body;
        email($row['email'], $subject, $body);
    }
}

function is_admin($user_id)
{
    $user_id = (int)$user_id;
    global $link;
    $query = mysqli_query($link, "SELECT COUNT(`user_id`) FROM `company` WHERE `user_id` = '$user_id' AND `type` = '1'");
    $query = mysqli_fetch_array($query);
    return $query[0];
}

function recover($mode, $email)
{
    $email = sanitize($email);
    $mode = sanitize($mode);
    $user_data = user_data(user_id_from_email($email), 'user_id', 'firstname', 'username');

    if ($mode == 'username') {
        email($email, 'Your username', "Hello " . $user_data['firstname'] . "\n\nYour username is " . $user_data['username']);
    } else if ($mode == 'password') {
        $generated_password = substr(sha1(rand(999, 999999)), 0, 14);
        change_password($user_data['user_id'], $generated_password);

        update_user($user_data['user_id'], array('password_recover' => '1'));
        email($email, 'Your password', "Hello " . $user_data['firstname'] . "\n\nYour password is " . $generated_password);
    }
}

function update_user($user_id, $user_data)
{
    global $link;

    $update = array();
    array_walk($user_data, 'array_sanitize');

    foreach ($user_data as $field => $data) {
        $update[] = '`' . $field . '`=\'' . $data . '\'';
    }
    $data  = implode(', ', $update);
    $id = $_SESSION['user_id'];
    $query = mysqli_query($link, "UPDATE `company` SET $data WHERE `user_id` = '$user_id' ");
}

function activate($email, $email_code)
{
    global $link;

    $email = mysqli_real_escape_string($link, $email);
    $email_code = mysqli_real_escape_string($link, $email_code);

    $match = mysqli_fetch_array(mysqli_query($link, "SELECT COUNT(`user_id`) FROM `company` WHERE `email` = '$email' AND `email_code` = '$email_code' AND `active` = 0"));
    if ($match[0] == 1) {
        $query = mysqli_query($link, "UPDATE `company` SET `active` = 1 WHERE `email` = '$email'");
        return true;
    } else {
        return false;
    }
}

function change_password($user_id, $password)
{
    global $link;

    $user_id = (int)$user_id;
    $password = sha1($password);

    $query = mysqli_query($link, "UPDATE `company` SET `password` = '$password', `password_recover` = 0 WHERE `user_id` = '$user_id' ");
}

function register_user($register_data)
{
    global $link;

    array_walk($register_data, 'array_sanitize');
    $register_data['password'] = sha1($register_data['password']);

    $fields = '`' . implode('`, `', array_keys($register_data)) . '`';
    $data = '\'' . implode('\', \'', $register_data) . '\'';


    $query = mysqli_query($link, "INSERT INTO company ($fields) VALUES ($data)");
    email($register_data['email'], 'Activate your account', "Hello " . $register_data['firstname'] . ",\n\n You need to activate your account,use the link below:\n\nhttp://localhost/rankmylist/timelog/activate.php?email=" . $register_data['email'] . "&email_code=" . $register_data['email_code']);
}
function user_count()
{
    global $link;

    $query = mysqli_query($link, "SELECT COUNT(`user_id`) FROM `company` WHERE `active` = 1");
    $data = mysqli_fetch_array($query);
    return $data[0];
}

function user_data($user_id)
{
    global $link;

    $data = array();
    $user_id = (int)$user_id;

    $func_num_args = func_num_args();
    $func_get_args = func_get_args();
    if ($func_num_args > 1) {

        unset($func_get_args[0]);

        $fields = '`' . implode('`, `', $func_get_args) . '`';
        $data = mysqli_fetch_assoc(mysqli_query($link, "SELECT $fields FROM `company` WHERE `user_id` = $user_id"));

        return $data;
    }
}

function logged_in()
{
    return (isset($_SESSION['user_id']) ? true : false);
}

function user_exists($username)
{
    global $link;

    $username = sanitize($username);
    $query = mysqli_query($link, "SELECT COUNT(`user_id`) FROM `company` WHERE `username` = '$username'");
    $data = mysqli_fetch_array($query);
    return $data[0];
}
function email_exists($email)
{
    global $link;

    $email = sanitize($email);
    $query = mysqli_query($link, "SELECT COUNT(`user_id`) FROM `company` WHERE `email` = '$email'");
    $data = mysqli_fetch_array($query);
    return $data[0];
}
function notification_email_exists($email)
{
    global $link;

    $email = sanitize($email);
    $query = mysqli_query($link, "SELECT COUNT(`user_id`) FROM `company` WHERE `notification_email` = '$email'");
    $data = mysqli_fetch_array($query);
    return $data[0];
}

function user_active($username)
{
    global $link;

    $username = sanitize($username);
    $query = mysqli_query($link, "SELECT COUNT(`user_id`) FROM `company` WHERE `username` = '$username' AND `active` = 1");
    $data = mysqli_fetch_array($query);
    return $data[0];
}

function user_id_from_username($username)
{
    global $link;

    $username = sanitize($username);
    $query = mysqli_query($link, "SELECT `user_id` FROM `company` WHERE `username` = '$username'");
    $data = mysqli_fetch_array($query);
    return $data[0];
}
function user_id_from_email($email)
{
    global $link;

    $email = sanitize($email);
    $query = mysqli_query($link, "SELECT `user_id` FROM `company` WHERE `email` = '$email'");
    $data = mysqli_fetch_array($query);
    return $data[0];
}

function login($username, $password)
{
    global $link;

    $user_id = user_id_from_username($username);
    $username = sanitize($username);
    $password = sha1($password);

    $query = mysqli_query($link, "SELECT `user_id` FROM `company` WHERE `username` = '$username' AND `password` = '$password'");
    $data = mysqli_fetch_array($query);

    return ($data[0] != 0 ? $data[0] : false);
}
