<?php include 'core/init.php'; ?>
<?php

include 'include/overall/employee_header.php';
protect_employee_page();
?>

<h1>Employee Dashboard</h1>
<div class="loading-div loader">
    <div class="loader-content">
        <img src="image/loader.gif" style="text-align: center" width="" />
    </div>
</div>
<br><button class="gen-btn btn" value="<?php echo $_SESSION['employee_id']; ?> "></button>

<div class="menu" style="margin-top: 20px;">
    <div class="add-employee">
        <h2><a href="employeerecord.php" class="text-color"><i class="fa fa-info-circle" aria-hidden="true"></i>All Records</a></h2>
    </div>
    <div class="add-employee">
        <h2> <a href="monthly_employee_record.php" class="text-color"><i class="fa fa-calendar-check-o" aria-hidden="true"></i>Monthly Records</a></h2>
    </div>
</div>

<?php include 'include/overall/footer.php'; ?>