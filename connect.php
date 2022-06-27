<?php
$servername = "localhost";
$username = "root";
$password = "chontech2020!";
$database = "check_late";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);
$conn->set_charset("utf8");
// Check connection
// if (!$conn) {
//     die("Connection failed: " . mysqli_connect_error());
// }
// echo "Connected successfully";
