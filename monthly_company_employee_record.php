<?php
include 'core/init.php';
protect_page();
include 'include/overall/header.php';

//required validation
if (empty($_POST) === false) {
    $required_fields = array('employee_id', 'start_date', 'end_date');
    foreach ($_POST as $key => $value) {
        if (empty($value) && in_array($key, $required_fields) === true) {
            $errors[] = 'Star marked fields are required';
            break 1;
        }
    }
}
?>
    <h1>Monthly Record</h1>

<?php
if (empty($_POST) === false && empty($errors) === true) {
    $employee_data = company_individual_employee_data($_SESSION['user_id'] , $_POST['employee_id']);

    $employee_id = $_POST['employee_id'];
    $start_date = strtotime($_POST['start_date']);
    $end_date = strtotime($_POST['end_date']);
    ?>
    <div class="record-data">
            <?php

            $employee_data = employee_data($_POST['employee_id'], 'name', 'title');

            echo "Employee Id: " . $employee_id . "<br>";
            echo "Name: " . $employee_data['name'] . "<br>";
            echo "Title: " . $employee_data['title'] . "<br>";
            echo "Start Date: " . $_POST['start_date'] . "<br>";
            echo "End Date: " . $_POST['end_date'] . "<br><br>";

            ?>
    </div>
    <?php
    $limit = 2;

    $total_records = get_number_of_rows_of_monthly_record($employee_id,$_POST['start_date'],$_POST['end_date']);
    $total_pages = ceil($total_records / $limit);

    include 'pagination.php';
}
else{
    if(empty($_POST)===false){
        echo '<h3 style="color: red">'.output_errors($errors).'</h3>';
    }
    ?>
        <div class="monthly-record-form">
            <form action="" method="post">
                <ul>
                    <li>Employee ID * :<br>
                        <select name="employee_id">
                            <?php
                            $employee_id_list = get_all_employees($_SESSION['user_id']);
                            foreach ($employee_id_list as $employee_id) {
                                echo "<option value=" . $employee_id['employee_id'] . ">" . $employee_id['employee_id'] . ". " . $employee_id['name'] . "</option>";
                            }
                            ?>
                        </select>
                    </li>
                    <li>
                        Start Date * :<br>
                        <input type="date" id="start_date" name="start_date" value="">
                    </li>
                    <li>
                        End Date * :<br>
                        <input type="date" id="end_date" name="end_date">
                    </li>
                    <li>
                        <input type="submit" class="gen-btn" value="Submit">
                    </li>

                </ul>
            </form>
        </div>
    <?php
    }
    ?>

    <script>
        $(document).ready(function() {
            const limit = <?php echo $limit; ?>;
            const employee_id = <?php echo $employee_id; ?>;
            const start_date = <?php echo "$start_date"; ?>;
            const end_date = <?php echo $end_date; ?>;
            const total_pages = <?php echo $total_pages; ?>;
            $("#target-content").load("ajax/monthly_company_employee_record_pagination.php?page=1" +
                                      "&&limit="+limit+"&&employee_id="+employee_id+"&start_date="+start_date+
                                      "&end_date="+end_date+"&total_pages="+total_pages);
            $(".page-link").click(function(e) {
                e.preventDefault();
                $('.loading-div').show();
                const id = $(this).attr("data-id");
                const select_id = $(this).parent().attr("id");
                console.log(select_id)
                $.ajax({
                    url: "ajax/monthly_company_employee_record_pagination.php",
                    type: "GET",
                    data: {
                        page: id,
                        limit: limit,
                        employee_id: employee_id,
                        start_date: start_date,
                        end_date: end_date,
                        total_pages: total_pages
                    },
                    cache: false,
                    success: function(dataResult) {
                        $('.loading-div').hide();
                        $("#target-content").html(dataResult);
                        $(".pageitem").removeClass("active");
                        $("#" + select_id).addClass("active");
                    }
                });
            });
        });
    </script>



<?php include 'include/overall/footer.php'; ?>