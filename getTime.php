<?php
require_once "connect.php";
$student_id = $_POST["student_id"];
$sql = "select time_stamp from late where student_id = '$student_id'";
$res = mysqli_query($conn, $sql);
$data = "";
while ($row = mysqli_fetch_array($res)) {
    $timeArr = explode(" ", $row["time_stamp"]);
    $data .= DateThai($timeArr[0]) . " เวลา " . $timeArr[1] . "<br>";
}
echo $data;
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
