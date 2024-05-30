<?php
    session_start();
    include('../database/connection2.php');

    // $hpercode = "PAT000005";
    // $referral_id = "REF000001";

    // $sql2 = "UPDATE hperson SET referral_id = IFNULL(referral_id, JSON_ARRAY()) WHERE hpercode=:hpercode";
    // $stmt = $pdo->prepare($sql2);
    // $stmt->bindParam(':hpercode', $hpercode, PDO::PARAM_STR);
    // $stmt->execute();

    // $sql2 = "UPDATE hperson SET referral_id = JSON_ARRAY_APPEND(referral_id, '$', :referral_id) WHERE hpercode=:hpercode";
    // $stmt = $pdo->prepare($sql2);
    // $stmt->bindParam(':hpercode', $code, PDO::PARAM_STR);
    // $stmt->bindParam(':referral_id', $referral_id, PDO::PARAM_STR);
    // $stmt->execute();


    $hpercode = "PAT000005";
    $referral_id = "REF000001";

    $sql2 = "UPDATE hperson SET referral_id = IFNULL(referral_id, JSON_ARRAY()) WHERE hpercode=:hpercode";
    $stmt = $pdo->prepare($sql2);
    $stmt->bindParam(':hpercode', $hpercode, PDO::PARAM_STR);
    $stmt->execute();

    $sql2 = "UPDATE hperson SET referral_id = JSON_ARRAY_APPEND(referral_id, '$', :referral_id) WHERE hpercode=:hpercode";
    $stmt = $pdo->prepare($sql2);
    $stmt->bindParam(':hpercode', $hpercode, PDO::PARAM_STR);
    $stmt->bindParam(':referral_id', $referral_id, PDO::PARAM_STR);
    $stmt->execute();

    $sql = "SELECT referral_id FROM hperson";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data_classifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo '<pre>'; print_r($data_classifications); echo '</pre>'
?>

<!DOCTYPE html>
<html lang="en">
<head>s
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