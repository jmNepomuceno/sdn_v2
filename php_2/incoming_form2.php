<?php
    session_start();
    include('../database/connection2.php');
    
    $timer_running = false;
    $post_value_reload = '';


    $sql = "SELECT * FROM incoming_referrals WHERE progress_timer IS NOT NULL AND refer_to = '" . $_SESSION["hospital_name"] . "'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if(count($data) > 0){
        $_SESSION['post_value_reload'] = 'true';
        $post_value_reload = $_SESSION['post_value_reload'];
    }


    $sql = "SELECT * FROM incoming_referrals WHERE logout_date!='null' AND refer_to = '" . $_SESSION["hospital_name"] . "' ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // echo '<pre>'; print_r($data); echo '</pre>';

    $logout_data = json_encode($data);
    // $login_data = $_SESSION['login_time'];


    // echo json_encode($data);
    // echo $_SESSION['login_time'];

    // $logout_data = 3;
    // $login_data = 3;
    // $_SESSION["sub_what"]

    if($_SESSION['running_hpercode'] != null || $_SESSION['running_hpercode'] != ""){
        $sql = "SELECT status_interdept FROM incoming_referrals WHERE hpercode='". $_SESSION['running_hpercode'] ."'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $status_interdept = $stmt->fetch(PDO::FETCH_ASSOC);

        $sql = "SELECT department FROM incoming_interdept WHERE hpercode='". $_SESSION['running_hpercode'] ."'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $department = $stmt->fetch(PDO::FETCH_ASSOC);

        // idk for refresh only?
        $current_pat_status =$status_interdept['status_interdept'] . ' - ' .  strtoupper($department['department']);
    }

    // *******************************************************************************
    // database showcase
    // $sql = "SELECT * FROM incoming_referrals WHERE refer_to='Bataan General Hospital and Medical Center' AND hpercode='BGHMC-0049'";
    // $stmt = $pdo->prepare($sql);
    // $stmt->execute();
    // $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // // echo '<pre>'; print_r($data); echo '</pre>';

    // $sql = "SELECT * FROM incoming_referrals WHERE hpercode='BGHMC-0049'";
    // $stmt = $pdo->prepare($sql);
    // $stmt->execute();
    // $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // refresh value of the data in database 
    
    // $sql = "UPDATE incoming_referrals SET status_interdept='', sent_interdept_time='', last_update='', status='Pending' WHERE hpercode='BGHMC-0049' ";
    // $stmt = $pdo->prepare($sql);
    // $stmt->execute();

    // $sql = "DELETE FROM incoming_interdept WHERE hpercode='BGHMC-0049' ";
    // $stmt = $pdo->prepare($sql);
    // $stmt->execute();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous"> -->
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    

    <link rel="stylesheet" href="../css/incoming_form.css">
