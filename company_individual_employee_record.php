<?php include 'core/init.php'; ?>

<?php
if (logged_in() === false && employee_logged_in() === false) {
    protect_page();
}
?>

<?php
$limit = 3;
$total_records = get_number_of_rows_of_employee_records($_GET['employeeId']);
$total_pages = ceil($total_records / $limit);

?>

<?php include 'include/overall/header.php'; ?>
    <h1>Attendance Records</h1>

<?php if (isset($_SESSION['user_id'])) {

    $employee_data = company_individual_employee_data($_SESSION['user_id'] , $_GET['employeeId']);

    if(!empty($employee_data) ){
        $employee_id = $_GET['employeeId'];
        echo "Employee Id: " . $employee_id . "<br>";
        echo "Name: " . $employee_data['name'] . "<br>";
        echo "Department: " . $employee_data['department'] . "<br>";
        echo "Title: " . $employee_data['title'] . "<br><br>";
    }else{
        header('Location: employee_manager.php');
    }
}

?>

<?php include 'pagination.php' ?>

    <script>
        $(document).ready(function() {
            const employee_id = <?php echo $employee_id; ?>;
            const limit = <?php echo $limit; ?>;
            const total_pages = <?php echo $total_pages; ?>;

            $("#target-content").load("ajax/company_individual_employee_record_pagination.php?page=1&&employee_id="+employee_id+"&&limit="+limit+"&total_pages="+total_pages);

            $(".page-link").click(function(e) {
                e.preventDefault();
                $('.loading-div').show();
                const id = $(this).attr("data-id");
                const select_id = $(this).parent().attr("id");
                console.log(select_id)
                $.ajax({
                    url: "ajax/company_individual_employee_record_pagination.php",
                    type: "GET",
                    data: {
                        page: id,
                        employee_id: employee_id,
                        limit: limit,
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