<?php

header('Access-Control-Allow-Origin: *');
//header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers:Access-Control-Allow-Headers, Access-Control-Allow-Methods, Content-Type, Authorization, X-Requested-With');


include_once '../../core/db/connect.php';
include_once '../../core/function/attendance.php';

if (isset($_GET['employee_id'])) {
    $data = checkin($_GET['employee_id']);
//    echo "c";

    echo json_encode(
        array('message' => 'Checked In')
    );
}