</head>
<body>
    <!-- <button id="pending-stop-btn" class="border-2 border-black">Stop</button> -->
    
    <input id="timer-running-input" type="hidden" name="timer-running-input" value= <?php echo $timer_running ?>>
    <input id="post-value-reload-input" type="hidden" name="post-value-reload-input" value= <?php echo $post_value_reload ?>>
    <input id="post-value-reload-history-input" type="hidden" name="post-value-reload-history-input" value= <?php echo $_SESSION["sub_what"] ?>>

    <input id="running-timer-input" type="hidden" name="running-timer-input" value= <?php echo $_SESSION["running_timer"] ?>>
    <!-- $current_pat_status -->
    <?php
        if(isset($_SESSION['running_hpercode']) && ($_SESSION['running_hpercode'] != null || $_SESSION['running_hpercode'] != "")) {
            echo '<input id="pat-curr-stat-input" type="hidden" name="pat-curr-stat-input" value="' . $current_pat_status . '">';
        }
    ?>

    <div class="incoming-container">
        <div class="search-main-div">
            <div class="refer-no-div">
                <label>Referral No.</label>
                <input id="incoming-referral-no-search" type="textbox">
            </div>
        
            <div class="lname-search-div">
                <label>Last Name</label>
                <input id="incoming-last-name-search" type="textbox">
            </div>

            <div class="fname-search-div">
                <label>First Name</label>
                <input id="incoming-first-name-search" type="textbox">
            </div>

            <div class="mname-search-div">
                <label>Middle Name</label>
                <input id="incoming-middle-name-search" type="textbox">
            </div>

            <div class="caseType-search-div">
                <label>Case Type</label>
                <select id='incoming-type-select'>
                    <option value=""> None</option>
                    <option value="ER"> ER</option>
                    <option value="OB"> OB</option>
                    <option value="OPD"> OPD</option>
                    <option value="PCR"> PCR</option>
                </select>
            </div>


            <div class="agency-search-div">
                <label>Agency</label>
                <select id='incoming-agency-select'>
                   <?php 
                    $stmt = $pdo->prepare('SELECT hospital_name FROM sdn_hospital');
                    $stmt->execute();
            
                    echo '<option value=""> None </option>';
                    while($data = $stmt->fetch(PDO::FETCH_ASSOC)){
                        echo '<option value="' , $data['hospital_name'] , '">' , $data['hospital_name'] , '</option>';
                    } 
                   ?>
                </select>
            </div>


            <div class="status-search-div">
                <label>Status</label>
                <select id='incoming-status-select'>
                    <option value="default">Select</option>
                    <option value="Pending">Pending</option>
                    <option value="All"> All</option>
                    <option value="On-Process"> On-Process</option>
                    <option value="Deferred"> Deferred</option>
                    <option value="Approved"> Approved</option>
                    <option value="Cancelled"> Cancelled</option>
                    <option value="Arrived"> Arrived</option>
                    <option value="Checked"> Checked</option>
                    <option value="Admitted"> Admitted</option>
                    <option value="Discharged"> Discharged</option>
                    <option value="For follow"> For follow up</option>
                    <option value="Referred"> Referred Back</option>
                </select>
            </div>

            <div class="search-clear-btns-div">
                <button id='incoming-clear-search-btn'>Clear</button>
                <button id='incoming-search-btn'>Search</button>
            </div>
        </div>

        <section class="incoming-table">

            <table id="myDataTable" class="display">
                <thead>
                    <tr class="text-center">
                        <th id="refer-no">Reference No. </th>
                        <th>Patient's Name</th>
                        <th>Type</th>
                        <th>Agency</th>
                        <th>Date/Time</th>
                        <th>Response Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="incoming-tbody">
                    <?php
                        // SQL query to fetch data from your table
                        // echo  "here";
                        try{
                            $sql = "SELECT * FROM incoming_referrals WHERE (status='Pending' OR status='On-Process') AND refer_to='". $_SESSION["hospital_name"] ."' ORDER BY date_time ASC";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute();
                            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            // echo count($data);
                            $jsonData = json_encode($data);

                            $index = 0;
                            $previous = 0;
                            $loop = 0;
                            // Loop through the data and generate table rows
                            foreach ($data as $row) {
                                $type_color;
                                if($row['type'] == 'OPD'){
                                    $type_color = '#d77707';
                                }else if($row['type'] == 'OB'){
                                    $type_color = '#22c45e';
                                }else if($row['type'] == 'ER'){
                                    $type_color = '#0368a1';
                                }else if($row['type'] == 'PCR' || $row['type'] == 'Toxicology'){
                                    $type_color = '#cf3136';
                                }

                                if($previous == 0){
                                    $index += 1;
                                }else{
                                    if($row['reference_num'] == $previous){
                                        $index += 1;
                                    }else{
                                        $index = 1;
                                    }  
                                }
                                
                                // $style_tr = 'background:#33444d; color:white;';
                                $style_tr = '';
                                if($loop != 0 &&  $row['status'] === 'Pending'){
                                    $style_tr = 'opacity:0.5; pointer-events:none;';
                                }

                                // $waiting_time = "--:--:--";
                                $date1 = new DateTime($row['date_time']);
                                $waiting_time_bd = "";
                                if($row['reception_time'] != null){
                                    $date2 = new DateTime($row['reception_time']);
                                    $waiting_time = $date1->diff($date2);

                                    // if ($waiting_time->days > 0) {
                                    //     $differenceString .= $waiting_time->days . ' days ';
                                    // }

                                    $waiting_time_bd .= sprintf('%02d:%02d:%02d', $waiting_time->h, $waiting_time->i, $waiting_time->s);

                                }else{
                                    $waiting_time_bd = "00:00:00";
                                }

                                if($row['reception_time'] == ""){
                                    $row['reception_time'] = "00:00:00";
                                }

                                if($row['status_interdept'] != ""){
                                    $sql = "SELECT department FROM incoming_interdept WHERE hpercode='". $data[0]['hpercode'] ."'";
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->execute();
                                    $data = $stmt->fetch(PDO::FETCH_ASSOC);

                                    $row['status'] = $row['status_interdept'] . " - " . strtoupper($data['department']);
                                }
                                // processed time = progress time ng admin + progress time ng dept
                                // maiiwan yung timer na naka print, once na send na sa interdept
                                
                                $sql = "SELECT final_progress_time FROM incoming_interdept WHERE hpercode='BGHMC-0049'";
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute();
                                $interdept_time = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                
                                $total_time = "00:00:00";
                                if($interdept_time[0]['final_progress_time'] != "" && $row['sent_interdept_time'] != ""){
                                    list($hours1, $minutes1, $seconds1) = array_map('intval', explode(':', $interdept_time[0]['final_progress_time']));
                                    list($hours2, $minutes2, $seconds2) = array_map('intval', explode(':', $row['sent_interdept_time']));

                                    // Create DateTime objects in UTC with the provided hours, minutes, and seconds
                                    $date1 = new DateTime('1970-01-01 ' . $hours1 . ':' . $minutes1 . ':' . $seconds1, new DateTimeZone('UTC'));
                                    $date2 = new DateTime('1970-01-01 ' . $hours2 . ':' . $minutes2 . ':' . $seconds2, new DateTimeZone('UTC'));

                                    // Calculate the total milliseconds
                                    $totalMilliseconds = $date1->getTimestamp() * 1000 + $date2->getTimestamp() * 1000;

                                    // Create a new DateTime object in UTC with the total milliseconds
                                    $newDate = new DateTime('@' . ($totalMilliseconds / 1000), new DateTimeZone('UTC'));

                                    // Format the result in UTC time "HH:mm:ss"
                                    $total_time = $newDate->format('H:i:s');
                                }

                                echo '<tr class="tr-incoming" style="'. $style_tr .'">
                                        <td id="dt-refer-no"> ' . $row['reference_num'] . ' - '.$index.' </td>
                                        <td id="dt-patname">' . $row['patlast'] , ", " , $row['patfirst'] , " " , $row['patmiddle']  . '</td>
                                        <td id="dt-type" style="background:' . $type_color . ' ">' . $row['type'] . '</td>
                                        <td id="dt-phone-no">
                                            <label> Referred: ' . $row['referred_by'] . '  </label>
                                            <label> Landline: ' . $row['landline_no'] . ' </label>
                                            <label> Mobile: ' . $row['mobile_no'] . ' </label>
                                        </td>
                                        <td id="dt-turnaround"> 
                                            <i class="accordion-btn fa-solid fa-plus"></i>

                                            <label class="referred-time-lbl"> Referred: ' . $row['date_time'] . ' </label>
                                            <label class="reception-time-lbl"> Reception: '. $row['reception_time'] .'</label>
                                            <label class="sdn-proc-time-lbl"> SDN Processed: '. $row['sent_interdept_time'] .'</label>
                                            
                                            <div class="breakdown-div">
                                                <label class="interdept-proc-time-lbl"> Interdept Processed: '.$interdept_time[0]['final_progress_time'].'</label>
                                                <label class="processed-time-lbl"> Total Processed: '.$total_time.'  </label>  
                                                <label> Approval: 0000-00-00 00:00:00  </label>  
                                                <label> Deferral: 0000-00-00 00:00:00  </label>  
                                                <label> Cancelled: 0000-00-00 00:00:00  </label>  
                                                <label> Arrived: 0000-00-00 00:00:00  </label>  
                                                <label> Checked: 0000-00-00 00:00:00  </label>  
                                                <label> Admitted: 0000-00-00 00:00:00  </label>  
                                                <label> Discharged: 0000-00-00 00:00:00  </label>  
                                                <label> Follow up: 0000-00-00 00:00:00  </label>  
                                                <label> Ref. Back: 0000-00-00 00:00:00  </label>  
                                            </div>
                                        </td>
                                        <td id="dt-stopwatch">
                                            <div id="stopwatch-sub-div">
                                                Processing: <span class="stopwatch">00:00:00</span>
                                            </div>
                                        </td>
                                        
                                        <td id="dt-status">
                                            <div> 
                                                
                                                <label class="pat-status-incoming">' . $row['status'] . '</label>
                                                <i class="pencil-btn fa-solid fa-pencil"></i>
                                                <input class="hpercode" type="hidden" name="hpercode" value= ' . $row['hpercode'] . '>

                                            </div>
                                        </td>
                                    </tr>';

                                $previous = $row['reference_num'];
                                $loop += 1;
                            }

                            // Close the database connection
                            $pdo = null;
                        }
                        catch(PDOException $e){
                            echo "asdf";
                        }
                    ?>
                </tbody>
            </table>
        </section>
    </div>

    <!-- MODAL -->
    

    
    <div class="modal fade" id="pendingModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button>Print</button>
                    <button id="close-pending-modal" data-bs-dismiss="modal">Close</button>
                    <!-- <span aria-hidden="true">&times;</span> --> 
                </div>
                <div  class="modal-body-incoming">
                    <div class="status-form-div">
                        <label id="status-bg-div">Status: </label>
                        <label  id="pat-status-form">Pending</label>
                    </div>
                                
                    <div id='approval-form'>
                        <label id="approval-title-div">Approval Form</label>
                            
                        <div class="approval-main-content">

                            <label id="case-cate-title">Case Category</label>
                            <select id="approve-classification-select">
                                <option value="">Select</option>
                                <option value="Primary">Primary</option>
                                <option value="Secondary">Secondary</option>
                                <option value="Tertiary">Tertiary</option>
                            </select>

                            <label id="admin-action-title">Emergency Room Administrator Action</label>
                            <textarea id="eraa"></textarea>

                            <div id="pre-text">
                                <label class="pre-emp-text">+ May transfer patient once stable.</label>
                                <label class="pre-emp-text">+ Please attach imaging and laboratory results to the referral letter.</label>
                                <label class="pre-emp-text">+ Hook to oxygen support and maintain saturation at >95%.</label>
                                <label class="pre-emp-text">+ Start venoclysis with appropriate intravenous fluids.</label>
                                <label class="pre-emp-text">+ Insert nasogastric tube(NGT).</label>
                                <label class="pre-emp-text">+ Insert indwelling foley catheter(IFC).</label>
                                <label class="pre-emp-text">+ Thank you for your referral.</label>
                            </div>

                            <!-- <label class="ml-[2%] font-semibold">Action</label>
                            <select id="approved-action-select" class="border border-slate-800 w-[95%] ml-[2%] rounded-sm outline-none">
                                <option value="">Pending</option>
                                <option value="Approve">Approve</option>
                                <option value="Defer">Defer</option>
                            </select> -->
                        </div> 

                        <div id="approval-form-btns">
                            <button id="inter-dept-referral-btn"> Interdepartamental Referral </button>
                            <button id="imme-approval-btn"> Immediate Approval </button>
                        </div>
                    </div>

                    <div class="interdept-div">
                        <div id="inter-dept-stat-form-div" class="status-form-div">
                            <label id="status-bg-div">Inter-Department Referral </label>
                        </div>
                        <label for="" id="inter-dept-lbl">Department: </label>
                        <select id="inter-depts-select" style="cursor:pointer;">
                            <option value="">Select</option>
                            <option value="surgery"> Surgery </option>
                            <option value="ob"> OB </option>
                            <option value="im"> Internal Medicine </option>
                            <option value="fm"> Fam Med </option>
                        </select>
                        <div class="int-dept-btn-div">
                            <button id="int-dept-btn-forward">Send / Forward</button>
                        </div>
                    </div>

                    <div class="interdept-div-v2">
                        <div id="inter-dept-stat-form-div" class="status-form-div">
                            <label id="status-bg-div">Interdepartment: Surgery - Status </label>
                        </div>
                        <!-- <label for="" id="v2-stat"> <span id="span-dept">Surgery</span> - Processing - <span id="span-time">00:07:09</span></label> -->
                        <label for="" id="v2-stat"> <span id="span-dept">Surgery</span>  <span id="span-status">Pending</span> <span id="span-time">00:00:00</span></span></label>
                        <label id="v2-update-stat">Updated 0 second(s) ago...</label>
                        <div class="int-dept-btn-div-v2">
                            <button id="cancel-btn">Cancel</button>
                            <button id="final-approve-btn">Proceed to Approval</button>
                        </div>
                    </div>

                    <div class="referral-details">
                        <div id="inter-dept-stat-form-div" class="status-form-div">
                            <label id="status-bg-div">Referral Details </label>
                        </div>
                        <div class="ul-div">
                            
                        </div>
                        <!-- <ul class="list-none flex flex-col space-y-2">
                            <li><label class="font-bold">Referring Agency:</label><span id="refer-agency" class="break-words"></span></li>
                            <li><label class="font-bold">Reason for Referral:</label><span id="refer-reason" class="break-words"></span></li><br>
                
                            <li><label class="font-bold">Name:</label><span id="pending-name"  class="break-words">This is where you put the data</span></li>
                            <li><label class="font-bold">Birthday:</label><span id="pending-bday" class="break-words">This is where you put the data</span></li>
                            <li><label class="font-bold">Age:</label><span id="pending-age" class="break-words">This is where you put the data</span></li>
                            <li><label class="font-bold">Sex:</label><span id="pending-sex" class="break-words">This is where you put the data</span></li>
                            <li><label class="font-bold">Civil Status:</label><span id="pending-civil" class="break-words">This is where you put the data</span></li>
                            <li><label class="font-bold">Religion:</label><span id="pending-religion" class="break-words">This is where you put the data</span></li>
                            <li><label class="font-bold">Address:</label><span id="pending-address" class="break-words">This is where you put the data</span></li><br>
                
                            <li><label class="font-bold">Parent/Guardian:</label><span id="pending-parent" class="break-words">This is where you put the data</span></li>
                            <li><label class="font-bold">PHIC Member:</label><span id="pending-phic" class="break-words">This is where you put the data</span></li>
                            <li><label class="font-bold">Mode of Transport:</label><span id="pending-transport" class="break-words">This is where you put the data</span></li>
                            <li><label class="font-bold">Date/Time Admitted:</label><span id="pending-admitted" class="break-words">This is where you put the data</span></li>
                            <li><label class="font-bold">Referring Doctor:</label><span id="pending-referring-doc" class="break-words">This is where you put the data</span></li>
                            <li><label class="font-bold">Contact #:</label><span id="pending-contact-no" class="break-words">This is where you put the data</span></li><br>

                            <li class="pending-type-ob hidden"><label class="font-bold underline">OB-Gyne</label><span id="pending-ob" class="break-words">This is where you put the data</span></li>
                            <li class="pending-type-ob hidden"><label class="font-bold">Last Menstrual Period:</label><span id="pending-last-mens" class="break-words">This is where you put the data</span></li>
                            <li class="pending-type-ob hidden"><label class="font-bold">Age of Gestation</label><span id="pending-gestation" class="break-words">This is where you put the data</span></li><br>
                
                            <li><label class="font-bold">Chief Complaint and History:</label><span id="pending-complaint-history" class="break-words">This is where you put the data</span></li><br>
                
                            <li><label class="font-bold">Physical Examination</label><span id="pending-pe" class="break-words">This is where you put the data</span></li>
                            <li><label class="font-bold">Blood Pressure:</label><span id="pending-bp" class="break-words">This is where you put the data</span></li>
                            <li><label class="font-bold">Heart Rate:</label><span id="pending-hr" class="break-words">This is where you put the data</span></li>
                            <li><label class="font-bold">Respiratory Rate:</label><span id="pending-rr" class="break-words">This is where you put the data</span></li>
                            <li><label class="font-bold">Temperature:</label><span id="pending-temp" class="break-words">This is where you put the data</span></li>
                            <li><label class="font-bold">Weight:</label><span id="pending-weight" class="break-words">This is where you put the data</span></li><br>

                            <li class="pending-type-ob hidden"><label class="font-bold">Fetal Heart Tone:</label><span id="pending-heart-tone" class="break-words">This is where you put the data</span></li>
                            <li class="pending-type-ob hidden"><label class="font-bold">Fundal Height:</label><span id="pending-fundal-height" class="break-words">This is where you put the data</span></li><br>

                            <li class="pending-type-ob hidden"><label class="font-bold underline">Internal Examination</label><span id="pending-ie" class="break-words">This is where you put the data</span></li>
                            <li class="pending-type-ob hidden"><label class="font-bold">Cervical Dilatation:</label><span id="pending-cd" class="break-words">This is where you put the data</span></li>
                            <li class="pending-type-ob hidden"><label class="font-bold">Bag of Water:</label><span id="pending-bag-water" class="break-words">This is where you put the data</span></li>
                            <li class="pending-type-ob hidden"><label class="font-bold">Presentation:</label><span id="pending-presentation" class="break-words">This is where you put the data</span></li>
                            <li class="pending-type-ob hidden"><label class="font-bold">Others:</label><span id="pending-others" class="break-words">This is where you put the data</span></li><br>
                
                            <li><label class="font-bold">Pertinent PE Findings:</label><span id="pending-p-pe-find" class="break-words">This is where you put the data</span></li><br>
                
                            <li><label class="font-bold">Impression / Diagnosis:</label><span id="pending-diagnosis" class="break-words">This is where you put the data</span></li>
                        </ul> -->
                    </div>
                </div>

                
                <div class="modal-footer">
                    <!-- <button id="ok-modal-btn-incoming" type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2" data-bs-dismiss="modal">OK</button>
                    <button id="yes-modal-btn-incoming" type="button" class="hidden bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2" data-bs-dismiss="modal">Yes</button>
                 -->
                </div>
            </div>
        </div>
    </div>



    </div>

    <!-- Modal -->
    <div class="modal fade" id="myModal-incoming" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <div class="flex flex-row justify-between items-center">
                    <h5 id="modal-title-incoming" class="modal-title-incoming" id="exampleModalLabel">Warning</h5>
                    <i id="modal-icon" class="fa-solid fa-triangle-exclamation"></i>
                    <!-- <i class="fa-solid fa-circle-check"></i> -->
                </div>
                <button type="button" class="close text-3xl" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modal-body-incoming" class="modal-body-incoming ml-2">
                Please input at least one value in any field.
            </div>
            <div class="modal-footer">
                <button id="ok-modal-btn-incoming" type="button" data-bs-dismiss="modal">OK</button>
                <button id="yes-modal-btn-incoming" type="button" data-bs-dismiss="modal">Yes</button>
            </div>
            </div>
        </div>
    </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>  -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script type="text/javascript"  charset="utf8" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>

    <script src="../js_2/incoming_form2.js?v= <?php echo time(); ?>"></script>

    <script>
    // $(document).ready(function () {
    //     $('#myDataTable').DataTable();
    // });
        var jsonData = <?php echo $jsonData; ?>;
        // var logout_data =  echo $logout_data; ?>;
        var login_data = "<?php echo $_SESSION['login_time']; ?>";

        // console.log(logout_data)
    
    </script>
</body>
</html>