<?php 
    session_start();
    include('../database/connection2.php');

    $sql = "SELECT response_time,hpercode,status,progress_timer FROM incoming_referrals WHERE refer_to='". $_SESSION['hospital_name'] ."' ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $jsonString = json_encode($data);
    echo $jsonString;

?>