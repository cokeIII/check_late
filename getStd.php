<?php
require_once "connect.php";
$student_id = $_POST["student_id"];
$sql = "select * from student s 
inner join prefix p on p.prefix_id = s.perfix_id
inner join student_group g on g.student_group_id = s.group_id
where s.student_id = '$student_id'";
$res = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($res);
$numRow = mysqli_num_rows($res);
$data = array();
if ($numRow > 0) {
    $std_id = $row["student_id"];
    $prefix = $row["prefix_name"];
    $stu_fname = $row["stu_fname"];
    $stu_lname = $row["stu_lname"];
    $group_id = $row["group_id"];
    $level = $row["level_name"];
    $grade_name = $row["grade_name"];
    $dept_name = $row["major_name"];
    $day = date("Y-m-d");
    $student_group_short_name = $row["student_group_short_name"];
    $sqlCheck = "select * from late where student_id = '$std_id' and date(time_stamp)='$day'";
    $resCheck = mysqli_query($conn, $sqlCheck);
    $numRowCheck = mysqli_num_rows($resCheck);
    if ($numRowCheck > 0) {
        $data["name"] = "วันนี้ได้บันทึกข้อมูลนักเรียนไปแล้ว";
        $data["dayCheck"] = true;
    } else {
        $sqlIn = "insert into late (
            student_id,
            prefix,
            fname,
            lname,
            group_id,
            level,
            grade_name,
            dept_name,
            student_group_short_name
            ) values(
                '$std_id',
                '$prefix',
                '$stu_fname',
                '$stu_lname',
                '$group_id',
                '$level',
                '$grade_name',
                '$dept_name',
                '$student_group_short_name'
            )";
        $data["name"] = $prefix . $stu_fname . " " . $stu_lname;
        $data["grade_name"] = $grade_name;
        $data["dept_name"] = $dept_name;
        // $data["sqlCheck"] = $sqlCheck;
        // $data["sql"] = $sql;
        // $data["sqlIn"] = $sqlIn;
        // print_r($data);
        $resIn = mysqli_query($conn, $sqlIn);
        // if ($resIn) {
        //     echo json_encode($data);
        // }
    }
    echo json_encode($data);
}
