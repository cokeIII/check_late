<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    date_default_timezone_set("asia/bangkok");
    function DateThai($strDate)
    {
        $exDate = explode("-", $strDate);
        $strDate = ($exDate[2]) . "-" . $exDate[1] . "-" . $exDate[0];
        $strYear = date("Y", strtotime($strDate));
        $strMonth = date("n", strtotime($strDate));
        $strDay = date("j", strtotime($strDate));
        $strHour = date("H", strtotime($strDate));
        $strMinute = date("i", strtotime($strDate));
        $strSeconds = date("s", strtotime($strDate));
        $strMonthCut = array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
        $strMonthThai = $strMonthCut[$strMonth];
        // return "$strDay $strMonthThai $strYear, $strHour:$strMinute";
        return "$strDay $strMonthThai $strYear";
    }
    require_once "setHead.php";
    require_once "connect.php";
    $sql = "";
    $checkSql = true;
    if (!empty($_POST["date_report"]) && !empty($_POST["date_report2"]) && !empty($_POST["time_report"])) {
        $checkSql = false;
        $timeArr = explode("-", $_POST["time_report"]);
        $time1 = $timeArr[0];
        $time2 = $timeArr[1];
        $day1 = $_POST["date_report"] . " " . $time1;
        $day2 = $_POST["date_report2"] . " " . $time2;
        $sql = "select *,MAX(time_stamp) as MaxTime from late where time_stamp between '$day1' and '$day2' group by student_id";
    } else if (!empty($_POST["date_report"]) && !empty($_POST["date_report2"])) {
        $checkSql = false;
        $day1 = $_POST["date_report"] . " 00:00:01";
        $day2 = $_POST["date_report2"] . " 23:59:59";
        $sql = "select *,MAX(time_stamp) as MaxTime from late where time_stamp between '$day1' and '$day2' group by student_id";
    } else if (!empty($_POST["date_report"]) && !empty($_POST["time_report"])) {
        $checkSql = false;
        $timeArr = explode("-", $_POST["time_report"]);
        $time1 = $timeArr[0];
        $time2 = $timeArr[1];
        $day1 = $_POST["date_report"] . " " . $time1;
        $day2 = $_POST["date_report"] . " " . $time2;
        $sql = "select *,MAX(time_stamp) as MaxTime from late where time_stamp between '$day1' and '$day2' group by student_id";
    } else if (!empty($_POST["date_report"])) {
        $checkSql = false;
        $day1 =  $_POST["date_report"];
        $sql = "SELECT *,MAX(time_stamp) as MaxTime FROM late WHERE date(time_stamp) = '$day1' group by student_id";
    } else if (!empty($_POST["time_report"])) {
        $checkSql = false;
        $timeArr = explode("-", $_POST["time_report"]);
        $time1 = $timeArr[0];
        $time2 = $timeArr[1];
        $sql = "SELECT *,MAX(time_stamp) as MaxTime FROM late WHERE time(time_stamp) >= '$time1' and time(time_stamp) <= '$time2' group by student_id";
    } else {
        $sql = "select *,MAX(time_stamp) as MaxTime from late  group by student_id";
    }
    $res = mysqli_query($conn, $sql);
    // echo $sql;
    ?>
</head>

