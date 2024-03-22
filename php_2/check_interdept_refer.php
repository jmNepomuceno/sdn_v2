<?php
    session_start();
    include("../database/connection2.php");
    
    $hpercode = $_POST['hpercode'];
    //update the status of the patient in the table of incoming_referrals
    $sql = "SELECT status_interdept FROM incoming_referrals WHERE hpercode=:hpercode";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':hpercode', $hpercode, PDO::PARAM_STR);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if($data['status_interdept'] != ""){
        echo true;
    }else{
        echo false;
    }
?>