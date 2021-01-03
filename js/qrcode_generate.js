$(document).ready(function () {
    $('.qrcode-generate').click(function(){
        const employee_id = $(this).val();
        console.log(employee_id);
        const qr_id = "#qrcode-" + employee_id;
        console.log(qr_id);
       $.ajax({
            type: 'post',
            url: "core/function/employee.php",
            data: {
                'qrcode_exist': employee_id
            },
            success: function (response) {
                // alert(response);
                if (response === '') {
                    // $(".qr").html("<button class='gen-btn' id='qrcode-generate' value=" + employee_id + ">Generate</button>")
                    // $('#qrcode-generate').click(function () {
                        // const qrcode = $(this).val();
                        // alert(qrcode);
                        $.ajax({
                            type: 'post',
                            url: "core/function/employee.php",
                            data: {
                                'qrcode': employee_id
                            },
                            success: function (response) {
                                // alert(response);
                                $("#qrcode-succes").text("Qr code generated successfully for employee-id: " + employee_id + ".");
                                setTimeout(function () {
                                    $('#qrcode-succes').fadeOut()
                                }, 3000)
                                $("#qr-image-"+employee_id).attr("src", response)
                                $(".qr-gen").html("<a class=text-color onClick=$('" + qr_id + "').show();><button class=gen-btn>view</button></a>");
                            }
                        })
                    // })
                }
                //  else {
                    // $(".qr").html("<a class=text-color onClick=$('" + qr_id + "').show();><button class=gen-btn>view</button></a>");
                // }
            }
        })
    })
})