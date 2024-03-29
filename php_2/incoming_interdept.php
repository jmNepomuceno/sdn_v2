<?php
    session_start();
    include("../database/connection2.php");
    date_default_timezone_set('Asia/Manila');

    // insert the data into incoming_interdept
    $dept = $_POST['dept'];
    $currentDateTime = date('Y-m-d H:i:s');
    $hpercode = $_POST['hpercode'];
    $pause_time = $_POST['pause_time'];

    $sql = "INSERT INTO incoming_interdept (department, hpercode, recept_time) VALUES (?,?,?)";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(1, $dept, PDO::PARAM_STR);
    $stmt->bindParam(2, $hpercode, PDO::PARAM_STR);
    $stmt->bindParam(3, $currentDateTime, PDO::PARAM_STR);
    $stmt->execute();


    //update the status of the patient in the table of incoming_referrals
    $sql = "UPDATE incoming_referrals SET status_interdept='Pending' , sent_interdept_time=:pause_time WHERE hpercode=:hpercode";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':hpercode', $hpercode, PDO::PARAM_STR);
    $stmt->bindParam(':pause_time', $pause_time, PDO::PARAM_STR);
    $stmt->execute();
?>