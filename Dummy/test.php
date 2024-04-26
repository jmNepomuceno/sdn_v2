<?php
    session_start();
    include('../database/connection2.php');

    // $sql = "SELECT * FROM incoming_referrals WHERE refer_to='Bataan General Hospital and Medical Center' AND hpercode='BGHMC-0049'";
    // $stmt = $pdo->prepare($sql);
    // $stmt->execute();
    // $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // echo '<pre>'; print_r($data); echo '</pre>';

    // $sql = "SELECT department FROM incoming_interdept WHERE hpercode='BGHMC-0049'";
    // $stmt = $pdo->prepare($sql);
    // $stmt->execute();
    // $data = $stmt->fetch(PDO::FETCH_ASSOC);
    // echo '<pre>'; print_r($data); echo '</pre>';

    // $sql = "SELECT * FROM incoming_referrals WHERE refer_to='Bataan General Hospital and Medical Center' AND hpercode='BGHMC-0050'";
    // $stmt = $pdo->prepare($sql);
    // $stmt->execute();
    // $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // echo '<pre>'; print_r($data); echo '</pre>';

    $sql = "SELECT * FROM incoming_referrals WHERE refer_to = 'Bataan General Hospital and Medical Center' AND date_time LIKE '%2024-04-24%'";
    // $sql = "SELECT reception_time, date_time, final_progressed_timer FROM incoming_referrals WHERE refer_to = :hospital_name AND reception_time LIKE '%2024-02-08%'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $pat_class_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo '<pre>'; print_r($pat_class_data); echo '</pre>';
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
    
    <script>
        var dataArray = ['Limay Medical Center', 'Limay Medical Center', 'Morong Bataan RHU', 'Morong Bataan RHU'];

        // Object to store counts of each element
        var counts = {};

        // Iterate over the array to count occurrences
        dataArray.forEach(function(item) {
            // Count occurrences of each element
            counts[item] = (counts[item] || 0) + 1;
        });

        // Array to store the unique elements
        var uniqueArray = Object.keys(counts);

        // Array to store the counts, matching length of uniqueArray
        var duplicatesCount = uniqueArray.map(function(item) {
            // Return count of each element
            return counts[item];
        });

        console.log("Unique array:", uniqueArray);
        console.log("Duplicates count:", duplicatesCount);
    </script>
</body>
</html>