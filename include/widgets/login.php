<div class="widget">
    <div class="inner company">
        <h2>Login as Company</h2>
        <form action="login.php" method="post">
            <ul id="login">
                <li>
                    Username:<br>
                    <input type="text" name="username">
                </li>
                <li>
                    Password:<br>
                    <input type="password" name="password">
                </li>
                <li>
                    <input type="submit" class="gen-btn" value="Log in">
                </li>
                <li>
                    <a href="register.php">Register</a>
                </li>
                <li>
                    Forgotten your <a href="recover.php?mode=username">username</a> or <a href="recover.php?mode=password">password</a>
                </li>
            </ul>
        </form>
    </div>
    <div class="inner employee">
        <h2>Login as Employee</h2>

        <form action="employee_login.php" method="post">
            <ul id="login">
                <li>
                    Email:<br>
                    <input type="text" name="email">
                </li>
                <li>
                    Password:<br>
                    <input type="password" name="password">
                </li>
                <li>
                    <input type="submit" class="gen-btn" value="Log in">
                </li>
            </ul>
        </form>

    </div>
</div>