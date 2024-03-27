<?php
    session_start();
    include("../database/connection2.php");
    
    $hpercode = $_POST['hpercode'];
    $array = array();
    //update the status of the patient in the table of incoming_referrals
    $sql = "SELECT status_interdept, reception_time FROM incoming_referrals WHERE hpercode=:hpercode";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':hpercode', $hpercode, PDO::PARAM_STR);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if($data['status_interdept'] != ""){
        $array[] = true;
    }else{
        $array[] = false;
    }

    if($data['reception_time'] != ""){
        $array[] = true;
    }else{
        $array[] = false;
    }

    $array = json_encode($array);
    echo $array;
?>