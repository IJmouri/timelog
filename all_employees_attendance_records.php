<?php include 'core/init.php'; ?>
<?php protect_page(); ?>
<?php

if (isset($_SESSION['user_id'])) {
    include 'include/overall/header.php';
} else if (isset($_SESSION['employee_id'])) {
    include 'include/overall/employee_header.php';
}

?>
<?php
$limit = 3;
$total_records = get_number_of_rows_of_all_records($_SESSION['user_id']);
$total_pages = ceil($total_records / $limit);
?>

<h2 style="text-align: center; color: darkslateblue">All Employees Records</h2>


<?php include 'pagination.php' ?>

    <script>
        $(document).ready(function() {
            const limit = <?php echo $limit; ?>;
            const total_pages = <?php echo $total_pages; ?>;
            $("#target-content").load("ajax/all_employees_attendance_records_pagination.php?page=1&&limit="+limit+"&total_pages="+total_pages);
            $(".page-link").click(function(e) {
                e.preventDefault();
                $(".loading-div").show(); //show loading element
                const id = $(this).attr("data-id");
                const select_id = $(this).parent().attr("id");
                console.log(select_id)
                $.ajax({
                    url: "ajax/all_employees_attendance_records_pagination.php",
                    type: "GET",
                    data: {
                        page: id,
                        limit: limit,
                        total_pages: total_pages
                    },
                    cache: false,
                    success: function(dataResult) {
                            $(".loading-div").hide();
                            $("#target-content").html(dataResult);
                            $(".pageitem").removeClass("active");
                            $("#" + select_id).addClass("active");
                    }
                });
            });
        });
    </script>



<?php include 'include/overall/footer.php'; ?>