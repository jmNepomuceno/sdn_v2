<?php
    session_start();
    include('../database/connection2.php');

    $temp_array = array();
    for($i=0; $i < count($_SESSION["process_timer"]); $i++){
        $temp_array[] =  '{"pat_clicked_code" :  "' .$_SESSION["process_timer"][$i]["pat_clicked_code"].'" ,  elapsedTime: "' .$_SESSION["process_timer"][$i]["elapsedTime"].'" "}';
    }  

    // echo $temp_array;

    $temp_session = json_encode($_SESSION["process_timer"]);
    echo $temp_session;

?>