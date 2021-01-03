<?php include 'core/init.php'; ?>
<!doctype html>
<html>
<?php include 'include/head.php'; ?>

<body>

    <?php
    include 'include/header.php';
    logged_in_redirect();
    //include 'include/overall/header.php';
    ?>

    <div id="container">
        <?php
        if (empty($_POST) === false) {
            $required_fields = array('username', 'password', 'password_again', 'firstname', 'lastname', 'email');
            foreach ($_POST as $key => $value) {
                if (empty($value) && in_array($key, $required_fields) === true) {
                    $errors[] = 'All the fields are required';
                    break 1;
                }
            }

            if (empty($errors) === true) {
                if (user_exists($_POST['username']) == 1) {
                    $errors[] = 'Sorry, the username already taken';
                }
                if (email_exists($_POST['email']) == 1) {
                    $errors[] = 'Sorry, the email already taken';
                }
                if (preg_match("/\\s/", $_POST['username']) == true) {
                    $errors[] = 'username should not have any space';
                }
                if (strlen($_POST['password']) < 6) {
                    $errors[] = 'Password must be at least 6 characters';
                }
                if ($_POST['password'] != $_POST['password_again']) {
                    $errors[] = 'Password do not match';
                }
                if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false) {
                    $errors[] = 'Valid email required';
                }
            }
        }

        ?>

        <h1>Register</h1>

        <?php
        if (isset($_GET['success']) && empty($_GET['success'])) {
            echo 'You have been registered successfully. Please check your email to activate your account.';
        } else {
            if (empty($_POST) === false && empty($errors) === true) {
                $register_data = array(
                    'username' => $_POST['username'],
                    'password' => $_POST['password'],
                    'firstname' => $_POST['firstname'],
                    'lastname' => $_POST['lastname'],
                    'email' => $_POST['email'],
                    'email_code' => md5($_POST['username'] + microtime())
                );
                register_user($register_data);
                header('Location: register.php?success');
                exit();
            } else {
                echo output_errors($errors);
            }
        ?>
            <div class="monthly-record-form">

                <form action="" method="post">
                    <ul>
                        <li>
                            Username:<br>
                            <input type="text" name="username">
                        </li>
                        <li>
                            Password:<br>
                            <input type="password" name="password">
                        </li>
                        <li>
                            Password again:<br>
                            <input type="password" name="password_again">
                        </li>
                        <li>
                            First name:<br>
                            <input type="text" name="firstname">
                        </li>
                        <li>
                            Last name:<br>
                            <input type="text" name="lastname">
                        </li>
                        <li>
                            Email:<br>
                            <input type="text" name="email">
                        </li>
                        <li>
                            <input type="submit" class="gen-btn" value="Register">
                        </li>
                    </ul>
                </form>
            </div>
    </div>
<?php
        }
?>
<?php include 'include/overall/footer.php'; ?>