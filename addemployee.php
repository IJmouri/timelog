<?php
include 'core/init.php';

protect_page();

include 'include/overall/header.php';

if (empty($_POST) === false) {
    $required_fields = array('name', 'department', 'email', 'title');
    foreach ($_POST as $key => $value) {
        if (empty($value) && in_array($key, $required_fields) === true) {
            $errors[] = 'Star marked fields are required';
            break 1;
        }
    }
    if (empty($errors) === true) {
        if (employee_email_exists($_POST['email']) == 1) {
            $errors[] = 'Sorry, the email already taken';
        }
        if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false) {
            $errors[] = 'Valid email required';
        }
    }
}
?>

<h1>Add Employee</h1>

<?php
if (isset($_GET['success']) && empty($_GET['success'])) {
?>
    <div class="modal">
        <div class="modal-content" style="text-align: center">
            <div class="modal-bod">
                <img src="image/success.gif" width="100%" />
            </div>
            <h2 style="color: green;">Your work has been saved</h2>
        </div>
    </div>
    <h3 style="color: green;" class="text">Employee added successfully. Please check email to activate account.</h3>

<?php
} 
    if (empty($_POST) === false && empty($errors) === true) {

        $notification = ($_POST['notification'] == 'on') ? 1 : 0;

        $employee_data = array(
            'company_id' => $_SESSION['user_id'],
            'name' => $_POST['name'],
            'department' => $_POST['department'],
            'title' => $_POST['title'],
            'hourlyrate' => $_POST['hourlyrate'],
            '4digitpin' => $_POST['4digitpin'],
            'notification' => $notification,
            'email' => $_POST['email'],
            'activation_code' => md5($_POST['name'] + microtime())
        );
        //        print_r($employee_data);
        add_employee($employee_data);
        header('Location: addemployee.php?success');
        exit();
    } else {
        echo '<h3 style="color: red">' . output_errors($errors) . '</h3>';
    }
?>
    <div class="monthly-record-form">
        <form action="" method="post">
            <ul>
                <li>
                    Name * :<br>
                    <input type="text" name="name">
                </li>
                <li>
                    Department * :<br>
                    <input type="text" name="department">
                </li>
                <li>
                    Title *:<br>
                    <input type="text" name="title">
                </li>
                <li>
                    Hourly Rate:<br>
                    <input type="number" name="hourlyrate">
                </li>
                <li>
                    4 Digit Pin:<br>
                    <input type="number" name="4digitpin">
                </li>
                <li>
                    <input type="checkbox" class="checkmark" name="notification">Send eMail notification when employee checks-in or out?
                </li>
                <li>
                    Email * :<br>
                    <input type="text" name="email">
                </li>
                <li>
                    <input type="submit" class="gen-btn" value="Save">
                </li>
            </ul>
        </form>
    </div>
    <!--    <a href="addemployee.php"><button>Add Employee</button></a>-->
    <!--    --><?php
            
                ?>
<script>
    $('.modal').show();

    setTimeout(function() {
        $('.modal').fadeOut()
    }, 1000);
    setTimeout(function() {
        $('.text').fadeOut()
    }, 3000)
</script>
<?php include 'include/overall/footer.php'; ?>