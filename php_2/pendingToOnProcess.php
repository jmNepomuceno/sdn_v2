<?php
    session_start();
    include('../database/connection2.php');

    $hpercode = $_POST['hpercode'];
    $sql = "UPDATE incoming_referrals SET status='On-Process' WHERE hpercode= '". $hpercode ."' ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
?>