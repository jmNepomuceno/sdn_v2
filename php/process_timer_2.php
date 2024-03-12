<?php
    session_start();
    include('../database/connection2.php');

    $global_single_hpercode = $_POST['global_single_hpercode'];
    $elapsedTime = $_POST['elapsedTime'];
    // $table_index = $_POST['table_index'];

    $already_in_session = false; // already in session process_timer
    $index = 0;


    // check if meron ng laman yung process_timer na session whenever nirerefresh yung page
    if(count($_SESSION["process_timer"]) > 0){

        for($i = 0; $i < count($_SESSION["process_timer"]); $i++){
            if($global_single_hpercode == $_SESSION["process_timer"][$i]['global_single_hpercode']){
                $already_in_session = true;
                $index = $i;
                break;
            }
        } 
    }
    
    if($already_in_session === true){
        // echo "true \n"; 
        $_SESSION["process_timer"][$index]['elapsedTime'] = $elapsedTime;
    }else{
        // updating the status of the patient in the database
        $_SESSION["process_timer"][] = array( 
            'global_single_hpercode' => $global_single_hpercode, 
            'elapsedTime' => $elapsedTime,
            // 'table_index' => $table_index,
            // 'approved_bool' => $approved_bool
        );
        // echo "False \n";
        $sql = "UPDATE incoming_referrals SET status='On-Process' WHERE hpercode= '". $global_single_hpercode ."' ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }
    

    $temp = json_encode($_SESSION["process_timer"]);
    echo $temp;
?>