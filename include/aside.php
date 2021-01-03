<?php
if (logged_in() === true) {
    include 'include/widgets/loggedin.php';
} else {
?>
    <aside id="Just_A_Random_ID">

    <?php include 'include/widgets/login.php';
}
//include 'include/widgets/user_count.php';

    ?>

    </aside>