<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    date_default_timezone_set("asia/bangkok");
    require_once "setHead.php";
    ?>
</head>

<body class="bg-ctc">
    <?php require_once "menu.php"; ?>
    <div class="container">
        <div class="form-group row mt-5 p-3">
            <!-- <div class="col-md-3 col-ms-2.5 p-1">
                <h5>รหัสนักเรียน/นักศึกษา</h5>
            </div> -->
            <div class="col-md-6 col-ms-3 p-2">
                <form action="" method="post">
                </form>
                <input type="number" name="student_id" id="student_id" class="form-control" placeholder="รหัสนักเรียน/นักศึกษา">
            </div>
            <div class="col-md-6 col-ms-6 p-2">
                <button class="btn btn-primary float-right qrcode-reader" type="button" id="openreader-single2" data-qrr-target="#single2" data-qrr-audio-feedback="true">
                    <i class="fa fa-qrcode fa-2x" aria-hidden="true"></i>
                    <span class="line-center">Scan QR</span>
                </button>
            </div>
        </div>
        <div class="mt-5 row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h1>
                            <div class="text-center" id="stdName"></div>
                        </h1>
                    </div>

                    <div class="card-body text-center">
                        <img src="img/logo_ctc.png" width="20%" height="auto" id="imgShow">
                        <div id="showData">
                            <h1>
                                <div class="text-center" id="gradeName"></div>
                            </h1>
                            <br>
                            <h1>
                                <div class="text-center" id="deptName"></div>
                            </h1>
                            <br>
                            <h1>
                                <div class="text-center" id="timeShow">เวลา <?php echo date("h:i:sa"); ?></div>
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</body>

</html>
<?php require_once "setFoot.php"; ?>
<script src="dist/js/qrcode-reader.min.js"></script>

<script>
    $(document).ready(function() {
        $("#showData").hide()
        $("#student_id").focus()
        $('#student_id').keypress(function(event) {
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if (keycode == '13') {
                getData($('#student_id').val())
            }
        });
    });
    // overriding path of JS script and audio 
    // overriding path of JS script and audio 
    $.qrCodeReader.jsQRpath = "dist/js/jsQR/jsQR.min.js";
    $.qrCodeReader.beepPath = "dist/audio/beep.mp3";

    // bind all elements of a given class
    $(".qrcode-reader").qrCodeReader();

    // read or follow qrcode depending on the content of the target input
    $("#openreader-single2").qrCodeReader({
        callback: function(code) {
            if (code) {
                // window.location.href = code;
                $("#student_id").val(code)
                getData($("#student_id").val())

            }
        }
    }).off("click.qrCodeReader").on("click", function() {
        var qrcode = $("#student_id").val().trim();
        if (qrcode) {
            // window.location.href = qrcode;
        } else {
            $.qrCodeReader.instance.open.call(this);
        }
    });

    function clearData() {
        // $("#stdName").html("")
        $("#gradeName").html("")
        $("#deptName").html("")
        $("#showData").hide()
        $("#imgShow").show()
        $("#student_id").val("")
    }

    function getData(student_id) {
        $.ajax({
            type: "POST",
            url: "getStd.php",
            data: {
                student_id: student_id
            },
            success: function(result) {
                if (result != "") {
                    data = JSON.parse(result);
                    if (data.name != "") {
                        if (data.dayCheck) {
                            $("#stdName").html(data.name)
                            clearData()
                            return
                        }
                        $("#stdName").html(data.name)
                        $("#gradeName").html(data.grade_name)
                        $("#deptName").html(data.dept_name)
                        $("#showData").show()
                        $("#imgShow").hide()
                        $("#student_id").val("")

                    } else {
                        $("#stdName").html("ไม่พบข้อมูลนักเรียน")
                        clearData()
                    }
                } else {
                    $("#stdName").html("ไม่พบข้อมูลนักเรียน")
                    clearData()
                }
            }
        });
    }
</script>