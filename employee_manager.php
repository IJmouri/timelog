<?php include 'core/init.php'; ?>
<?php protect_page(); ?>
<?php include 'include/overall/header.php'; ?>

<?php

$limit = 2;
$total_records = get_number_of_rows_of_all_employees($_SESSION['user_id']);
$total_pages = ceil($total_records / $limit);

?>

<h1 style="text-align: center; color: darkslateblue">Employee Details</h1>
<?php include 'pagination.php'; ?>

<script>
    $(document).ready(function() {
        const limit = <?php echo $limit; ?>;
        const total_records = <?php echo $total_records; ?>;
        const total_pages = <?php echo $total_pages; ?>;
        $("#target-content").load("ajax/employee_manager_pagination.php?page=1&&limit=" + limit + "&total_records=" + total_records + "&total_pages=" + total_pages);

        $(".page-link").click(function(e) {
            e.preventDefault();
            $(".loading-div").show(); //show loading element
            const id = $(this).attr("data-id");
            const select_id = $(this).parent().attr("id");
            $.ajax({
                url: "ajax/employee_manager_pagination.php",
                type: "GET",
                data: {
                    page: id,
                    limit: limit,
                    total_records: total_records,
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
<?php
include 'include/overall/footer.php';
?>