<?php include 'core/init.php'; ?>
<?php protect_page(); ?>

<?php

if (empty($_POST) === false) {
    $required_fields = array('current_password', 'password', 'password_again');
    foreach ($_POST as $key => $value) {
        if (empty($value) && in_array($key, $required_fields) === true) {
            $errors[] = 'All the fields are required';
            break 1;
        }
    }

    if (empty($errors) === true) {
        if (sha1($_POST['current_password']) === $user_data['password']) {
            if (trim($_POST['password']) != trim($_POST['password_again'])) {
                $errors[] = 'Password do not match';
            } else if (strlen($_POST['password']) < 6) {
                $errors[] = 'Password must be at least 6 characters';
            }
        } else {
            $errors[] = 'Current Password is incorrect';
        }
    }
}

?>
<?php include 'include/overall/header.php'; ?>

<h1>Change</h1>
<?php
if (isset($_GET['success']) && empty($_GET['success'])) {
    echo 'You have been changed password successfully';
} else {
    if (isset($_GET['force']) === true && empty($_GET['force']) === true) {
        echo '<p>You must change your password</p>';
    }

    if (empty($_POST) === false && empty($errors) === true) {
        change_password($session_user_id, $_POST['password']);
        header('Location: changepassword.php?success');
    } else {
        echo output_errors($errors);
    }

?>
    <form action="" method="post">
        <ul>
            <li>
                Current Password*:<br>
                <input type="password" name="current_password" />
            </li>
            <li>
                New Password*:<br>
                <input type="password" name="password" />
            </li>
            <li>
                New Password Again*:<br>
                <input type="password" name="password_again" />
            </li>
            <li>
                <input type="submit" class="gen-btn" value="Change">
            </li>
        </ul>
    </form>
<?php
}
?>

<?php include 'include/overall/footer.php'; ?>