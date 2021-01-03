let employee_id = $('.btn').val();
$.ajax({
    type: 'post',
    url: "core/function/attendance.php",
    data: {
        'get_current_session': employee_id
    },
    success: function (response) {
        console.log(response);
        // alert(response);
        if (response === "false") {
            $('.btn').text("Check In");
        } else {
            $('.btn').text("Check Out");
        }
    }
});

$('.btn').click(function () {

    $('btn').toggle();
    $(".loading-div").show(); //show loading element

    employee_id = $(this).val();
    // alert(employee_id);
    $.ajax({
        type: 'post',
        url: "core/function/attendance.php",
        data: {
            'get_current_session': employee_id
        },
        success: function (response) {
            if (response !== "false")
                response = JSON.parse(response)
            // alert(response);
            $(".loading-div").hide(); //show loading element

            if (response === "false") {

                $('.btn').text("Check Out");

                $.ajax({
                    type: 'post',
                    url: "core/function/attendance.php",
                    data: {
                        'checkin': employee_id
                    },
                    success: function (response) {
                        console.log(response);
                    }
                });
            } else if (response["minimumSessionTime"] > 120) { // 120 seconds
                $(".loading-div").hide(); //show loading element
                
                $('.btn').text("Check In");

                const session_employee_id = response["attendance_id"];
                console.log(employee_id)
                $.ajax({
                    type: 'post',
                    url: "core/function/attendance.php",
                    data: {
                        'checkout': session_employee_id,
                        'employee_id': employee_id
                    },
                    success: function (response) {}
                });
            }
        }
    });
});