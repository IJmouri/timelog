<?php include '../core/init.php'; ?>

<?php
$limit = $_GET['limit'];
if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };
$start_from = ($page-1) * $limit;

$total_pages = $_GET['total_pages'];
if ($total_pages != 0) {
?>
<table>
    <tr>
        <th>Date</th>
        <th>Check In</th>
        <th>Check Out</th>
        <th>Time Attended (hr)</th>
    </tr>

    <?php

    $data = get_employee_record($_GET['employee_id'], $start_from, $limit);

    foreach ($data as $row) {

        $checkin = convertTime($row['check_in']);

        echo "<tr><td>" . $row['date'] . "</td>";
        echo "<td>" . date('H:i:s', strtotime($row['check_in'])) . "</td>";

        if (empty($row['check_out'])) {
            echo "<td>" . $row['check_out'] . "</td>";
            echo "<td>" . timeDifference($checkin, convertTime(date('Y-m-d H:i:s'))) . "</td></tr>";
        } else {
            $checkout = convertTime($row['check_out']);
            echo "<td>" . date('H:i:s', strtotime($row['check_out'])) . "</td>";
            echo "<td>" . timeDifference($checkin, $checkout) . "</td></tr>";
        }
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
