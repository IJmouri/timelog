<?php include 'core/init.php';
//logged_in_redirect();
include 'include/overall/employee_header.php';
if (isset($_GET['saved']) === false) {
    if (employee_active($_GET['email']) == 1) {
        echo "<h4>Please, set your password!</h4>";
?>
        <form action="" method="post">
            <ul>
                <li>
                    Password*:<br>
                    <input type="password" name="password" />
                </li>
                <li>
                    Password Again*:<br>
                    <input type="password" name="password_again" />
                </li>
                <li>
                    <input type="submit" value="Save">
                </li>
            </ul>
        </form>
    <?php
    }
}
if (empty($_POST) === false) {
    $required_fields = array('password', 'password_again');
    foreach ($_POST as $key => $value) {
        if (empty($value) && in_array($key, $required_fields) === true) {
            $errors[] = 'All the fields are required';
            break 1;
        }
    }

    if (empty($errors) === true) {
        $email = $_GET['email'];
        $activation_code = $_GET['activation_code'];

        if (strlen($_POST['password']) < 6) {
            $errors[] = 'Password must be at least 6 characters';
        }
        if ($_POST['password'] != $_POST['password_again']) {
            $errors[] = 'Password do not match';
        }
        if (employee_email_exists($email) === false) {
            $errors[] = 'Oops, something went wrong, and we could not find your mail.';
        }
    }
}

if (isset($_GET['saved']) && empty($_GET['saved'])) {
    echo "<h2>Thanks, we have activated your account.</h2>";

    echo 'Password saved successfully. Now you are free to login.';
} else {
    if (empty($_POST) === false && empty($errors) === true) {

        $email = $_GET['email'];
        $activation_code = $_GET['activation_code'];
        $password = $_POST['password'];

        if (set_password($email, $activation_code, $password) === true) {
            header('Location: set_employee_password.php?saved');
        } else {
            $errors[] = 'we had problems activating your account';
        }
        exit();
    } else {
        echo output_errors($errors);
    }
    ?>
<?php
}
?>