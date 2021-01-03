
    <?php
    if (employee_logged_in() === true) {
        include 'include/widgets/employee_loggedin.php';
    } else {
        ?>
    <aside id="Just_A_Random_ID">
<?php
    include 'include/widgets/login.php';
    }
    include 'include/widgets/employee_count.php';

    ?>

</aside>