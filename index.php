<?php include 'core/init.php'; ?>
<?php include 'include/overall/header.php'; ?>

<!--    <h1>Home</h1>-->
<!--    <p>Just a template.</p>-->

<?php

if (isset($_SESSION['user_id'])) {
    echo '<h1 style="margin-top: 20px ; color: darkslateblue">Welcome to Timelog</h1>';
?>
    <div class="menu">
        <div class="add-employee">
            <h2> <a href="addemployee.php" class="text-color"><i class="fa fa-user-plus" aria-hidden="true"></i>Add Employees</a></h2>
        </div>
        <div class="add-employee">
            <h2><a href="all_employees_attendance_records.php" class="text-color"><i class="fa fa-info-circle" aria-hidden="true"></i>All Records</a></h2>
        </div>
        <div class="add-employee">
            <h2> <a href="monthly_company_employee_record.php" class="text-color"><i class="fa fa-calendar-check-o" aria-hidden="true"></i>Monthly Records</a></h2>
        </div>
        <div class="add-employee">
            <h2><a href="employee_manager.php" class="text-color"><i class="fa fa-users" aria-hidden="true"></i>Employees Information </a></h2>
        </div>
    </div>
  
<?php
    //    echo 'Logged in';
} else {
    echo 'Not Logged in';
}


?>
<?php include 'include/overall/footer.php'; ?>