$('#scanned-result').text("");
const html5QrcodeScanner = new Html5QrcodeScanner(
    "reader", {
        fps: 10,
        qrbox: 250
    }
);
html5QrcodeScanner.render(onScanSuccess);

function onScanSuccess(qrCodeMessage) {
    let employee_id = qrCodeMessage;
    // alert(employee_id);
    $.ajax({
        type: 'post',
        url: "core/function/attendance.php",
        data: {
            'employee_exists': employee_id
        },
        success: function (response) {
            // console.log(response);
            // alert(response);
            if (response === "1") {
                $.ajax({
                    type: 'post',
                    url: "core/function/attendance.php",
                    data: {
                        'get_current_session': employee_id
                    },
                    success: function (response) {
                        // console.log(response);
                        // alert(response['attendance_id'])
                        if (response !== "false")
                            response = JSON.parse(response)
                        // console.log(response["attendance_id"])
                        // console.log(response["minimumSessionTime"])
                        if (response === "false") {
                            $.ajax({
                                type: 'post',
                                url: "core/function/attendance.php",
                                data: {
                                    'checkin': employee_id
                                },
                                success: function (response) {
                                    $('#scanned-result').text("Checked In");
                                    html5QrcodeScanner.clear();
                                }
                            });
                        } else if (response["minimumSessionTime"] > 120) {
                            // $('#scanned-result').text("id" + response);
                            const session_employee_id = response["attendance_id"];
                            $.ajax({
                                type: 'post',
                                url: "core/function/attendance.php",
                                data: {
                                    'checkout': session_employee_id,
                                    'employee_id': employee_id
                                },
                                success: function (response) {
                                    $('#scanned-result').text("Checked Out");
                                    html5QrcodeScanner.clear();
                                }
                            });
                        } else {
                            $('#scanned-result').text("You can not check out right now!");
                            html5QrcodeScanner.clear();
                        }

                    }
                });
            } else {
                $('#scanned-result').text("Invalid Employee ID");
            }
        }
    });



    // handle on success condition with the decoded message
}