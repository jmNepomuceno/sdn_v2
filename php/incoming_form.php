<?php
    session_start();
    include('../database/connection2.php');
    // echo isset($_SESSION["process_timer"]);

    // echo count($_SESSION["process_timer"]);   
    $timer_running = false;

    $post_value_reload = '';

    if(count($_SESSION["process_timer"]) >= 1){
        // echo "here";
        // for($i = 0; $i < count($_SESSION["process_timer"]); $i++){
        //     foreach ($_SESSION["process_timer"][$i] as $key => $value) {
        //         echo "Key: $key, Value: $value<br>";
        //     }
        // }
        $timer_running = true;
    }

    $sql = "SELECT * FROM incoming_referrals WHERE progress_timer IS NOT NULL AND refer_to = '" . $_SESSION["hospital_name"] . "'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if(count($data) > 0){
        $_SESSION['post_value_reload'] = 'true';
        $post_value_reload = $_SESSION['post_value_reload'];
    }

    // echo '<pre>'; print_r($data); echo '</pre>';

    // echo $timer_running;
    // if(count($_SESSION["process_timer"]) >= 1){
    //     for($i = 0; $i < count($_SESSION["process_timer"]); $i++){
    //         echo $_SESSION['process_timer'][$i]['elapsedTime'] . '<br/>';
    //     }
    // }

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
    

    <link rel="stylesheet" href="../output.css">

    <style>
        .dataTables_length {
            margin-top:0;
            margin-bottom:5px;
        }   

        .tr-incoming {
            height: 61px;
            transition-property: all;
            /* transition-delay: 150ms; */
            transition-duration: 300ms;
            transition-timing-function: ease;
        }

        .breakdown-div{
            display:none;
            flex-direction: column;
            justify-content: space-around;
            align-items: center;
            width:100%;
            height: 250px;
        }
    </style>

