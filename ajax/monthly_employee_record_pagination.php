<?php include '../core/init.php'; ?>

<?php
$limit = $_GET['limit'];
if (isset($_GET["page"])) {
    $page  = $_GET["page"];
} else {
    $page = 1;
};
$start_from = ($page - 1) * $limit;
$employee_id = $_GET['employee_id'];
$start_date = $_GET['start_date'];
$start_date = date("Y-m-d", $start_date);

$end_date = $_GET['end_date'];
$end_date = date("Y-m-d", $end_date);

$total_pages = $_GET['total_pages'];
if ($total_pages != 0) {
?>
    <table>
        <tr>
            <th>Date</th>
            <th>Time Attended (hr)</th>
        </tr>

        <?php
        $result = monthly_attedance($employee_id, $start_date, $end_date, $start_from, $limit);
        //    print_r($result);
        foreach ($result as $row) {
            echo "<tr><td>" . date('D,d M Y', strtotime($row['date'])) . "</td>";
            echo "<td>" . $row['Total Hours'] . "</td></tr>";
        }
        ?>
    </table>
<?php
} else { ?>
    <div class="" style="text-align: center">
        <img src="image/ghost_result.gif" width="500px" />
    </div>
<?php
}

?>