<?php
include 'D:\Downloads\harika\htdocs\SecurinAssessment-Marepalli_Harika_yadav-Amrita_Vishwa_vidhyapeetham\config\config.php';
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}
?>
