<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    include_once("../database/class_dbconn.php");

    if ($_SERVER["REQUEST_METHOD"] === 'POST') {

        $formData = $_POST;

        try {
            $response = array(
                "success" => true,
                "message" => "Data received successfully",
                "formData" => $formData
            );
        } catch (Exception $e) {
            $response = array(
                "success" => false,
                "message" => "Error: " . $e->getMessage()
            );
        }
        
        echo json_encode($response);        

    } else {
        $response = array(
            "success" => false,
            "message" => "Invalid request method."
        );
    }

?>