</head>
<body class="overflow-hidden">
    <!-- <button id="pending-stop-btn" class="border-2 border-black">Stop</button> -->
    <input id="timer-running-input" type="hidden" name="timer-running-input" value=<?php echo $timer_running ?>>
    <input id="post-value-reload-input" type="hidden" name="post-value-reload-input" value=<?php echo $post_value_reload ?>>
    <input id="post-value-reload-history-input" type="hidden" name="post-value-reload-history-input" value=<?php echo $_SESSION["sub_what"] ?>>

    <!-- <input id="timer-running-input" type="hidden" name="timer-running-input" value="false"> -->

    <div class="w-full h-full flex flex-col justify-start items-center bg-white">
        <div class="w-full h-[10%] flex flex-row justify-around items-center mt-8 ">

            <div class="w-[10%] h-[100%] flex flex-col justify-center items-left">
                <label class="ml-1 font-bold">Referral No.</label>
                <input id="incoming-referral-no-search" type="textbox" class="w-full border-2 border-[#bfbfbf] rounded-md outline-none">
            </div>
            

            <div class="w-[12%] h-[100%] flex flex-col justify-center items-left">
                <label class="ml-1 font-bold">Last Name</label>
                <input id="incoming-last-name-search" type="textbox" class="w-full border-2 border-[#bfbfbf] rounded-md w-[100%] outline-none">
            </div>

            <div class="w-[10%] h-[100%] flex flex-col  justify-center items-left">
                <label class="ml-1 font-bold">First Name</label>
                <input id="incoming-first-name-search" type="textbox" class="w-full border-2 border-[#bfbfbf] rounded-md w-[100%] outline-none">
            </div>

            <div class="w-[12%] h-[100%] flex flex-col  justify-center items-left">
                <label class="ml-1 font-bold">Middle Name</label>
                <input id="incoming-middle-name-search" type="textbox" class="w-full border-2 border-[#bfbfbf] rounded-md w-[100%] outline-none">
            </div>

            <div class="w-[6%] h-[100%] flex flex-col  justify-center items-left">
                <label class="ml-1 font-bold ">Case Type</label>
                <select id='incoming-type-select' class="w-full border-2 border-[#bfbfbf] rounded-md">
                    <option value=""> None</option>
                    <option value="ER"> ER</option>
                    <option value="OB"> OB</option>
                    <option value="OPD"> OPD</option>
                    <option value="PCR"> PCR</option>
                </select>
            </div>


            <div class="w-[15%] h-[100%] flex flex-col  justify-center items-left">
                <label class="ml-1 font-bold">Agency</label>
                <select id='incoming-agency-select' class="w-full border-2 border-[#bfbfbf] rounded-md w-[100%">
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


            <div class="w-[9%] h-[100%] flex flex-col  justify-center items-left">
                <label class="ml-1 font-bold ">Status</label>
                <select id='incoming-status-select' class="w-full border-2 border-[#bfbfbf] rounded-md">
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

            <div class="w-[15%] h-full flex flex-row justify-around items-center font-bold text-white">
                <button id='incoming-clear-search-btn' class="w-[100px] h-[50%] rounded bg-[#2f3e46] opacity-30 pointer-events-none">Clear</button>
                <button id='incoming-search-btn' class="w-[100px] h-[50%] rounded bg-[#2f3e46]">Search</button>
            </div>
        </div>

        <section class=" w-[98%] h-[80%] flex flex-row justify-center items-center rounded-lg border-2 border-[#bfbfbf] mt-3">
            
            <div class="w-[98%] h-[95%]  flex flex-col justify-start rounded-lg overflow-y-auto">
                <table id="myDataTable" class="display">
                    <thead>
                        <tr class="text-center">
                            <th id="yawa" class="w-[20%] bg-[#e6e6e6]">Reference No. </th>
                            <th class="w-[17%] bg-[#e6e6e6]">Patient's Name</th>
                            <th class="w-[7%] bg-[#e6e6e6]">Type</th>
                            <th class="w-[17%] bg-[#e6e6e6]">Agency</th>
                            <th class="w-[15%] bg-[#e6e6e6]">Date/Time</th>
                            <th class="w-[15%] bg-[#e6e6e6]">Response Time</th>
                            <th class="w-[10%] bg-[#e6e6e6]"> Status</th>
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
                                        $type_color = 'bg-amber-600';
                                    }else if($row['type'] == 'OB'){
                                        $type_color = 'bg-green-500';
                                    }else if($row['type'] == 'ER'){
                                        $type_color = 'bg-sky-700';
                                    }else if($row['type'] == 'PCR' || $row['type'] == 'Toxicology'){
                                        $type_color = 'bg-red-600';
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
                                    
                                    $style_tr = '';
                                    if($loop != 0 &&  $row['status'] === 'Pending'){
                                        $style_tr = 'opacity-50 pointer-events-none';
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

                                    echo '<tr class="tr-incoming '. $style_tr .' ">
                                            <td class="text-xs"> ' . $row['reference_num'] . ' - '.$index.' </td>
                                            <td>' . $row['patlast'] , ", " , $row['patfirst'] , " " , $row['patmiddle']  . '</td>
                                            <td class="h-full font-bold text-center ' . $type_color . ' ">' . $row['type'] . '</td>
                                            <td>
                                                <label class="text-xs ml-1"> Referred: ' . $row['referred_by'] . '  </label>
                                                <div class="flex flex-row justify-start items-center"> 
                                                    <label class="text-[7.7pt] ml-1"> Landline: ' . $row['landline_no'] . ' </label>
                                                    <label class="text-[7.7pt] ml-1"> Mobile: ' . $row['mobile_no'] . ' </label>
                                                </div>
                                            </td>
                                            <td class="flex flex-col justify-center items-left relative"> 
                                                <i class="absolute bottom-0 right-0 accordion-btn fa-solid fa-plus border-2 border-[#a4b7c1] p-1 text-xs rounded bg-[#d1dbe0] opacity-40 cursor-pointer hover:opacity-100"></i>

                                                <label class="referred-time-lbl text-sm w-[95%] border-b border-[#bfbfbf]"> Referred: ' . $row['date_time'] . ' </label>
                                                <label class="queue-time-lbl text-sm w-[95%] border-b border-[#bfbfbf] mt-1"> Queue Time: ' . $waiting_time_bd . ' </label>
                                                <label class="reception-time-lbl text-sm w-[95%] border-b border-[#bfbfbf] mt-1"> Reception: '. $row['reception_time'] .'</label>
                                                
                                                <div class="breakdown-div">
                                                    <label class="processed-time-lbl text-sm w-full border-b border-[#bfbfbf] mt-1"> Processed: 00:00:00  </label>  
                                                    <label class="text-sm w-full border-b border-[#bfbfbf] mt-1"> Approval: 0000-00-00 00:00:00  </label>  
                                                    <label class="text-sm w-full border-b border-[#bfbfbf] mt-1"> Deferral: 0000-00-00 00:00:00  </label>  
                                                    <label class="text-sm w-full border-b border-[#bfbfbf] mt-1"> Cancelled: 0000-00-00 00:00:00  </label>  
                                                    <label class="text-sm w-full border-b border-[#bfbfbf] mt-1"> Arrived: 0000-00-00 00:00:00  </label>  
                                                    <label class="text-sm w-full border-b border-[#bfbfbf] mt-1"> Checked: 0000-00-00 00:00:00  </label>  
                                                    <label class="text-sm w-full border-b border-[#bfbfbf] mt-1"> Admitted: 0000-00-00 00:00:00  </label>  
                                                    <label class="text-sm w-full border-b border-[#bfbfbf] mt-1"> Discharged: 0000-00-00 00:00:00  </label>  
                                                    <label class="text-sm w-full border-b border-[#bfbfbf] mt-1"> Follow up: 0000-00-00 00:00:00  </label>  
                                                    <label class="text-sm w-full border-b border-[#bfbfbf] mt-1"> Ref. Back: 0000-00-00 00:00:00  </label>  
                                                </div>
                                            </td>
                                            <td>
                                                <div class="flex flex-row justify-around items-center">
                                                    Processing: 
                                                    <div> 
                                                        <div class="stopwatch">00:00:00</div>
                                                    </div>
                                                </div>
                                            </td>
                                            
                                            <td class=" font-bold text-center bg-gray-500">
                                                <div class=" flex flex-row justify-around items-center"> 
                                                    
                                                    <label class="pat-status-incoming">' . $row['status'] . '</label>
                                                    <i class="pencil-btn fa-solid fa-pencil cursor-pointer hover:text-white"></i>
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

            </div>
        </section>
    </div>

    <!-- MODAL -->
    <div id="pendingModal" class="fixed inset-0 flex items-center justify-center z-10 delay-100 hidden">
        <!-- Modal background -->
        <div class="modal-background fixed inset-0 bg-black opacity-50"></div>
            
            <!-- Modal content -->
            <div class="bg-black p-4 rounded shadow-md z-20  w-[1000px] h-[900px] flex flex-col justify-start items-start overflow-y-auto ">
                <div class="flex flex-row justify-between items-center w-full">
                    <label id="pending-type-lbl" class="bg-sky-600  w-[10%] justify-center text-white rounded-sm text-center"></label>
                    <div class="flex flex-row justify-between items-center w-[25%]">
                        <button class="bg-sky-600  w-[45%] justify-center text-white rounded-sm text-center">Print</button>
                        <button id="close-pending-modal" class="bg-sky-600  w-[45%] justify-center text-white rounded-sm text-center">Close</button>
                    </div>
                </div> 
                <!-- update the form -->
                <div class=" w-full h-[100px] mt-[1.5%] rounded-sm bg-white flex flex-col items-start justify-start">
                    <div id="status-bg-div" class=" w-[100%] h-[40%] bg-gray-600 rounded-sm -mt-[0.1%] text-white font-semibold text-md flex flex-row justify-start items-center">
                        <label class="ml-[1%]">Status</label>
                    </div>
                   <label class="text-gray-500 font-bold ml-[1%] text-glow text-3xl" id="pat-status-form">Pending</label>
                </div>

                <div id="temp-forward-form" class="w-[100%] h-[18%] bg-blue-400 rounded-sm mt-2 text-black font-bold text-md flex flex-row justify-between items-center">
                    <label class="text-black ml-[1%]">Patient Forwarding Form</label>
                    <button id="forward-continue-btn" class="bg-[#065cc6] font-semibold w-[10%] mr-2 rounded-sm text-black"> Continue </button>
                </div>

                <div id="pat-forward-form" class="bg-white w-full h-[180px] max-h-[180px] min-h-[180px] mt-[1.5%] rounded-sm flex flex-col hidden">
                    <div class=" w-[100%] h-[18%] bg-blue-400 rounded-sm -mt-[0.1%] text-black font-bold text-md flex flex-row justify-start items-center">
                        <label class="text-black ml-[1%]">Patient Forwarding Form</label>
                    </div>
                        
                    <div class="flex flex-col justify-center items-start w-full  h-[45%] mt-[35px]">
                        <label class="ml-[2%] font-semibold">Action</label>
                        <select class="border border-slate-800 w-[95%] ml-[2%] rounded-sm">
                            <option>Select</option>
                            <option>Emergency Room</option>
                            <option>OB-GYNE</option>
                            <option>Out-Patient Department</option>
                        </select>
                    </div> 

                    <div class="flex flex-row justify-end items-center w-full">
                        <button id="main-forward-btn" class="bg-blue-400 font-semibold w-[10%] mr-4 rounded-sm text-black"> Forward </button>
                        <button id="forward-cancel-btn" class="bg-blue-400 font-semibold w-[10%] mr-4 rounded-sm text-black"> Cancel </button>
                    </div>
                </div>

                <!-- <div id="pending-start-div" class="bg-white w-full h-[50px] max-h-[50px] min-h-[50px] mt-[1.5%] rounded-sm flex flex-row justify-start items-center">
                    <button id="pending-start-btn" class="bg-green-400 font-semibold w-[12%] h-[80%] ml-2 rounded-sm text-black"> Start </button>
                </div> -->

                <!-- 2 approval form , shows when the status is On-Process -->
                <div id='approval-form' class="bg-white w-full h-[500px] max-h-[500px] min-h-[500px] mt-[1.5%] rounded-sm flex flex-col hidden">
                    <div class=" w-[100%] h-[7%] bg-blue-400 rounded-sm -mt-[0.1%] text-black font-bold text-md flex flex-row justify-start items-center">
                        <label class="text-black ml-[1%]">Approval Form</label>
                    </div>
                        
                    <div class="flex flex-col justify-evenly items-start w-full h-[85%]">
                        <label class="ml-[2%] font-semibold">Case Category</label>
                        <select id="approve-classification-select" class="border border-slate-800 w-[95%] ml-[2%] rounded-sm outline-none">
                            <option value="">Select</option>
                            <option value="Primary">Primary</option>
                            <option value="Secondary">Secondary</option>
                            <option value="Tertiary">Tertiary</option>
                        </select>

                        <label class="ml-[2%] font-semibold">Emergency Room Administrator Action</label>
                        <textarea id="eraa" class="border-2 border-[#bfbfbf] w-[95%] h-[30%] ml-4 resize-none outline-none"></textarea>

                        <div class="w-[95%] h-[40%] ml-4 font-bold flex flex-col justify-start items-left">
                            <label class="pre-emp-text cursor-pointer w-max">+ May transfer patient once stable</label>
                            <label class="pre-emp-text cursor-pointer w-max">+ Please attach imaging and laboratory results to the referral letter.</label>
                            <label class="pre-emp-text cursor-pointer w-max">+ Hook to oxygen support and maintain saturation at >95%.</label>
                            <label class="pre-emp-text cursor-pointer w-max">+ Start venoclysis with appropriate intravenous fluids.</label>
                            <label class="pre-emp-text cursor-pointer w-max">+ Insert nasogastric tube(NGT).</label>
                            <label class="pre-emp-text cursor-pointer w-max">+ Insert indwelling foley catheter(IFC).</label>
                            <label class="pre-emp-text cursor-pointer w-max">+ Thank you for your referral.</label>
                        </div>

                        <label class="ml-[2%] font-semibold">Action</label>
                        <select id="approved-action-select" class="border border-slate-800 w-[95%] ml-[2%] rounded-sm outline-none">
                            <option value="">Pending</option>
                            <option value="Approve">Approve</option>
                            <option value="Defer">Defer</option>
                        </select>
                    </div> 

                    <div class="flex flex-row justify-end items-center w-full mt-2">
                        <button id="pending-approved-btn" class="bg-blue-400 font-semibold w-[10%] mr-4 rounded-sm text-black" data-bs-toggle="modal" data-bs-target="#myModal-incoming"> Approve </button>
                        <!-- <button id="pending-approved-btn" class="bg-blue-400 font-semibold w-[10%] mr-6 rounded-sm text-black"> Approved </button> -->
                    </div>
                </div>

                <!-- 3 arrival form, shows when the status is Approved -->
                <div id="arrival-form" class="bg-white w-full h-[300px] max-h-[300px] min-h-[300px] mt-[1.5%] rounded-sm flex flex-col hidden">
                    <div class=" w-[100%] h-[18%] bg-blue-400 rounded-sm -mt-[0.1s%] text-black font-bold text-md flex flex-row justify-start items-center">
                        <label class="text-black ml-[1%]">Arrival Form</label>
                    </div>
                        
                    <div class="flex flex-col justify-evenly items-start w-full h-[80%] mt-1">
                        <label class="font-bold text-xl ml-4">Arrival Note</label>
                        <textarea id="arrival-text-area" class="border-2 border-[#bfbfbf] w-[95%] h-[55%] ml-4 resize-none outline-none"></textarea>
                        <button id="arrival-submit" class="bg-green-400 font-semibold w-[12%] h-[30px] ml-4 rounded-sm text-black" data-bs-toggle="modal" data-bs-target="#myModal-incoming"> Submit </button>     
                    </div> 
                </div>
                <!-- 3 approval details  -->
                <div id="approval-details" class="bg-white w-full h-[180px] max-h-[180px] min-h-[180px] mt-[1.5%] rounded-sm flex flex-col hidden">
                    <div class=" w-[100%] h-[18%] bg-blue-400 rounded-sm -mt-[0.1s%] text-black font-bold text-md flex flex-row justify-start items-center">
                        <label class="text-black ml-[1%]" id="approval-details-id">Approval Details</label>
                    </div>
                        
                    <div class="flex flex-col justify-center items-start w-full h-[80%] mt-1">
                        <div class="w-full h-[50%] flex flex-col justify-center items-start">
                            <label class="font-bold text-xl ml-4">Emergency Room Administrator Action</label>
                            <p class="ml-4" id="admin-action-lbl">ffff</p>
                        </div>
                        <div class="w-full h-[50%] flex flex-col justify-center items-start">
                            <label class="font-bold text-xl ml-4">Case Category</label>
                            <label class="ml-4" id="classification-lbl">asdfasdfasdfasdfasd</label>
                        </div>
                    </div> 
                </div>
                <!-- 3 cancel form  -->
                <div id="cancel-form" class="bg-white w-full h-[300px] max-h-[300px] min-h-[300px] mt-[1.5%] rounded-sm flex flex-col hidden">
                    <div class=" w-[100%] h-[18%] bg-[#ff4d4d] rounded-sm -mt-[0.1s%] text-black font-bold text-md flex flex-row justify-start items-center">
                        <label class="text-black ml-[1%]">Cancellation Form</label>
                    </div>
                        
                    <div class="flex flex-col justify-evenly items-start w-full h-[80%] mt-1">
                        <label class="font-bold text-xl ml-4">Cancellation Reasons</label>
                        <textarea id="cancellation-textarea" class="border-2 border-[#bfbfbf] w-[95%] h-[55%] ml-4 resize-none outline-none"></textarea>
                        <button id="cancel-submit" class="bg-[#ff4d4d] font-semibold w-[12%] h-[30px] ml-4 rounded-sm text-black" data-bs-toggle="modal" data-bs-target="#myModal-incoming"> Cancel </button>     
                    </div> 
                </div>
                

                <!-- 4 check up, shows when the status is arrived  -->
                <div id='checkup-form' class="bg-white w-full h-[300px] max-h-[300px] min-h-[300px] mt-[1.5%] rounded-sm flex flex-col hidden">
                    <div class=" w-[100%] h-[15%] bg-blue-400 rounded-sm -mt-[0.1%] text-black font-bold text-md flex flex-row justify-start items-center">
                        <label class="text-black ml-[1%]">Check-up Form</label>
                    </div>
                        
                    <div class="flex flex-col justify-evenly items-start w-full h-[85%]">
                        <label class="ml-[2%] font-semibold">Check-up Note</label>
                        <textarea id="checkup-textarea" class="border-2 border-[#bfbfbf] w-[95%] h-[50%] ml-4 resize-none outline-none"></textarea>

                        <label class="ml-[2%] font-semibold">Action</label>
                        <select id="checkup-classification-select" class="border border-slate-800 w-[95%] ml-[2%] rounded-sm outline-none">
                            <option value="">Select</option>
                            <option value="Primary">Check</option>
                            <option value="Secondary">ewan</option>
                            <option value="Tertiary">ewan</option>
                        </select>
                    </div> 

                    <div class="flex flex-row justify-end items-center w-full mb-2">
                        <button id="check-submit-btn" class="bg-blue-400 font-semibold w-[10%] mr-4 rounded-sm text-black" data-bs-toggle="modal" data-bs-target="#myModal-incoming"> Submit </button>
                        <!-- <button id="pending-approved-btn" class="bg-blue-400 font-semibold w-[10%] mr-6 rounded-sm text-black"> Approved </button> -->
                    </div>
                </div>
                <!-- 4 arrival details  -->
                <div id="arrival-details" class="bg-white w-full h-[180px] max-h-[180px] min-h-[180px] mt-[1.5%] rounded-sm flex flex-col hidden">
                    <div class=" w-[100%] h-[18%] bg-blue-400 rounded-sm -mt-[0.1s%] text-black font-bold text-md flex flex-row justify-start items-center">
                        <label class="text-black ml-[1%]">Arrival Note</label>
                    </div>
                        
                    <div class="flex flex-col justify-center items-start w-full h-[80%] mt-1">
                        <p>The dog is barking so loud that the other dog from the block also started barking.</p>
                    </div> 
                </div>

                <!-- 5 follow-up/refer back form, shows when the status is Check -->
                <div id="followup-form" class="bg-white w-full h-[300px] max-h-[300px] min-h-[300px] mt-[1.5%] rounded-sm flex flex-col hidden">
                    <div class=" w-[100%] h-[15%] bg-blue-400 rounded-sm -mt-[0.1%] text-black font-bold text-md flex flex-row justify-start items-center">
                        <label class="text-black ml-[1%]">Follow-Up / Refer Back Form</label>
                    </div>
                        
                    <div class="flex flex-col justify-evenly items-start w-full h-[85%]">
                        <label class="ml-[2%] font-semibold">Follow-Up / Refer Back Note</label>
                        <textarea id="follow-textarea" class="border-2 border-[#bfbfbf] w-[95%] h-[50%] ml-4 resize-none outline-none"></textarea>

                        <label class="ml-[2%] font-semibold">Action</label>
                        <select id="follow-classification-select" class="border border-slate-800 w-[95%] ml-[2%] rounded-sm outline-none">
                            <option value="">Select</option>
                            <option value="Follow-Up">Follow-Up</option>
                            <option value="Refer Back">Refer Back</option>
                        </select>
                    </div> 

                    <div class="flex flex-row justify-end items-center w-full mb-2">
                        <button id="follow-submit-btn" class="bg-blue-400 font-semibold w-[10%] mr-4 rounded-sm text-black"> Submit </button>
                        <!-- <button id="pending-approved-btn" class="bg-blue-400 font-semibold w-[10%] mr-6 rounded-sm text-black"> Approved </button> -->
                    </div>
                </div>

                <!-- 6 discharged form, shows when the status is admitted -->
                <div id="discharged-form" class="bg-white w-full h-[300px] max-h-[300px] min-h-[300px] mt-[1.5%] rounded-sm flex flex-col hidden">
                    <div class=" w-[100%] h-[15%] bg-blue-400 rounded-sm -mt-[0.1%] text-black font-bold text-md flex flex-row justify-start items-center">
                        <label class="text-black ml-[1%]">Discharged Form</label>
                    </div>
                        
                    <div class="flex flex-col justify-evenly items-start w-full h-[85%]">
                        <label class="ml-[2%] font-semibold">Discharged Note</label>
                        <textarea id="discharged-textarea" class="border-2 border-[#bfbfbf] w-[95%] h-[50%] ml-4 resize-none outline-none"></textarea>

                        <label class="ml-[2%] font-semibold">Action</label>
                        <select id="discharged-classification-select" class="border border-slate-800 w-[95%] ml-[2%] rounded-sm outline-none">
                            <option value="">Select</option>
                            <option value="Discharged">Discharged</option>
                        </select>
                    </div> 

                    <div class="flex flex-row justify-end items-center w-full mb-2">
                        <button id="follow-submit-btn" class="bg-blue-400 font-semibold w-[10%] mr-4 rounded-sm text-black"> Submit </button>
                        <!-- <button id="pending-approved-btn" class="bg-blue-400 font-semibold w-[10%] mr-6 rounded-sm text-black"> Approved </button> -->
                    </div>
                </div>

                <div class="bg-white w-full  mt-[1.5%] rounded-sm  flex flex-col">
                    <div class="bg-blue-400 rounded-sm text-black font-bold text-md p-2">
                        <label class="text-black">Referral Details</label>
                    </div>
                
                    <div class= "mt-2 p-2 flex flex-col">
                        <ul class="list-none flex flex-col space-y-2">
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
                        </ul>
                    </div>
                </div>

             </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="myModal-incoming" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header flex flex-row justify-between items-center">
                <div class="flex flex-row justify-between items-center">
                    <h5 id="modal-title-incoming" class="modal-title-incoming" id="exampleModalLabel">Warning</h5>
                    <i id="modal-icon" class="fa-solid fa-triangle-exclamation ml-2"></i>
                    <!-- <i class="fa-solid fa-circle-check"></i> -->
                </div>
                <button type="button" class="close text-3xl" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modal-body-incoming" class="modal-body-incoming ml-2">
                Please fill out the required fields.
            </div>
            <div class="modal-footer">
                <button id="ok-modal-btn-incoming" type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2" data-bs-dismiss="modal">OK</button>
                <button id="yes-modal-btn-incoming" type="button" class="hidden bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2" data-bs-dismiss="modal">Yes</button>
            </div>
            </div>
        </div>
    </div>

    


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>  -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script type="text/javascript"  charset="utf8" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>

    <script src="./js/incoming_form_2.js?v=<?php echo time(); ?>"></script>

    <script>
    // $(document).ready(function () {
    //     $('#myDataTable').DataTable();
    // });
        var jsonData = <?php echo $jsonData; ?>;
        var logout_data = <?php echo $logout_data; ?>;
        var login_data = "<?php echo $_SESSION['login_time']; ?>";

        // console.log(logout_data)
    
    </script>
</body>
</html>