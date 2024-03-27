<?php
    session_start();
    include("../database/connection2.php");
    date_default_timezone_set('Asia/Manila');

    $timer = $_POST['timer'];
    $currentDateTime = date('Y-m-d H:i:s');

    // Update for status of approval or deferral
    $pat_class = $_POST['case_category'];
    $global_single_hpercode = filter_input(INPUT_POST, 'global_single_hpercode');
    if($_POST['action'] === "Approve"){
        $sql = "UPDATE incoming_referrals SET status='Approved', pat_class=:pat_class WHERE hpercode=:hpercode AND refer_to = '" . $_SESSION["hospital_name"] . "'";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':hpercode', $global_single_hpercode, PDO::PARAM_STR);
        $stmt->bindParam(':pat_class', $pat_class, PDO::PARAM_STR);
        $stmt->execute();
    }else{
        $sql = "UPDATE incoming_referrals SET status='Deferred', pat_class=:pat_class WHERE hpercode=:hpercode AND refer_to = '" . $_SESSION["hospital_name"] . "'";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':hpercode', $global_single_hpercode, PDO::PARAM_STR);
        $stmt->bindParam(':pat_class', $pat_class, PDO::PARAM_STR);
        $stmt->execute();
    }

    // update of the final progressed timer
    $timer = filter_input(INPUT_POST, 'timer');
    $sql_b = "UPDATE incoming_referrals SET final_progressed_timer=:timer WHERE hpercode=:hpercode AND refer_to = '" . $_SESSION["hospital_name"] . "'";
    $stmt_b = $pdo->prepare($sql_b);
    $stmt_b->bindParam(':timer', $timer, PDO::PARAM_STR);
    $stmt_b->bindParam(':hpercode', $global_single_hpercode, PDO::PARAM_STR);
    $stmt_b->execute();

    // update the approved_details and set the time of approval on the database
    $approve_details = filter_input(INPUT_POST, 'approve_details');
    if($_POST['action'] === "Approve"){
        $sql = "UPDATE incoming_referrals SET approval_details=:approve_details, approved_time=:approved_time, progress_timer=NULL, refer_to_code=NULL WHERE hpercode=:hpercode AND refer_to = '" . $_SESSION["hospital_name"] . "'";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':approve_details', $approve_details, PDO::PARAM_STR); // currentDateTime
        $stmt->bindParam(':approved_time', $currentDateTime, PDO::PARAM_STR);
        $stmt->bindParam(':hpercode', $global_single_hpercode, PDO::PARAM_STR);
        $stmt->execute();
    }else{
        $sql = "UPDATE incoming_referrals SET deferred_details=:approve_details, deferred_time=:approved_time WHERE hpercode=:hpercode AND refer_to = '" . $_SESSION["hospital_name"] . "'";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':approve_details', $approve_details, PDO::PARAM_STR); // currentDateTime
        $stmt->bindParam(':approved_time', $currentDateTime, PDO::PARAM_STR);
        $stmt->bindParam(':hpercode', $global_single_hpercode, PDO::PARAM_STR);
        $stmt->execute();
    }


    //get all the pending or on-process status on the database to populate the data table after the approval
    $sql = "SELECT * FROM incoming_referrals WHERE (status='Pending' OR status='On-Process') AND refer_to = '" . $_SESSION["hospital_name"] . "'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);  


    $jsonString = json_encode($data);
    echo $jsonString;


    // update also the status of the patient on the hperson table
    $sql = "SELECT type FROM incoming_referrals WHERE hpercode=:hpercode AND refer_to = '" . $_SESSION["hospital_name"] . "'";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':hpercode', $global_single_hpercode, PDO::PARAM_STR);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if($_POST['action'] === "Approve"){
        $sql = "UPDATE hperson SET status='Approved', type='". $data['type'] ."' WHERE hpercode=:hpercode ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':hpercode', $global_single_hpercode, PDO::PARAM_STR);
        $stmt->execute();
    }else{
        $sql = "UPDATE hperson SET status='Deferred', type='". $data['type'] ."' WHERE hpercode=:hpercode ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':hpercode', $global_single_hpercode, PDO::PARAM_STR);
        $stmt->execute();
    }

    $sql = "SELECT patlast, patfirst, patmiddle FROM incoming_referrals WHERE hpercode=:hpercode AND refer_to = '" . $_SESSION["hospital_name"] . "'";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':hpercode', $global_single_hpercode, PDO::PARAM_STR);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);


    // updating for history log
    $act_type = 'pat_refer';
    $history_stats = "";
    if($_POST['action'] === "Approve"){
        $history_stats = "Approved";
    }else{
        $history_stats = "Deferred";
    }
    $action = 'Status Patient: ' . $history_stats;
    $pat_name = $data[0]['patlast'] . ' ' . $data[0]['patfirst'] . ' ' . $data[0]['patmiddle'];
    $sql = "INSERT INTO history_log (hpercode, hospital_code, date, activity_type, action, pat_name, username) VALUES (?,?,?,?,?,?,?)";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(1, $global_single_hpercode, PDO::PARAM_STR);
    $stmt->bindParam(2, $_SESSION['hospital_code'], PDO::PARAM_INT);
    $stmt->bindParam(3, $currentDateTime, PDO::PARAM_STR);
    $stmt->bindParam(4, $act_type, PDO::PARAM_STR);
    $stmt->bindParam(5, $action, PDO::PARAM_STR);
    $stmt->bindParam(6, $pat_name, PDO::PARAM_STR);
    $stmt->bindParam(7, $_SESSION['user_name'], PDO::PARAM_STR);

    $stmt->execute();
?>