<body class="bg-ctc">
    <?php require_once "menu.php"; ?>
    <div class="container">
        <div class="card shadow mt-5">
            <div class="card-header">
                <h5>รายงาน</h5>
            </div>
            <div class="card-body p-3">
                <form action="report.php" method="post">
                    <div class="row mt-3">
                        <div class="col-md-3">
                            <label>วันที่</label>
                            <input type="date" name="date_report" class="form-control" value="<?php echo (!empty($_POST["date_report"]) ? $_POST["date_report"] : ""); ?>">
                        </div>
                        <div class="col-md-1 text-center">ถึง<br>-</div>
                        <div class="col-md-3">
                            <label>วันที่</label>
                            <input type="date" name="date_report2" class="form-control" value="<?php echo (!empty($_POST["date_report2"]) ? $_POST["date_report2"] : ""); ?>">
                        </div>
                        <div class="col-md-3 border-left">
                            <label>เวลา</label>
                            <select name="time_report" class="form-control" id="timeReport">
                                <option value="">-</option>
                                <option value="07:50:00-07:59:00">07.50 - 07.59 น.</option>
                                <option value="08:00:00-09:30:00">08.00 - 09.30 น.</option>
                            </select>
                        </div>
                        <div class="col-md-2 mt-2">
                            <br>
                            <button type="submit" class="btn btn-primary">เลือก</button>
                        </div>
                    </div>
                </form>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <form action="excelReport.php" method="post">
                            <input type="hidden" name="checkSql" value="<?php echo $checkSql; ?>">
                            <input type="hidden" name="sql" value="<?php echo $sql; ?>">
                            <button class="btn btn-success float-end mb-3" id="reportExcel" checkSql="<?php echo $checkSql; ?>" sql="<?php echo $sql; ?>"><i class="fa-solid fa-file-excel"></i> Excel</button>
                        </form>
                    </div>
                </div>
                <table class="table" id="reportData" width="100%">
                    <thead>
                        <tr>
                            <td>ลำดับ</td>
                            <td>รหัสนักเรียน</td>
                            <td>ชื่อ- สกุล</td>
                            <td>ชั้นเรียน</td>
                            <td>เวลา</td>
                            <td>จำนวนครั้ง</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1;
                        while ($row = mysqli_fetch_array($res)) {
                            $dateArr = explode(" ", $row["MaxTime"]);
                        ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><?php echo $row["student_id"]; ?></td>
                                <td><?php echo $row["prefix"] . $row["fname"] . " " . $row["lname"]; ?></td>
                                <td><?php echo $row["student_group_short_name"]; ?></td>
                                <td><?php echo DateThai($dateArr[0]) . " เวลา " . $dateArr[1]; ?></td>
                                <td><b><?php echo countLate($sql, $row["student_id"], $checkSql); ?></b> <button student_id="<?php echo $row["student_id"]; ?>" class="btn btn-success detailData"><i class="fa-solid fa-eye"></i></button></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
<!-- Modal -->
<div class="modal fade" id="modalLate" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">รายการวันที่มาสาย</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="modal-display"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php require_once "setFoot.php"; ?>
<script src="dist/js/qrcode-reader.min.js"></script>
<?php
function countLate($sql, $std_id, $checkSql)
{
    global $conn;
    $sql1 = substr($sql, 0, 8);
    $sql = $sql1 . " " . substr($sql, 36, strlen($sql) - 1);
    $sql = substr($sql, 0, strlen($sql) - 19);
    if ($checkSql) {
        $sqlCount = $sql . " where student_id='" . $std_id . "'";
    } else {
        $sqlCount = $sql . " and student_id='" . $std_id . "'";
    }
    // echo $sqlCount;
    $res = mysqli_query($conn, $sqlCount);
    $numCount = mysqli_num_rows($res);
    return $numCount;
}
?>
<script>
    $(document).ready(function() {
        $('#reportData').DataTable({
            "scrollX": true
        });
        $("#reportExcel").click(function() {
            let sql = $(this).attr("sql")
            let checkSql = $(this).attr("checkSql")
            // $.redirect("excelReport.php", {
            //     sql: sql,
            //     checkSql: checkSql,
            // }, "POST");
        })
        let timeSelect = '<?php echo (!empty($_POST["time_report"]) ? $_POST["time_report"] : ""); ?>'
        $("#timeReport").val(timeSelect)
        $(document).on("click", ".detailData", function() {
            $.ajax({
                type: "POST",
                url: "getTime.php",
                data: {
                    student_id: $(this).attr("student_id")
                },
                success: function(result) {
                    $(".modal-display").html(result)
                }
            });
            $('#modalLate').modal('show');
        })
    })
</script>