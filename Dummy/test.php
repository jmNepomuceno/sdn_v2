<?php
    session_start();
    include('../database/connection2.php');

    $sql = "SELECT * FROM incoming_referrals WHERE refer_to='Bataan General Hospital and Medical Center' AND hpercode='BGHMC-0049'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo '<pre>'; print_r($data); echo '</pre>';

    $sql = "SELECT department FROM incoming_interdept WHERE hpercode='BGHMC-0049'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    echo '<pre>'; print_r($data); echo '</pre>';

    $sql = "SELECT * FROM incoming_referrals WHERE refer_to='Bataan General Hospital and Medical Center' AND hpercode='BGHMC-0050'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo '<pre>'; print_r($data); echo '</pre>';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timer Example</title>

    <style>
        body{
            background: black;
            color:white;
            font-size: 1.3rem;
        }
    </style>
</head>
<body>
    
</body>
</html>