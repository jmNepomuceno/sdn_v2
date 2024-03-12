<?php 

    session_start();
    include('../database/connection2.php');

    $pat_clicked_code = $_POST['pat_clicked_code'];
    $elapsedTime = $_POST['elapsedTime'];
    $table_index = $_POST['table_index'];

    $approved_bool = $_POST['approved_bool'];
    $approved_hpercode = $_POST['approved_clicked_hpercode'];

    $already = false;
    $index = 0;

    for($i = 0; $i < count($_SESSION["process_timer"]); $i++){
        if($pat_clicked_code == $_SESSION["process_timer"][$i]['pat_clicked_code']){
            $already = true;
            $index = $i;
            break;
        }
    } 

    //echo $already . " / " . $index .  " \n";
    if($_POST['secs_add'] === '0'){
        $sql = "UPDATE incoming_referrals SET logout_date=null, progress_timer=null, refer_to_code=null";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }
    if($already === true){
        // echo "true \n"; 
        $_SESSION["process_timer"][$index]['elapsedTime'] = $elapsedTime;
    }else{
        //echo "false \n";
        
        $_SESSION["process_timer"][] = array( 
            'pat_clicked_code' => $pat_clicked_code, 
            'elapsedTime' => $elapsedTime,
            'table_index' => $table_index,
            'approved_bool' => $approved_bool
        );

        $sql = "UPDATE incoming_referrals SET status='On-Process' WHERE hpercode= '". $pat_clicked_code ."' ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        
    }

    // for($i = 0; $i < count($_SESSION["process_timer"]); $i++){
    //     echo $_SESSION["process_timer"][$i]['pat_clicked_code'] . " ";
    //     echo $_SESSION["process_timer"][$i]['elapsedTime'] . " ";
    //     echo $_SESSION["process_timer"][$i]['table_index'];
    // }
    
    $i = 0;
    if($_POST['approved_bool'] === 'true'){
        foreach ($_SESSION["process_timer"] as $key => &$item) {
            if ($item['pat_clicked_code'] === $approved_hpercode) {
                $item['approved_bool'] = 'true';
                // unset($item['pat_clicked_code']);
            }
        }
        unset($item);
    }

    $temp = json_encode($_SESSION["process_timer"]);
    echo $temp;

?>