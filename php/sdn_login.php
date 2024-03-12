<?php   

    $sdn_username = $_POST['sdn_username'];
    $sdn_password = $_POST['sdn_password'];

    $_SESSION['username'] = $sdn_password;

    if($sdn_username == "admin" && $sdn_password == "admin"){
        // header
        echo "main.php";
    }
    
?>