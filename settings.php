<?php
include 'core/init.php';
protect_page();

include 'include/overall/header.php';

if (empty($_POST) === false) {
    $required_fields = array('firstname', 'lastname', 'notification_email');
    foreach ($_POST as $key => $value) {
        if (empty($value) && in_array($key, $required_fields) === true) {
            $errors[] = 'All the fields are required';
            break 1;
        }
    }

    if (empty($errors) === true) {
        if (filter_var($_POST['notification_email'], FILTER_VALIDATE_EMAIL) === false) {
            $errors[] = 'Valid email required';
        } else if (notification_email_exists($_POST['notification_email']) == 1 && $user_data['notification_email'] !== $_POST['notification_email']) {
            $errors[] = 'Sorry, the email already taken';
        }
    }
}


?>
<h2>Settings</h2>
<?php
if (isset($_GET['success']) && empty($_GET['success'])) {
    echo "<h2 style='color:green;'>Your profile updated successfully</h2>";
} else {
    if (empty($_POST) === false && empty($errors) === true) {
        $allow_email = ($_POST['allow_email'] == 'on') ? 1 : 0;
        $update_data = array(
            'firstname' => $_POST['firstname'],
            'lastname' => $_POST['lastname'],
            'notification_email' => $_POST['notification_email'],
            'allow_email' => $allow_email
        );
        update_user($session_user_id, $update_data);
        header('Location: settings.php?success');
    } else {
        echo "<h2 style='color:red'>".output_errors($errors)."</h2>";
    }
?>
    <form action="" method="post">
        <ul>
            <li>
                Firstname:<br>
                <input type="text" name="firstname" value="<?php echo $user_data['firstname'] ?>">
            </li>
            <li>
                Lastname:<br>
                <input type="text" name="lastname" value="<?php echo $user_data['lastname'] ?>">
            </li>
            <li>
                Notification Email:<br>
                <input type="text" name="notification_email" value="<?php echo $user_data['notification_email'] ?>">
            </li>
            <li>
                <input type="checkbox" name="allow_email" <?php if ($user_data['allow_email'] == 1) {
                                                                echo 'checked="checked"';
                                                            } ?>>Would you like to receive email from us?
            </li>
            <li>
                <input type="submit" class="gen-btn" value="Update">
            </li>
        </ul>
    </form>
<?php
}
?>

<?php include 'include/overall/footer.php'; ?>