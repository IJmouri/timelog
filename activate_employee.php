<?php include 'core/init.php';
//logged_in_redirect();
include 'include/overall/employee_header.php';
if (isset($_GET['success']) === true && empty($_GET['success']) === true) {
    echo "<h2>Thanks, we have activated your account.</h2>";


?>

<?php
} else if (isset($_GET['email'], $_GET['activation_code']) === true) {
    $email = trim($_GET['email']);
    $activation_code = trim($_GET['activation_code']);

    if (employee_email_exists($email) === false) {
        $errors[] = 'Oops, something went wrong, and we could not find your mail.';
    } else if (activate_employee($email, $activation_code) === false) {

        $errors[] = 'we had problems activating your account';
    }

    if (empty($errors) === false) {
        echo output_errors($errors);
    } else {
        header('Location: set_employee_password.php?email=' . $email);

        //        header('Location: activate_employee.php?success');
        exit();
    }
} else {
    //    header('Location: index.php');
    //    exit();
}

include 'include/overall/footer.php';
