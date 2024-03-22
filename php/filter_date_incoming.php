<?php 
    session_start();
    include("../database/connection2.php");

    // $dateTime = new DateTime('2024-01-29');
    // $formattedDate = $dateTime->format('Y-m-d') . '%';

    // from_date
    $from_date = new DateTime($_POST['from_date']);
    $to_date = new DateTime($_POST['to_date']);

    // $from_date = new DateTime('2024-01-25');
    // $to_date = new DateTime('2024-01-31');

    $formattedFromDate = $from_date->format('Y-m-d') . '%';
    $formattedToDate = $to_date->format('Y-m-d') . '%';

    $sql = "";
    if($_POST['where'] === 'incoming'){
        $sql = "SELECT  reception_time, date_time, final_progressed_timer, hpercode FROM incoming_referrals WHERE (status!='Pending' OR status!='On-Process' OR status!='Deferred' OR status!='Deferred' OR status!='Cancelled' OR status!='Discharged' OR status!='Referred Back') AND approved_time BETWEEN :start_date AND :end_date AND refer_to = '" . $_SESSION["hospital_name"] . "'";   
    }else{
        $sql = "SELECT  reception_time, date_time, final_progressed_timer, hpercode FROM incoming_referrals WHERE (status!='Pending' OR status!='On-Process' OR status!='Deferred' OR status!='Deferred' OR status!='Cancelled' OR status!='Discharged' OR status!='Referred Back') AND approved_time BETWEEN :start_date AND :end_date AND referred_by = '" . $_SESSION["hospital_name"] . "'";
    }

    // $sql = "SELECT  reception_time, date_time, final_progressed_timer, hpercode FROM incoming_referrals WHERE (status!='Pending' OR status!='On-Process' OR status!='Deferred' OR status!='Deferred' OR status!='Cancelled' OR status!='Discharged' OR status!='Referred Back') AND approved_time BETWEEN :start_date AND :end_date AND refer_to = '" . $_SESSION["hospital_name"] . "'";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':start_date', $formattedFromDate, PDO::PARAM_STR);
    $stmt->bindParam(':end_date', $formattedToDate, PDO::PARAM_STR);
    $stmt->execute();
    $dataDate = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // echo '<pre>'; print_r($dataDate); echo '</pre>';

    // $finalJsonString = json_encode($dataDate);
    // echo $finalJsonString;

    ///////////////////////////////////////////////////////////

    $recep_arr = array(); 
    for($i = 0; $i < count($dataDate); $i++){
        // Given dates
        $date1 = new DateTime($dataDate[$i]['reception_time']);
        $date2 = new DateTime($dataDate[$i]['date_time']);

        // Calculate the difference
        $interval = $date1->diff($date2);

        // Format the difference as hh:mm:ss
        $formattedDifference = sprintf(
            '%02d:%02d:%02d',
            $interval->h,
            $interval->i,
            $interval->s
        );

        array_push($recep_arr, $formattedDifference);
    }

    // print_r($recep_arr);

    $fastest_recep_secs = array();
    // Function to convert duration to seconds
    function durationToSeconds($duration) {
        list($hours, $minutes, $seconds) = explode(':', $duration);
        return $hours * 3600 + $minutes * 60 + $seconds;
    }

    // Function to convert seconds to duration
    function secondsToDuration($seconds) {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $seconds = $seconds % 60;

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }

    // for average reception time
    $averageSeconds_reception = 0;
    for($i = 0; $i < count($recep_arr); $i++){
        $averageSeconds_reception += durationToSeconds($recep_arr[$i]);
    }
    // for approval time
    $averageSeconds_approval = 0;
    for($i = 0; $i < count($dataDate); $i++){
        $averageSeconds_approval += durationToSeconds($dataDate[$i]['final_progressed_timer']);
    }

    // for total time
    $averageSeconds_total = 0;
    for($i = 0; $i < count($dataDate); $i++){
        $averageSeconds_total += (durationToSeconds($dataDate[$i]['final_progressed_timer']) + durationToSeconds($recep_arr[$i]));
    }

    // echo $averageSeconds_total;


    for($i = 0; $i < count($recep_arr); $i++){
        durationToSeconds($recep_arr[$i]);
        array_push($fastest_recep_secs, (durationToSeconds($recep_arr[$i]) + durationToSeconds($dataDate[$i]['final_progressed_timer'])));
    }

    
    // print_r($fastest_recep_secs);

    $averageSeconds_reception = (int) round($averageSeconds_reception / count($dataDate));
    $averageDuration_reception = secondsToDuration($averageSeconds_reception);  

    $averageSeconds_approval = (int) round($averageSeconds_approval / count($dataDate));
    $averageDuration_approval = secondsToDuration($averageSeconds_approval);

    $averageSeconds_total = (int) round($averageSeconds_total / count($dataDate));
    $averageDuration_total = secondsToDuration($averageSeconds_total);

    $fastest_response_final = secondsToDuration(min($fastest_recep_secs));
    $slowest_response_final = secondsToDuration(max($fastest_recep_secs)); 

    $associativeArray = array(
        'totalReferrals' => count($dataDate),
        'averageSeconds_reception' => $averageSeconds_reception,
        'averageDuration_reception' => $averageDuration_reception,
        'averageSeconds_approval' => $averageSeconds_approval,
        'averageDuration_approval' => $averageDuration_approval,
        'averageSeconds_total' => $averageSeconds_total,
        'averageDuration_total' => $averageDuration_total,
        'fastest_response_final' => $fastest_response_final,
        'slowest_response_final' => $slowest_response_final,
        'hpercode' => $dataDate[0]['hpercode']
    );

    // print_r($associativeArray);

    $finalJsonString = json_encode($associativeArray);
    echo $finalJsonString;
?>

