<header>
    <h1 class="logo">TimeLog</h1>

    <?php
    if (isset($_SESSION['user_id'])) {
        include 'include/menu.php';
    } else if (!isset($_SESSION['employee_id'])) {

    ?>
        <nav>
            <ul>
                <li><a href="index.php" class="menu">Home</a></li>
                <li><a href="attendance.php" class="menu">Attendance</a></li>
            </ul>
        </nav>
    <?php
    } else if (isset($_SESSION['employee_id'])) {
        include 'include/employee_menu.php';
    }
    ?>

    <div class="clear"></div>
</header>