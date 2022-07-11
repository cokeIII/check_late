<?php
$title = "check_rate_" . date("d-m-Y");
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=$title.xls");
header("Pragma: no-cache");
header("Expires: 0");
require_once "connect.php";
$sql = $_POST["sql"];
$checkSql = $_POST["checkSql"];
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

$res = mysqli_query($conn, $sql);

?>
<table class="table" id="reportData" width="100%" border="1">
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
                <td><b><?php echo countLate($sql, $row["student_id"], $checkSql); ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>