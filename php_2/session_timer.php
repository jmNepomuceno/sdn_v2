<?php 
    session_start();
    include('../database/connection2.php');
    $_SESSION['running_timer'] = $_POST['formattedTime'];
    $_SESSION['running_hpercode'] = $_POST['hpercode'];
?>