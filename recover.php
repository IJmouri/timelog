<?php include 'core/init.php'; ?>
    <!doctype html>
    <html>
<?php include 'include/head.php'; ?>

<body>

<?php
    include 'include/header.php';
 logged_in_redirect(); ?>

<?php //include 'include/overall/header.php'; ?>

    <div id="container">
<h1>Recover</h1>

<?php

if (isset($_GET['success']) === true && empty($_GET['success']) === true) {
    echo '<p>Thanks, we have emailed you</p>';
} else {

    $mode_allowed = array('username', 'password');
    if (isset($_GET['mode']) === true && in_array($_GET['mode'], $mode_allowed) === true) {
        if (isset($_POST['email']) === true && empty($_POST['email']) === false) {
            if (email_exists($_POST['email']) == 1) {
                recover($_GET['mode'], $_POST['email']);
                header('Location: recover.php?success');
            } else {
                echo '<p>We could not find that email address</p>';
            }
        }

?>

        <form action="" method="post">
            <ul>
                <li>
                    <input type="text" name="email" placeholder="Enter your email address">
                </li>
                <li>
                    <input type="submit" class="gen-btn" value="Recover">
                </li>
            </ul>
        </form>

        </div>
<?php
    } else {
        header('Location: index.php');
        exit();
    }
}


include 'include/overall/footer.php'; ?>