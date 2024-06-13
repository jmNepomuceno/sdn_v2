<?php 
    include("../database/connection2.php");
    session_start();

    $hpercode = 'PAT000012';
    
    // $sql = "SELECT * FROM incoming_referrals WHERE hpercode='". $hpercode ."' ORDER BY date_time DESC LIMIT 1";
    // $stmt = $pdo->prepare($sql);
    // $stmt->execute();
    // $data = $stmt->fetchAll(PDO::FETCH_ASSOC);  
    // $jsonString = $data;

    $sql = "SELECT date_time FROM incoming_referrals WHERE hpercode='". $hpercode ."' ORDER BY date_time DESC LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $latest_referral = $stmt->fetch(PDO::FETCH_ASSOC);  


    echo '<pre>'; print_r($latest_referral); echo '</pre>';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>