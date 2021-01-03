<?php include 'core/init.php';
logged_in_redirect();
include 'include/overall/header.php';
if (isset($_GET['success']) === true && empty($_GET['success']) === true) {
    echo "<h2>Thanks, we have activated your account.</h2>";
    echo "Now, you are free to login";
} else if (isset($_GET['email'], $_GET['email_code']) === true) {
    $email = trim($_GET['email']);
    $email_code = trim($_GET['email_code']);

    if (email_exists($email) === false) {
        $errors[] = 'Oops, something went wrong, and we could not find your mail.';
    } else if (activate($email, $email_code) === false) {
        $errors[] = 'we had problems activating your account';
    }

    if (empty($errors) === false) {
        echo output_errors($errors);
    } else {
        header('Location: activate.php?success');
        exit();
    }
} else {
    header('Location: index.php');
    exit();
}

include 'include/overall/footer.php';
