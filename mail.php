<?php include 'core/init.php'; ?>
<?php
protect_page();
admin_protect();

?>

<?php include 'include/overall/header.php'; ?>

<h1>Mail</h1>
<?php

if (isset($_GET['success']) && empty($_GET['success'])) {
    echo 'Email has been sent';
} else {

    if (empty($_POST) === false) {
        if (empty($_POST['subject']) === true) {
            $errors[] = 'Subject is required';
        }
        if (empty($_POST['body']) === true) {
            $errors[] = 'Body is required';
        }
        if (empty($errors) === false) {
            echo output_errors($errors);
        } else {
            mail_users($_POST['subject'], $_POST['body']);
            header('Location: mail.php?success');
            exit();
        }
    }
?>
    <form action="" method="post">
        <ul>
            <li>
                Subject*<br>
                <input type="text" name="subject">
            </li>
            <li>
                Body*<br>
                <textarea name="body"></textarea>
            </li>
            <li>
                <input type="submit" class="gen-btn" name="send">
            </li>
        </ul>
    </form>
<?php
}
?>

<?php include 'include/overall/footer.php'; ?>