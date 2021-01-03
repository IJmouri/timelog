<?php include '../core/init.php'; ?>
<?php

// // removing employee's records
// if (isset($_GET['employee_id']) && $_GET['mode'] === "remove") {
//     $employee_id = $_GET['employee_id'];

//     $remove = remove_employee($employee_id);
//     header('Location: ../employee_manager.php');
// }

?>

<h3 id="qrcode-succes" style="color: green"></h3>
<?php
$limit = $_GET['limit'];
if (isset($_GET["page"])) {
    $page  = $_GET["page"];
} else {
    $page = 1;
};
$start_from = ($page - 1) * $limit;

$total_records = $_GET['total_records'];
$total_pages = $_GET['total_pages'];
if ($total_pages != 0) {
?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Department</th>
                <th>Title</th>
                <th>Status</th>
                <th>QR Code</th>
                <th>Remove</th>
            </tr>
        </thead>
        <tbody>

            <?php

            $data = get_employees($_SESSION['user_id'], $start_from, $limit);
            foreach ($data as $row) {

                $status = get_current_session($row['employee_id']); // check if any employee is checkedin or not
                $employee_id = $row['employee_id'];

                echo "<tr id='tr_id-$employee_id'><td>" . $employee_id . "</td>";
                echo "<td><a href='company_individual_employee_record.php?employeeId=$employee_id'><p class='ellipsis'>" . $row['name'] . "</p></a></td>";
                echo "<td><p class='ellipsis'>" . $row['department'] . "</p></td>";
                echo "<td><p>" . $row['title'] . "</p></td>";

                if ($status !== "false") {
                    echo "<td style='color: green;'><bold>Checked In</bold></td>";
                } else {
                    echo "<td> </td>";
                } ?>

                <?php

                if ($row['qrcode'] !== NULL) {
                ?>
                    <td class="qr-view"><button class="gen-btn" onClick="$('#qrcode-<?php echo $employee_id; ?>').show();">
                            view</button></td>
                <?php
                } else {
                ?>
                    <td class="qr-gen"><button class="gen-btn qrcode-generate" value="<?php echo $employee_id; ?>">
                            Generate</button></td>

                <?php
                }
                ?>
                <td>
                    <a onClick="$('#modal-<?php echo $employee_id; ?>').show();">
                        <i class="fa fa-trash" style="font-size:32px ;color: darkslateblue" aria-hidden="true"></i>
                    </a>
                </td>
                </tr>
                <!--  qr code popup-->
                <div class="modal" id="qrcode-<?php echo $employee_id; ?>" style="display:none;">
                    <div class="modal-content" style="text-align: center">
                        <div class="modal-header">
                            <span class="close" onClick="$('#qrcode-<?php echo $employee_id; ?>').hide();">&times;</span>
                            <h2 style="margin-top: 10px">QR Code</h2>
                        </div>
                        <div class="modal-body">
                            <h3>Qr-Code image of employee-id: <?php echo $employee_id; ?></h3>
                            <img src="<?php echo $row['qrcode']; ?>" id="qr-image-<?php echo $employee_id; ?>">
                        </div>
                    </div>
                </div>
                <!--        remove popup-->
                <div class="modal" id="modal-<?php echo $employee_id; ?>" style="display:none;">
                    <div class="modal-content" style="text-align: center">
                        <div class="modal-header">
                            <span class="close" onClick="$('#modal-<?php echo $employee_id; ?>').hide();">&times;</span>
                            <h2 style="margin-top: 10px">Confirm Remove</h2>
                        </div>
                        <div class="modal-body">
                            <h2 style="margin-top: 25px">Are you sure you want to remove employee id:<?php echo $employee_id; ?>’s records?</h2><br />
                        </div>
                        <div class="modal-footer">
                            <a onClick="$('#modal-<?php echo $employee_id; ?>').hide();">
                                <button class="confirm-btn remove" value="<?php echo $employee_id; ?>">Yes</button></a>
                            <a onClick="$('#modal-<?php echo $employee_id; ?>').hide();">
                                <button class="confirm-btn">No</button></a>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
        </tbody>
    </table>
<?php
} else { ?>
    <div class="" style="text-align: center">
        <img src="image/ghost_result.gif" width="500px" />
    </div>
<?php
}

?>

<script type="text/javascript" src="js/qrcode_generate.js"></script>
<script>
    const limit = <?php echo $limit; ?>;
    let records = <?php echo $total_records; ?>;
    $('.remove').click(function() {
        const employee_id = $(this).val();
        const company_id = <?php echo $_SESSION['user_id']; ?>;

        $.ajax({
            type: 'post',
            url: "core/function/employee.php",
            data: {
                'remove_id': employee_id,
                'user_id': company_id
            },
            success: function(response) { //updating pagination start
                $('#tr_id-' + employee_id).remove();
                let total_old_pages = <?php echo $total_pages ?>;
                let page = <?php echo $page; ?>; //current page number
                let total_new_records = response;
                let total_new_pages = Math.ceil(total_new_records / limit);

                let id = total_new_pages;

                if (total_old_pages !== total_new_pages) {
                    total_old_pages = total_new_pages;
                    id = id + 1;
                    $("#" + id).remove();
                }
                if (page === total_new_pages + 1 && total_new_records % limit === 0) {
                    page = page - 1;
                }
                if (total_new_pages === 0) {
                    $("#1").remove();
                }
                $("#target-content").load("ajax/employee_manager_pagination.php?page=" + page + "&limit=" + limit + "&total_records=" + total_new_records + "&total_pages=" + total_new_pages);
                $(".pageitem").removeClass("active");

                $("#" + page).addClass("active");
            }
        })
    })
</script>
<!-- <script type="text/javascript" src="js/remove.js"></script> -->