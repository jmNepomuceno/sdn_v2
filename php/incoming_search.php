<?php 
    session_start();
    include("../database/connection2.php");
    //SELECT * FROM incoming_referrals WHERE status='Pending' AND referred_by='Limay Medical Center'
    
    $get_all = $_POST['get_all'];
    
    if($get_all === 'true'){
        $sql = "SELECT * FROM incoming_referrals WHERE refer_to = '" . $_SESSION["hospital_name"] . "' AND (status='Pending' OR status='On-Process')";
        // echo 'true';
    }
    if($get_all === 'false'){
        if(isset($_POST['stopwatch_arr']) && isset($_POST['hpercode_arr'])){

        $stopwatchArr = $_POST['stopwatch_arr'];
        $hpercode_arr = $_POST['hpercode_arr'];

        if(count($stopwatchArr) > 0){
            for($i = 0; $i < count($stopwatchArr); $i++){
                $sql = "UPDATE incoming_referrals SET response_time='". $stopwatchArr[$i] ."' WHERE hpercode='". $hpercode_arr[$i] ."' ";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
            }
            // $sql = "UPDATE incoming_referrals SET logout_date=null, progress_timer=null, refer_to_code=null";
            // $stmt = $pdo->prepare($sql);
            // $stmt->execute();
        }
    }

        $ref_no = $_POST['ref_no'];
        $last_name = $_POST['last_name'];
        $first_name = $_POST['first_name'];
        $middle_name = $_POST['middle_name'];
        $case_type = $_POST['case_type'];
        $agency = $_POST['agency'];
        $status = $_POST['status'];
        // $status = 'Pending';


        // referred_by='". $agency ."'
        // $sql = "SELECT * FROM incoming_referrals WHERE (reference_num LIKE '%". $agency ."%' OR patlast LIKE '%". $last_name ."%' OR patfirst LIKE '%". $first_name ."%' OR patmiddle LIKE '%". $middle_name ."%' OR type LIKE '%". $case_type ."%' OR referred_by LIKE '%". $agency ."%' OR status LIKE '%". $status ."%') AND refer_to='". $_SESSION["hospital_name"] ."'";

        // $sql = "SELECT * FROM incoming_referrals WHERE (type = '" . $case_type . "' OR referred_by = '" . $agency . "') AND refer_to = '" . $_SESSION["hospital_name"] . "'";
        //SELECT * FROM incoming_referrals WHERE type = 'ER' AND referred_by = 'Referred: Limay Medical Center' AND status = 'All' AND refer_to = 'Bataan General Hospital and Medical Center'

        $sql = "SELECT * FROM incoming_referrals WHERE ";

        $conditions = array();
        $others = false;

        if (!empty($ref_no)) {
            $conditions[] = "reference_num LIKE '%". $ref_no ."%'";
            $others = true;
        }

        if (!empty($last_name)) {
            $conditions[] = "patlast LIKE '%". $last_name ."%' ";
            $others = true;
        }

        if (!empty($first_name)) {
            $conditions[] = "patfirst LIKE '%". $first_name ."%' ";
            $others = true;
        }

        if (!empty($middle_name)) {
            $conditions[] = "patmiddle LIKE '%". $middle_name ."%' ";
            $others = true;
        }

        if (!empty($case_type)) {
            $conditions[] = "type = '" . $case_type . "'"; 
            $others = true;
        }

        if (!empty($agency)) {
            $conditions[] = "referred_by = '" . $agency . "'";
            $others = true;
        } 

        if(!empty($status)){
            $others = false;
        }

        if ($others === false) {
            if($status === 'All' || $status === 'default'){
                $conditions[] = "(status='Pending' OR status='On-Process' OR status='Deferred' OR status='Approved' OR status='Cancelled'
                OR status='Arrived' OR status='Checked' OR status='Admitted' OR status='Discharged' OR status='For Follow Up' OR status='Referred Back')";
            }
            else if($status === 'default'){
                $conditions[] = "status='Pending' OR status='On-Process'";
            }
            else{
                // $conditions[] = "(status='Pending' OR status='On-Process')";
                $conditions[] = "status = '" . $status . "'";
            }
        }

        if($others === true){
            $conditions[] = "(status='Pending' OR status='On-Process' OR status='Deferred' OR status='Approved' OR status='Cancelled'
                OR status='Arrived' OR status='Checked' OR status='Admitted' OR status='Discharged' OR status='For Follow Up' OR status='Referred Back')";
        }

        if (count($conditions) > 0) {
            $sql .= implode(" AND ", $conditions);
        } else {
            $sql .= "1";  // Always true condition if no input values provided.
        }
        
        $sql .= " AND refer_to = '" . $_SESSION["hospital_name"] . "'";
        // $sql = "SELECT * FROM incoming_referrals WHERE type = 'ER' AND status = 'All' AND refer_to = 'Bataan General Hospital and Medical Center'";
        // echo $sql;
        
        // echo 'false';
    }

    // echo $sql . ' , ' . $status;
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);  

    // echo count($data);

    $jsonString = json_encode($data);
    echo $jsonString;

    // echo gettype(json_encode($data));
?>