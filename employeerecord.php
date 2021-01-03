<?php include 'core/init.php'; ?>

<?php

if (logged_in() === false && employee_logged_in() === false) {
    protect_page();
}

?>
<?php
include 'include/overall/employee_header.php';
?>
<?php
$employee_id = $_SESSION['employee_id'];
$limit = 3;
$total_records = get_number_of_rows_of_employee_records($employee_id);
$total_pages = ceil($total_records / $limit);

?>


<h1>Records</h1>

<?php include 'pagination.php' ?>

<script>
    $(document).ready(function() {
        const employee_id = <?php echo $employee_id; ?>;
        const limit = <?php echo $limit; ?>;
        const total_pages = <?php echo $total_pages; ?>;

        $("#target-content").load("ajax/employeerecord_pagination.php?page=1&&employee_id=" + employee_id + "&&limit=" + limit + "&total_pages=" + total_pages);

        $(".page-link").click(function(e) {
            e.preventDefault();
            $(".loading-div").show(); //show loading element

            const id = $(this).attr("data-id");
            const select_id = $(this).parent().attr("id");
            console.log(select_id)
            $.ajax({
                url: "ajax/employeerecord_pagination.php",
                type: "GET",
                data: {
                    page: id,
                    employee_id: employee_id,
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