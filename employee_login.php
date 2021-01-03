<?php
include 'core/init.php';
//logged_in_redirect();
if(empty($_POST) === false){
    $email = $_POST['email'];
    $password = $_POST['password'];

    if(empty($email) === true || empty($password) === true){
        $errors[] = 'You need to enter email and password';
    } else if ( employee_email_exists($email) != 1){
        $errors[] = 'We can not find the email. Have you registered?';
    }else if(employee_active($email) == 0){
        $errors[] = 'You have not activated your account';
    }else{

        $employee_login = employee_login($email, $password);

        if($employee_login === false){
            $errors[] = 'That email or password combination is incorrect';
        }else{
            $_SESSION['employee_id'] = $employee_login;

//            checkin($employee_login);
            header('Location: employeedashboard.php' );
            exit();
        }
    }
}else{
    $errors[] = 'No data received';
}
include 'include/overall/employee_header.php';
if(empty($errors) === false){
    echo '<h2> We tried to log you in,but...</h2>';
    echo output_errors($errors);

}
include 'include/overall/footer.php';
