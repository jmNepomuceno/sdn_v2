<?php 
    include('database/connection2.php');
    session_start();

    $tm_fields = array("Last Name","First Name","Middle Name", "Birthday" ,"Mobile No." ,"Username" ,"Password" ,"Confirm Password");
    $tm_input_names = array("last_name","first_name","middle_name", "birthday" ,"mobile_no" ,"username" ,"password" ,"confirm_password");
    $tm_id = array("tms-last-name","tms-first-name","tms-middle-name", "tms-birthday" ,"tms-mobile-no" ,"tms-username" ,"tms-password" ,"tms-confirm-password");

    $sdn_fields = array("Hospital Name","Hospital Code","Address: Region","Address: Province", "Address: City/ Municipality" ,"Address: Barangay","Zip Code" ,"Email Address" ,
                "Hospital Landline No.","Hospital Mobile No.","Hospital Director","Hospital Director Mobile No.","Point Person","Point Person Mobile No.");
    $sdn_input_names = array("hospital_name","hospital_code","address_region","address_province", "address_municipality" ,"address_barangay","zip_code" ,"email_address" ,
                    "landline_no" ,"hospital_mobile_no", "hospital_director", "hospital_director_mobile_no","point_person","point_person_mobile_no");
    
    $sdn_id = array("sdn-hospital-name","sdn-hospital-code","sdn-address-region","sdn-address-province", "sdn-address-municipality" ,"sdn-address-barangay","sdn-zip-code" ,"sdn-email-address" ,
                    "sdn-landline-no" ,"sdn-hospital-mobile-no", "sdn-hospital-director", "sdn-hospital-director-mobile-no","sdn-point-person","sdn-point-person-mobile-no");

    //authorization
    $sdn_autho_fields = array("Hospital Code", "Cipher Key" , "Last Name", "First Name", "Middle Name", "Extension Name", "Username" , "Password", "Confirm Password");

    $sdn_autho_input_names = array("hospital_code", "cipher_key" , "last_name", "first_name", "middle_name", "extension_name", "username" , "password", "confirm_password");
    
    $sdn_autho_id = array("sdn-auth-hospital-code", "sdn-cipher-key" , "sdn-last-name", "sdn-first-name", "sdn-middle-name", "sdn-extension-name", "sdn-username" , "sdn-password", "sdn-confirm-password");

    if($_POST){
        $_SESSION["process_timer"] = [] ;
         
        $sdn_username = $_POST['sdn_username'];
        $sdn_password = $_POST['sdn_password'];
        $account_validity = false;
        // //query to check if the user is already logged in.
        // if($sdn_username != "" && $sdn_password != ""){
        //     $_SESSION['user_name'] = "John Marvin Nepomuceno";
        //     $_SESSION['user_password'] = "password";
        //     header('Location: ./main.php');
        // }

        // login verifaction for the outside users
        if($sdn_username != "admin" && $sdn_password != "admin"){
            try{
                $stmt = $pdo->prepare('SELECT * FROM sdn_users WHERE username = ? AND password = ?');
                $stmt->execute([$sdn_username , $sdn_password]);
                $data_child = $stmt->fetchAll(PDO::FETCH_ASSOC);
                // echo '<pre>'; print_r($data_child); echo '</pre>';

                if(count($data_child) == 1){
                    $account_validity = true;
                }

                // echo '<pre>'; print_r($data_child); echo '</pre>';
                // echo $data_child[0]['hospital_code'];


                // $stmt_all_data = $pdo->prepare("SELECT sdn_hospital.*
                //                                 FROM sdn_hospital
                //                                 JOIN sdn_users ON sdn_hospital.hospital_code = sdn_users.hospital_code
                //                                 WHERE sdn_users.hospital_code = 6574");

                // $stmt_all_data->execute();
                // $data_all_data = $stmt_all_data->fetchAll(PDO::FETCH_ASSOC);
                
                if($account_validity == true){
                    $stmt = $pdo->prepare('SELECT * FROM sdn_hospital WHERE hospital_code = ?');
                    $stmt->execute([$data_child[0]['hospital_code']]);
                    $data_parent = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    // echo '<pre>'; print_r($data_parent); echo '</pre>';

                    $_SESSION['hospital_code'] = $data_parent[0]['hospital_code'];
                    $_SESSION['hospital_name'] = $data_parent[0]['hospital_name'];
                    $_SESSION['hospital_email'] = $data_parent[0]['hospital_email'];
                    $_SESSION['hospital_landline'] = $data_parent[0]['hospital_landline'];
                    $_SESSION['hospital_mobile'] = $data_parent[0]['hospital_mobile'];
                    $_SESSION['hospital_name'] = $data_parent[0]['hospital_name'];

                    $_SESSION['user_name'] = $data_child[0]['username'];
                    $_SESSION['user_password'] = $data_child[0]['password'];
                    $_SESSION['first_name'] = $data_child[0]['user_firstname'];
                    $_SESSION['last_name'] = $data_child[0]['user_lastname'];
                    $_SESSION['middle_name'] = $data_child[0]['user_middlename'];
                    $_SESSION['user_type'] = 'outside';

                    $_SESSION['post_value_reload'] = 'false';
                    $_SESSION["sub_what"] = "";
                    // Get the current date and time
                    $timezone = new DateTimeZone('Asia/Manila'); // Replace 'Your/Timezone' with your actual time zone
                    $currentDateTime = new DateTime("",$timezone);

                    // Format date components
                    $year = $currentDateTime->format('Y');
                    $month = $currentDateTime->format('m');
                    $day = $currentDateTime->format('d');

                    $hours = $currentDateTime->format('H');
                    $minutes = $currentDateTime->format('i');
                    $seconds = $currentDateTime->format('s');

                    $final_date = $year . "/" . $month . "/" . $day . " " . $hours . ":" . $minutes . ":" . $seconds;
                    $normal_date = $year . "-" . $month . "-" . $day . " " . $hours . ":" . $minutes . ":" . $seconds;

                    $_SESSION['login_time'] = $final_date;

                    $sql = "UPDATE incoming_referrals SET login_time = '". $final_date ."' , login_user='". $sdn_username ."' ";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();

                    $sql = "UPDATE sdn_users SET user_lastLoggedIn='online' , user_isActive='1' WHERE username=:username AND password=:password";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':username', $data_child[0]['username'], PDO::PARAM_STR);
                    $stmt->bindParam(':password', $data_child[0]['password'], PDO::PARAM_STR);
                    $stmt->execute();

                    // for history log
                    $act_type = 'user_login';
                    $pat_name = " ";
                    $hpercode = " ";
                    $action = 'online';
                    $user_name = $data_child[0]['username'];
                    $sql = "INSERT INTO history_log (hpercode, hospital_code, date, activity_type, action, pat_name, username) VALUES (?,?,?,?,?,?,?)";
                    $stmt = $pdo->prepare($sql);

                    $stmt->bindParam(1, $hpercode, PDO::PARAM_STR);
                    $stmt->bindParam(2, $_SESSION['hospital_code'], PDO::PARAM_INT);
                    $stmt->bindParam(3, $normal_date, PDO::PARAM_STR);
                    $stmt->bindParam(4, $act_type, PDO::PARAM_STR);
                    $stmt->bindParam(5, $action, PDO::PARAM_STR);
                    $stmt->bindParam(6, $pat_name, PDO::PARAM_STR);
                    $stmt->bindParam(7, $user_name, PDO::PARAM_STR);

                    $stmt->execute();

                    header('Location: ./main.php');
                }else{
                    echo '<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
                            <script type="text/javascript">
                                var jQuery = $.noConflict(true);
                                jQuery(document).ready(function() {
                                    jQuery("#modal-title").text("Warning")
                                    jQuery("#modal-icon").addClass("fa-triangle-exclamation")
                                    jQuery("#modal-icon").removeClass("fa-circle-check")
                                    jQuery("#modal-body").text("Invalid username and password!")
                                    jQuery("#ok-modal-btn").text("Close")
                                    jQuery("#myModal").modal("show");
                                });
                            </script>';
                }
                
            }catch(PDOException $e){
                echo "Error: " . $e->getMessage();
            }

        }
        //verification for admin user logged in
        else if($sdn_username == "admin" && $sdn_password == "admin"){
            // $_SESSION['user_name'] = "Bataan General Hospital and Medical Center";
            $_SESSION['hospital_code'] = '1437';
            $_SESSION['hospital_name'] = "Bataan General Hospital and Medical Center";
            $_SESSION['hospital_landline'] = '333-3333';
            $_SESSION['hospital_mobile'] = '3333-3333-333';
            // $_SESSION['user_name'] = "Administrator";
            // $_SESSION['user_password'] = $sdn_password;

            $_SESSION['user_name'] = 'admin';
            $_SESSION['user_password'] = 'admin';
            $_SESSION['last_name'] = 'Administrator';
            $_SESSION['first_name'] = '';
            $_SESSION['middle_name'] = '';
            $_SESSION['user_type'] = 'admin';
            // $_SESSION["process_timer"] = [];
            $_SESSION['post_value_reload'] = 'false';
            $_SESSION["sub_what"] = "";

            // Get the current date and time
            $timezone = new DateTimeZone('Asia/Manila'); // Replace 'Your/Timezone' with your actual time zone
            $currentDateTime = new DateTime("",$timezone);

            // Format date components
            $year = $currentDateTime->format('Y');
            $month = $currentDateTime->format('m');
            $day = $currentDateTime->format('d');

            $hours = $currentDateTime->format('H');
            $minutes = $currentDateTime->format('i');
            $seconds = $currentDateTime->format('s');

            $final_date = $year . "/" . $month . "/" . $day . " " . $hours . ":" . $minutes . ":" . $seconds;
            $temp_date = $year . "-" . $month . "-" . $day . " " . $hours . ":" . $minutes . ":" . $seconds;
            
            $_SESSION['login_time'] = $final_date;

            $sql = "UPDATE incoming_referrals SET login_time = :final_date, login_user = :sdn_username";
            $stmt = $pdo->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':final_date', $final_date, PDO::PARAM_STR);
            $stmt->bindParam(':sdn_username', $sdn_username, PDO::PARAM_STR);

            // Execute the statement
            $stmt->execute();

            $sql = "UPDATE sdn_users SET user_lastLoggedIn='online' , user_isActive='1' WHERE username='admin' AND password='admin'";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();

            // for history log
            $act_type = 'user_login';
            $pat_name = " ";
            $hpercode = " ";
            $action = 'online';
            $user_name = 'admin';
            $sql = "INSERT INTO history_log (hpercode, hospital_code, date, activity_type, action, pat_name, username) VALUES (?,?,?,?,?,?,?)";
            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(1, $hpercode, PDO::PARAM_STR);
            $stmt->bindParam(2, $_SESSION['hospital_code'], PDO::PARAM_INT);
            $stmt->bindParam(3, $temp_date, PDO::PARAM_STR);
            $stmt->bindParam(4, $act_type, PDO::PARAM_STR);
            $stmt->bindParam(5, $action, PDO::PARAM_STR);
            $stmt->bindParam(6, $pat_name, PDO::PARAM_STR);
            $stmt->bindParam(7, $user_name, PDO::PARAM_STR);

            $stmt->execute();

            header('Location: ./main.php');
        } 

        else if($sdn_username != 'admin' || $sdn_password != 'admin'){
            echo '<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
                    <script type="text/javascript">
                        var jQuery = $.noConflict(true);
                        jQuery(document).ready(function() {
                            jQuery("#modal-title").text("Warning")
                            jQuery("#modal-icon").addClass("fa-triangle-exclamation")
                            jQuery("#modal-icon").removeClass("fa-circle-check")
                            jQuery("#modal-body").text("Invalid username and password!")
                            jQuery("#ok-modal-btn").text("Close")
                            jQuery("#myModal").modal("show");
                        });
                    </script>';
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    <!-- <link rel="stylesheet" href="index.css"> -->
    <link rel="stylesheet" href="output.css">

    <style>
        .loader {
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid #3498db;
            width: 120px;
            height: 120px;
            -webkit-animation: spin 2s linear infinite; /* Safari */
            animation: spin 2s linear infinite;
        }

        /* Safari */
        @-webkit-keyframes spin {
            0% { -webkit-transform: rotate(0deg); }
            100% { -webkit-transform: rotate(360deg); }
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
        -webkit-appearance: none;
      }
    </style>

    

    
</head>
<body>
        
        <div class="flex flex-col justify-between items-center w-full h-screen overflow-hidden">
            <header class="header-div w-full h-40 sm:h-28 flex flex-col sm:flex-row justify-start sm:justify-between bg-mainColor">
                <!-- MOBILE DIV VIEW -->
                <div class="w-full h-2/4 flex md:hidden flex-row justify-center items-center">
                    <!-- <img src="./assets/login_imgs/main_bg.png" alt="logo-img" class="w-20 sm:w-24 h-11/12 sm:h-full ml-10"> -->
                    <img src="./assets/login_imgs/main_bg.png" alt="logo-img" class="w-20 sm:w-24 h-11/12 sm:h-full mt-3">
                    <h1 class="text-white text-3xl mt-2">BataanGHMC</h1>
                </div>

                <!-- DESKTOP DIV VIEW -->
                <div class="main-logo w-1/4 h-full flex-row hidden md:flex justify-center items-center cursor-pointer">
                    <!-- <img src="./assets/login_imgs/main_bg.png" alt="logo-img" class="w-20 sm:w-24 h-11/12 sm:h-full ml-10"> -->
                    <img src="./assets/login_imgs/main_bg.png" alt="logo-img" class="w-20 sm:w-24 h-11/12 sm:h-full">
                    <h1 class="text-white text-5xl">BataanGHMC</h1>
                </div>


                <div class="nav-div flex flex-row justify-around items-center w-full sm:w-1/4 h-full  mr-10 sm:p-5 text-white text-xl">
                    <a><h4 class="cursor-pointer">Home</h4></a>
                    <a><h4 class="cursor-pointer">Services</h4></a>
                    <a><h4 class="cursor-pointer">About</h4></a>
                </div> 
            </header>

            <!-- MAIN BODY  -->
            <div class="main-div w-full h-full flex flex-row justify-start items-center">
                <div class="flex flex-row justify-end w-full sm:w-3/5 h-full z-10 bg-white">

                    <div class="sub-main-div flex flex-col justify-center items-center w-full h-full gap-10">

                        <div class="sdn-div w-80 h-28 flex flex-col justify-center hover:justify-start items-center rounded-3xl bg-mainColor hover:w-450 hover:h-48 hover:transition duration-700 ease-in-out cursor-pointer border-5 border-titleDivColor">
                            <h3 class="ask-account-sdn-h3 w-full h-10 hidden rounded-t-3xl flex flex-col justify-center items-center text-white text-sm">
                                Already have an account?
                            </h3>

                            <div class="w-3/4 h-2/5  rounded-3xl flex flex-row justify-around items-center">
                                <i class="sdn-lock-icon fa-solid fa-lock text-white"></i>
                                <div class="sdn-text text-white text-lg">Service Delivery Network</div>
                            </div>

                            <button type="button" class="sdn-login-btn btn btn-success bg-loginHereBtn hidden mt-3">Login Here</button>
                        </div>
                       
                        
                         <div class="create-acc-div w-80 h-28 flex flex-col justify-center hover:justify-start items-center rounded-3xl bg-mainColor hover:w-450 hover:h-48 hover:transition duration-700 ease-in-out cursor-pointer border-5 border-titleDivColor">
                            <h3 class="ask-account-cc-h3 w-full h-10 hidden rounded-t-3xl flex flex-col justify-center items-center text-white text-sm">
                                No account yet?
                            </h3>

                            <div class="w-3/4 h-2/5 rounded-3xl flex flex-row justify-around items-center">
                                <i class="cc-lock-icon fa-solid fa-user text-white"></i>
                                <div class="cc-text text-white text-xl">Create Account</div>
                            </div>

                            <button type="button" class="cc-login-btn btn btn-success bg-loginHereBtn hidden mt-3">Sign up</button>
                        </div>
                        
                        <!-- <div class="main-div-sdn-login w-[30%] h-[40%] border-2 border-black"></div> -->
                    </div>

                    <div class="hidden md:flex triangle-div w-0 h-0 border-t-415 border-t-transparent border-b-415 border-b-transparent border-l-415 border-l-white -mr-415"></div>
                </div>
                
                <div class="hidden md:flex flex flex-row justify-end items-center w-2/4 h-full">
                    <img src="./assets/main_imgs/sdn_img.jpg" alt="logo-img" class=" hover-img w-full h-full opacity-50">
                </div>

            </div>

            <!-- MODAL DIV  -->
            <div class="modal-div hidden absolute flex flex-col justify-start items-center gap-3 w-11/12 sm:w-2/6 h-80 translate-y-[200px] sm:translate-y-[350px] translate-x-50px border bg-white rounded-lg">
                <div class="bg-mainColor w-full h-10 rounded-t-lg text-white flex flex-row justify-start items-center">
                    <h3 class="ml-5 text-xl">ACCOUNT REGISTRATION</h3>
                </div>

                <h3 class="ml-5 text-lg sm:text-xl font-bold">SELECT ACCOUNT TO CREATE</h3>

                <div class="w-11/12 h-36 flex flex-col justify-around items-center rounded-2xl">
                    <div class="sdn-choose-modal-div w-11/12 sm:w-2/4 h-2/5 border-2 border-titleDivColor rounded-3xl flex flex-row justify-start items-center bg-mainColor cursor-pointer">
                        <img src="./assets/main_imgs/add-user.png" alt="logo-img" class="w-6 h-2/5 ml-5">
                        <h1 class="text-white text-xl ml-2">Service Delivery Network</h1>
                    </div>
                    <div class="tms-choose-modal-div w-11/12 sm:w-2/4 h-2/5 border-2 border-titleDivColor rounded-3xl flex flex-row justify-start items-center bg-mainColor cursor-pointer">
                        <img src="./assets/main_imgs/add-user.png" alt="logo-img" class="w-6 h-2/5 ml-9">
                        <h1 class="text-white text-xl ml-2">Telemedicine Service</h1>
                    </div>
                </div>

                <div class="w-full h-9 flex flex-row justify-end items-center mb-1 mr-12">
                    <button class="modal-div-close-btn bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-4 h-full rounded">Close</button>
                </div>
            </div>
     
            <!-- TELEMEDICINE SERVICE MODAL -->

            <div class="tms-modal-div hidden absolute flex flex-col justify-center items-center w-full h-screen">
                <div class="flex flex-col justify-start items-center w-11/12 sm:w-2/5 h-5/6 bg-teleCreateAccColor">
                    <div class="w-full h-[7%] bg-cyan-950 flex flex-row justify-between items-center">
                        <h1 class="text-white text-sm sm:text-2xl ml-5 ">TELEMEDICINE ACCOUNT REGISTRATION</h1>
                        <!-- <i class="fa-solid fa-x"></i> -->
                        <button class="tms-btn-close text-xl sm:text-3xl mr-5 text-white">X</button>
                    </div>

                    
                    <div class="w-full h-[90%] overflow-y-scroll flex flex-col justify-start items-center bg-teleCreateAccColor p-2">
                        <form class="w-full h-full flex flex-col justify-start items-center">
                            <?php for ($x = 0; $x < 8; $x++) { ?>
                                <div class="w-11/12 h-[90px] border flex flex-col justify-start items-center bg-white border-2 mt-3">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-lg font-bold ml-3" for=<?php echo $tm_id[$x] ?>> <span class="text-red-600">*</span> <?php echo $tm_fields[$x] ?> </label>
                                    </div>
                                    <?php if($x == 7 || $x == 6){ ?>
                                        <input id=<?php echo $tm_id[$x] ?> type="password" name=<?php echo $tm_input_names[$x] ?> class="w-[95%] h-[40%] border-2 rounded-lg outline-none p-2" autocomplete="off">
                                    <?php }else if($x == 3) { ?>
                                        <input id=<?php echo $tm_id[$x] ?> type="date" name=<?php echo $tm_input_names[$x] ?> class="w-[95%] h-[40%] border-2 rounded-lg outline-none p-2" autocomplete="off">
                                    <?php }else if($x == 4) { ?>    
                                        <input id=<?php echo $tm_id[$x] ?> type="number" name=<?php echo $tm_input_names[$x] ?> minlength="9" maxlength="9" class="w-[95%] h-[40%] border-2 rounded-lg outline-none p-2" autocomplete="off">
                                    <?php }else if($x == 0 || $x == 1 || $x == 2) { ?>    
                                        <input id=<?php echo $tm_id[$x] ?> type="text" name=<?php echo $tm_input_names[$x] ?> class="uppercase w-[95%] h-[40%] border-2 rounded-lg outline-none p-2" autocomplete="off">
                                    <?php } else { ?>
                                        <input id=<?php echo $tm_id[$x] ?> type="text" name=<?php echo $tm_input_names[$x] ?> class="w-[95%] h-[40%] border-2 rounded-lg outline-none p-2" autocomplete="off">
                                    <?php } ?>  
                                    <!-- <?php echo $tm_input_names[$x] ?> -->
                                </div>
                            <?php } ?>
                            <h1 class="text-red-600 hidden" id="tms-match-h1"> Password do not match!</h1>
                            <div class="w-full flex flex-row justify-center items-center mt-3 mb-3">
                                <button name="tm-register-btn" id="tm-register-btn" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-4 h-full rounded w-2/4 sm:w-1/4 ">Register</button>
                            </div>
                        </form>
                    </div>

                    
                   
                </div>
            </div>

            <!-- TELEMEDICINE LOGIN MODAL -->
            <div class="tm-login-modal-div hidden absolute flex flex-col justify-center items-center w-full h-screen">
                <div class="absolute flex flex-col justify-start items-center gap-3 w-11/12 sm:w-2/6 h-[400px] translate-y-[50px] sm:translate-y-[50px] translate-x-50px border bg-teleCreateAccColor rounded-lg">
                    <div class="bg-mainColor w-full h-[50px] rounded-t-lg text-white flex flex-row justify-between items-center">
                        <h3 class="ml-5 text-xl">TELEMEDICINE SERVICES</h3>
                        <button class="tm-login-btn-close text-xl sm:text-3xl mr-5 text-white">X</button>

                    </div>

                    <h3 class="ml-5 text-lg sm:text-xl font-bold">LOGIN TO YOUR ACCOUNT</h3>

                    <!-- <div class="w-11/12 h-36 flex flex-col justify-around items-center rounded-2xl">
                        <div class="sdn-choose-modal-div w-11/12 sm:w-2/4 h-2/5 border-2 border-titleDivColor rounded-3xl flex flex-row justify-start items-center bg-mainColor cursor-pointer">
                            <img src="./assets/main_imgs/add-user.png" alt="logo-img" class="w-6 h-2/5 ml-5">
                            <h1 class="text-white text-xl ml-2">Service Delivery Network</h1>
                        </div>
                        <div class="tms-choose-modal-div w-11/12 sm:w-2/4 h-2/5 border-2 border-titleDivColor rounded-3xl flex flex-row justify-start items-center bg-mainColor cursor-pointer">
                            <img src="./assets/main_imgs/add-user.png" alt="logo-img" class="w-6 h-2/5 ml-9">
                            <h1 class="text-white text-xl ml-2">Telemedicine Service</h1>
                        </div>
                    </div> -->

                    <div class="flex flex-col justify-start items-center w-full">
                        <div class="w-11/12 h-[100px] rounded-lg border flex flex-col justify-start items-center bg-white border-2 mt-1">
                            <div class="w-full flex flex-row justify-start items-center p-1">
                                <h3 class="text-lg font-bold ml-3"> <span class="text-red-600">*</span> Username </h3>
                            </div>
                            <input type="text" name="username" id="tms-username-txt" class="uppercase w-[95%] h-[50%] border-2 border-sdnRegistraionColor rounded-lg outline-none p-2" required autocomplete="off">
                            
                        </div>

                        <div class="w-11/12 h-[100px] rounded-lg border flex flex-col justify-start items-center bg-white border-2 mt-3">
                            <div class="w-full flex flex-row justify-start items-center p-1">
                                <h3 class="text-lg font-bold ml-3"> <span class="text-red-600">*</span> Password </h3>
                            </div>
                            <input type="password" name="password" class="uppercase w-[95%] h-[50%] border-2 border-sdnRegistraionColor rounded-lg outline-none p-2" required autocomplete="off">
                        </div>
                    </div>

                    <div class="w-full h-9 flex flex-row justify-center items-center">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-4 h-full rounded">LOGIN</button>
                    </div>
                </div>
            </div>

            <!-- SDN MODAL -->

            <div id='sdn-modal-div-id' class="sdn-modal-div hidden flex flex-col justify-center items-center w-full h-screen ">
                <div class="sdn-sub-modal-div flex flex-col justify-start items-center w-11/12 sm:w-2/5 h-5/6 bg-teleCreateAccColor">
                    <div class="w-full h-[50px] sm:h-[70px] bg-cyan-950 flex flex-row justify-between items-center">
                        <h1 class="text-white text-lg sm:text-2xl ml-5 ">SERVICE DELIVERY NETWORK</h1>
                        <!-- <i class="fa-solid fa-x"></i> -->
                        <button class="sdn-btn-close text-xl sm:text-3xl mr-5 text-white bg-transparent">X</button>
                    </div>

                    <div class="w-full h-[70px] flex flex-row justify-start items-end border-2 border-b-sdnRegistraionColor bg-teleCreateAccColor">
                        <div class="sdn-registration-nav-btn w-2/5 sm:w-1/4 h-3/4 rounded-t-lg ml-2 bg-cyan-500 flex flex-col justify-center items-center text-lg sm:text-2xl text-white cursor-pointer">Registration</div>
                        <div class="sdn-authorization-nav-btn w-2/5 sm:w-1/4 h-3/4 rounded-t-lg ml-2 bg-mainColor flex flex-col justify-center items-center text-lg sm:text-2xl text-white cursor-pointer">Authorization</div>
                    </div>

                    <div class="w-full h-[75px] flex flex-row justify-center items-center border bg-teleCreateAccColor">
                        <p class="w-11/12 h-full text-xs sm:text-base text-black text-center p-2">
                            This is a one-time registration ONLY. If you already have an account, no need to register again. <br>
                            <span class="text-red-600">A one-time password and authorization key will be send to your registered mobile no. </span><br>
                        </p>
                    </div>
                    <div class="sdn-registration-modal-div w-full h-[70%] overflow-y-scroll flex flex-col justify-start items-center bg-teleCreateAccColor p-2">
                        <form action="index.php" id="sdn-modal-field" class="w-full h-full flex flex-col justify-start items-center" method="post" novalidate>
                            

                            <div class="w-11/12 flex flex-row justify-evenly items-center mt-3">
                                <div class="w-full flex flex-row justify-start items-center p-1">
                                    <label class="text-xs sm:text-xl  ml-3" for="sdn-hospital-name" > <span class="text-red-600"></span> Hospital Name </label>   
                                </div>
                                <input type="text" id="sdn-hospital-name" name="hospital_name" class="reg_inputs border-2 border-sdnRegistraionColor w-[115%] sm:w-[95%] h-[40px] sm:h-[60px] border-2 outline-none p-2" required autocomplete="off">
                            </div>

                            <div class="w-11/12 flex flex-row justify-evenly items-center mt-3">
                                <div class="w-full flex flex-row justify-start items-center p-1">
                                    <label class="text-xs sm:text-xl  ml-3" for="sdn-hospital-code" > <span class="text-red-600"></span> Hospital Code </label>   
                                </div>
                                <input type="number" id="sdn-hospital-code" name="hospital_code" class="reg_inputs border-2 border-sdnRegistraionColor w-[115%] sm:w-[95%] h-[40px] sm:h-[60px] border-2 outline-none p-2" required autocomplete="off">
                            </div>

                            <div class="w-11/12 flex flex-row justify-evenly items-center mt-3">
                                <div class="w-full flex flex-row justify-start items-center p-1">
                                    <label class="text-xs sm:text-xl  ml-3" for="sdn-region-select" > <span class="text-red-600"></span> Adress: Region </label>   
                                </div>
                                <select id="sdn-region-select" required onchange="getLocations('region' , 'sdn-region')" name="region" class="reg_inputs text-xs sm:text-base w-full h-full text-center border-2 border-sdnRegistraionColor cursor-pointer outline-none" autocomplete="off">
                                    <option value="" class="">Choose a Region</option>
                                    <?php 
                                        $stmt = $pdo->query('SELECT region_code, region_description from region');
                                        while($data = $stmt->fetch(PDO::FETCH_ASSOC)){
                                            echo '<option value="' , $data['region_code'] , '">' , $data['region_description'] , '</option>';
                                        }                                        
                                    ?>
                                    </select>
                            </div>

                            <div class="w-11/12 flex flex-row justify-evenly items-center mt-3">
                                <div class="w-full flex flex-row justify-start items-center p-1">
                                    <label class="text-xs sm:text-xl  ml-3" for="sdn-province-select" > <span class="text-red-600"></span> Address: Province </label>   
                                </div>
                                <select id="sdn-province-select" required onchange="getLocations('province' , 'sdn-province')" name="province" class="reg_inputs text-xs sm:text-base w-full h-full text-center border-2 border-sdnRegistraionColor cursor-pointer outline-none">
                                    <option value="" class="">Choose a Province</option>
                                </select>
                            </div>

                            <div class="w-11/12 flex flex-row justify-evenly items-center mt-3">
                                <div class="w-full flex flex-row justify-start items-center p-1">
                                    <label class="text-xs sm:text-xl  ml-3" for="sdn-city-select" > <span class="text-red-600"></span> Address: Municipality </label>   
                                </div>
                                <select id="sdn-city-select" required onchange="getLocations('city', 'sdn-city')" name="municipality" class="reg_inputs text-xs sm:text-base w-full h-full text-center border-2 border-sdnRegistraionColor cursor-pointer outline-none">
                                    <option value="" class="">Choose a Municipality</option>
                                </select>
                            </div>

                            <div class="w-11/12 flex flex-row justify-evenly items-center mt-3">
                                <div class="w-full flex flex-row justify-start items-center p-1">
                                    <label class="text-xs sm:text-xl  ml-3" for="sdn-brgy-select" > <span class="text-red-600"></span> Address: Barangay </label>   
                                </div>
                                <select id="sdn-brgy-select" name="barangay" class="reg_inputs text-xs sm:text-base w-full h-full text-center border-2 border-sdnRegistraionColor cursor-pointer outline-none">
                                    <option value="" class="">Choose a Barangay</option>
                                </select>
                            </div>

                            <div class="w-11/12 flex flex-row justify-evenly items-center mt-3">
                                <div class="w-full flex flex-row justify-start items-center p-1">
                                    <label class="text-xs sm:text-xl  ml-3" for="sdn-zip-code" > <span class="text-red-600"></span> Zip Code </label>   
                                </div>
                                <input type="number" id="sdn-zip-code" name="zip_code" class="reg_inputs border-2 border-sdnRegistraionColor w-[115%] sm:w-[95%] h-[40px] sm:h-[60px] border-2 outline-none p-2" required autocomplete="off">
                            </div>

                            <div class="w-11/12 flex flex-row justify-evenly items-center mt-3">
                                <div class="w-full flex flex-row justify-start items-center p-1">
                                    <label class="text-xs sm:text-xl  ml-3" for="sdn-email-address" > <span class="text-red-600"></span> Email Address </label>   
                                </div>
                                <input type="email" id="sdn-email-address" name="email_address" class="reg_inputs border-2 border-sdnRegistraionColor w-[115%] sm:w-[95%] h-[40px] sm:h-[60px] border-2 outline-none p-2" required autocomplete="off">
                            </div>

                            <div class="w-11/12 flex flex-row justify-evenly items-center mt-3">
                                <div class="w-full flex flex-row justify-start items-center p-1">
                                    <label class="text-xs sm:text-xl  ml-3" for="sdn-landline-no" > <span class="text-red-600"></span> Hospital Landline No. </label>   
                                </div>
                                <input type="text" id="sdn-landline-no" name="landline_no" class="reg_inputs border-2 border-sdnRegistraionColor w-[115%] sm:w-[95%] h-[40px] sm:h-[60px] border-2 outline-none p-2" required autocomplete="off">
                            </div>

                            <div class="w-11/12 flex flex-row justify-evenly items-center mt-3">
                                <div class="w-full flex flex-row justify-start items-center p-1">
                                    <label class="text-xs sm:text-xl  ml-3" for="sdn-hospital-mobile-no" > <span class="text-red-600"></span> Hospital Mobile No. </label>   
                                </div>
                                <input type="text" id="sdn-hospital-mobile-no" name="hospital_mobile_no" class="reg_inputs border-2 border-sdnRegistraionColor w-[115%] sm:w-[95%] h-[40px] sm:h-[60px] border-2 outline-none p-2" required autocomplete="off">
                            </div>

                            <div class="w-11/12 flex flex-row justify-evenly items-center mt-3">
                                <div class="w-full flex flex-row justify-start items-center p-1">
                                    <label class="text-xs sm:text-xl  ml-3" for="sdn-hospital-director" > <span class="text-red-600"></span> Hospital Director </label>   
                                </div>
                                <input type="text" id="sdn-hospital-director" name="hospital_director" class="reg_inputs border-2 border-sdnRegistraionColor w-[115%] sm:w-[95%] h-[40px] sm:h-[60px] border-2 outline-none p-2" required autocomplete="off" onkeydown="return /[a-zA-Z\s.,-]/i.test(event.key)">
                            </div>

                            <div class="w-11/12 flex flex-row justify-evenly items-center mt-3">
                                <div class="w-full flex flex-row justify-start items-center p-1">
                                    <label class="text-xs sm:text-xl  ml-3" for="sdn-hospital-director-mobile-no" > <span class="text-red-600"></span> Hospital Director Mobile No. </label>   
                                </div>
                                <input type="text" id="sdn-hospital-director-mobile-no" name="hospital_director_mobile_no" class="reg_inputs border-2 border-sdnRegistraionColor w-[115%] sm:w-[95%] h-[40px] sm:h-[60px] border-2 outline-none p-2" required autocomplete="off">
                            </div>

                            <div class="w-11/12 flex flex-row justify-evenly items-center mt-3">
                                <div class="w-full flex flex-row justify-start items-center p-1">
                                    <label class="text-xs sm:text-xl  ml-3" for="sdn-point-person" > <span class="text-red-600"></span> Point Person </label>   
                                </div>
                                <input type="text" id="sdn-point-person" name="point_person" class="reg_inputs border-2 border-sdnRegistraionColor w-[115%] sm:w-[95%] h-[40px] sm:h-[60px] border-2 outline-none p-2" required autocomplete="off" onkeydown="return /[a-zA-Z\s.,-]/i.test(event.key)">
                            </div>

                            <div class="w-11/12 flex flex-row justify-evenly items-center mt-3">
                                <div class="w-full flex flex-row justify-start items-center p-1">
                                    <label class="text-xs sm:text-xl  ml-3" for="sdn-point-person-mobile-no" > <span class="text-red-600"></span> Point Person Mobile No. </label>   
                                </div>
                                <input type="text" id="sdn-point-person-mobile-no" name="point_person_mobile_no" class="reg_inputs border-2 border-sdnRegistraionColor w-[115%] sm:w-[95%] h-[40px] sm:h-[60px] border-2 outline-none p-2" required autocomplete="off">
                            </div>

                            <!-- <div class="flex flex-col justify-start items-center w-full border-2 border-t-sdnRegistraionColor mt-3">
                                <div class="w-11/12 flex flex-row justify-evenly items-center mt-3">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-xs sm:text-lg font-bold ml-3"> <span class="text-red-600">*</span> Username</label>
                                    </div>
                                    <input type="text" name='username' class="border-2 border-sdnRegistraionColor uppercase w-[115%] sm:w-[95%] h-[40px] sm:h-[60px] border-2 outline-none p-2" required autocomplete="off">
                                </div>

                                <div class="w-11/12 flex flex-row justify-evenly items-center mt-3">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-xs sm:text-lg font-bold ml-3"> <span class="text-red-600">*</span> Password</label>
                                    </div>
                                    <input type="text" name='password' class="border-2 border-sdnRegistraionColor uppercase w-[115%] sm:w-[95%] h-[40px] sm:h-[60px] border-2 outline-none p-2" required autocomplete="off">
                                </div>

                                <div class="w-11/12 flex flex-row justify-evenly items-center mt-3">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-xs sm:text-lg font-bold ml-3"> <span class="text-red-600">*</span> Confirm Password</label>
                                    </div>
                                    <input type="text" name='confirm_password' class="border-2 border-sdnRegistraionColor uppercase w-[115%] sm:w-[95%] h-[40px] sm:h-[60px] border-2 outline-none p-2" required autocomplete="off">
                                </div>
                            </div> -->
                            
                            <div class="w-full h-15 flex flex-row justify-center items-center mt-3">
                                <button name="sdn-register-btn" id="sdn-register-btn" class="sdn-register-btn bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-4 h-full rounded w-2/4 sm:w-1/4 ">Register</button>
                            </div>
                        </form>
                    </div>

                    <!-- SDN AUTORIZATION MODAL DIV -->

                    <div class="sdn-authorization-modal-div hidden w-full h-[70%] overflow-y-scroll flex flex-col justify-start items-center bg-teleCreateAccColor p-2">
                        <form class="w-full h-full flex flex-col justify-start items-center" id="sdn-autho-form">
                            
                            <!-- HOSPITAL CODE AND CIPHER KEY DIV -->
                            <div class="w-11/12 h-auto border-2 border-b-[#808080] flex flex-col justify-around items-center">

                                <div class="w-full h-[70px] sm:h-[90px] border flex flex-col justify-start items-center bg-[#d9d9d9] border-2 rounded-lg mb-3">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-base sm:text-lg ml-3" for="sdn-autho-hospital-code-id"> <span class="text-red-600">*</span> Hospital Code </label>
                                    </div>
                                    <input id="sdn-autho-hospital-code-id" type="number" name="sdn-hospital-code" class="w-[95%] h-[40%] sm:h-[45%] border-2 border-[#666666] rounded-lg outline-none p-2" autocomplete="off">
                                </div>

                                <div class="w-full h-[70px] sm:h-[90px] border flex flex-col justify-start items-center bg-[#d9d9d9] border-2 rounded-lg mb-3">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-base sm:text-lg ml-3" for="sdn-autho-cipher-key-id"> <span class="text-red-600">*</span> Cipher Key </label>
                                    </div>
                                    <input id="sdn-autho-cipher-key-id" type="text" name="sdn-cipher-key" class="w-[95%] h-[40%] sm:h-[45%] border-2 border-[#666666] rounded-lg outline-none p-2" autocomplete="off">
                                </div>
                            </div>

                            <!-- LAST, FIRST, MIDDLE, EXTENSION NAME -->
                            <div class="w-11/12 h-auto border-2 border-b-[#808080] flex flex-col justify-around items-center mt-3">

                                <div class="w-full h-[70px] sm:h-[90px] border flex flex-col justify-start items-center bg-[#d9d9d9] border-2 rounded-lg mb-3">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-base sm:text-lg ml-3" for="sdn-autho-last-name-id"> <span class="text-red-600">*</span> Last Name </label>
                                    </div>
                                    <input id="sdn-autho-last-name-id" type="text" name="sdn-last-name" class="w-[95%] h-[40%] sm:h-[45%] border-2 border-[#666666] rounded-lg outline-none p-2" autocomplete="off">
                                </div>

                                <div class="w-full h-[70px] sm:h-[90px] border flex flex-col justify-start items-center bg-[#d9d9d9] border-2 rounded-lg mb-3">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-base sm:text-lg ml-3" for="sdn-autho-first-name-id"> <span class="text-red-600">*</span> First Name </label>
                                    </div>
                                    <input id="sdn-autho-first-name-id" type="text" name="sdn-first-name" class="w-[95%] h-[40%] sm:h-[45%] border-2 border-[#666666] rounded-lg outline-none p-2" autocomplete="off">
                                </div>

                                <div class="w-full h-[70px] sm:h-[90px] border flex flex-col justify-start items-center bg-[#d9d9d9] border-2 rounded-lg mb-3">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-base sm:text-lg ml-3" for="sdn-autho-middle-name-id"> <span class="text-red-600">*</span> Middle Name </label>
                                    </div>
                                    <input id="sdn-autho-middle-name-id" type="text" name="sdn-middle-name" class="w-[95%] h-[40%] sm:h-[45%] border-2 border-[#666666] rounded-lg outline-none p-2" autocomplete="off">
                                </div>

                                <div class="w-full h-[70px] sm:h-[90px] border flex flex-col justify-start items-center bg-[#d9d9d9] border-2 rounded-lg mb-3">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-base sm:text-lg ml-3" for="sdn-autho-ext-name-id"> Extension Name </label>
                                    </div>
                                    <input id="sdn-autho-ext-name-id" type="text" name="sdn-extension-name" class="w-[95%] h-[40%] sm:h-[45%] border-2 border-[#666666] rounded-lg outline-none p-2" autocomplete="off">
                                </div>

                            </div>

                            <!-- Username, Password and Confirm Password div -->
                            <div class="w-11/12 h-auto flex flex-col justify-around items-center mt-3">

                                <div class="w-full h-[70px] sm:h-[90px] border flex flex-col justify-start items-center bg-[#d9d9d9] border-2 rounded-lg mb-3">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-base sm:text-lg ml-3" for="sdn-autho-username"> <span class="text-red-600">*</span> Username </label>
                                    </div>
                                    <input id="sdn-autho-username" type="text" name="sdn-username" class="w-[95%] h-[40%] sm:h-[45%] border-2 border-[#666666] rounded-lg outline-none p-2" autocomplete="off">
                                </div>

                                <div class="w-full h-[70px] sm:h-[90px] border flex flex-col justify-start items-center bg-[#d9d9d9] border-2 rounded-lg mb-3">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-base sm:text-lg ml-3" for="sdn-autho-password"> <span class="text-red-600">*</span> Password </label>
                                    </div>
                                    <input id="sdn-autho-password" type="password" name="sdn-first-name" class="w-[95%] h-[40%] sm:h-[45%] border-2 border-[#666666] rounded-lg outline-none p-2" autocomplete="off">
                                </div>

                                <div class="w-full h-[70px] sm:h-[90px] border flex flex-col justify-start items-center bg-[#d9d9d9] border-2 rounded-lg mb-3">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-base sm:text-lg ml-3" for="sdn-autho-confirm-password"> <span class="text-red-600">*</span> Confirm Password </label>
                                    </div>
                                    <input id="sdn-autho-confirm-password" type="password" name="sdn-confirm-password" class="w-[95%] h-[40%] sm:h-[45%] border-2 border-[#666666] rounded-lg outline-none p-2" autocomplete="off">
                                </div>
                            </div>

                            <h1 class="text-red-600 hidden" id="tms-match-h1"> Password do not match!</h1>
                            <div class="w-full flex flex-row justify-center items-center mt-3 mb-3">
                                <button name="sdn-autho-verify-btn" id="sdn-autho-verify-btn" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-4 h-full rounded w-2/4 sm:w-1/4">Verify</button>
                            </div>
                        </form>

                    </div>

                </div>
            </div>

            <!-- SDN OTP MODAL  -->
            <div class="otp-modal-div hidden absolute flex flex-col justify-start items-center gap-3 w-11/12 sm:w-2/6 h-80 translate-y-[200px] sm:translate-y-[350px] translate-x-50px border bg-teleCreateAccColor rounded-lg">
                <div class="bg-mainColor w-full h-10 rounded-t-lg text-white flex flex-row justify-between items-center">
                    <h3 class="ml-5 text-xl">OTP <span>Email sent</span></h3>
                    <button id="sdn-otp-modal-btn-close" class="sdn-otp-modal-btn-close text-xl sm:text-3xl mr-5 text-white">X</button>

                </div>
                
                <div class="w-full h-auto flex flex-row justify-center items-center">
                    <h3 class="text-lg sm:text-xl font-bold">INPUT THE OTP</h3>
                </div>

                <div class="w-11/12 h-28 sm:h-36 flex flex-row justify-between items-center rounded-2xl ">
                    <div class="w-[48px] sm:w-[90px] h-full rounded-lg bg-white flex flex-col justify-center items-center text-6xl">
                        <input type="number" id="otp-input-1" class="w-full h-full rounded-lg text-center outline-none" placeholder="-">
                    </div>
                    <div class="w-[48px] sm:w-[90px] h-full rounded-lg bg-white flex flex-col justify-center items-center text-6xl">
                        <input type="number" id="otp-input-2" class="w-full h-full rounded-lg text-center outline-none" placeholder="-">
                    </div>
                    <div class="w-[48px] sm:w-[90px] h-full rounded-lg bg-white flex flex-col justify-center items-center text-6xl">
                        <input type="number" id="otp-input-3" class="w-full h-full rounded-lg text-center outline-none" placeholder="-">
                    </div>
                    <div class="w-[48px] sm:w-[90px] h-full rounded-lg bg-white flex flex-col justify-center items-center text-6xl">
                        <input type="number" id="otp-input-4" class="w-full h-full rounded-lg text-center outline-none" placeholder="-">
                    </div>
                    <div class="w-[48px] sm:w-[90px] h-full rounded-lg bg-white flex flex-col justify-center items-center text-6xl">
                        <input type="number" id="otp-input-5" class="w-full h-full rounded-lg text-center outline-none" placeholder="-">
                    </div>
                    <div class="w-[48px] sm:w-[90px] h-full rounded-lg bg-white flex flex-col justify-center items-center text-6xl">
                        <input type="number" id="otp-input-6" class="w-full h-full rounded-lg text-center outline-none" placeholder="-">
                    </div>
                </div>

                <div class="w-full h-28 flex flex-row justify-between items-center text-base">
                    <button id="resend-otp-btn" class="ml-10 text-blue-500 underline opacity-50 pointer-events-none">Resend OTP</button>
                    <label id="resend-otp-timer" class="mr-10 text-xl">00:00</label>
                </div>

                <div class="w-full h-9 flex flex-row justify-center items-center items-center mb-1">
                    <button id="otp-verify-btn" class="otp-verify-btn bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-4 h-full rounded">Verify</button>

                    <!-- <button class="modal-div-close-btn bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-4 h-full rounded">Close</button> -->
                </div>
            </div>
            
            <!-- SDN LOADING MODAL // LOADING SCREEN WHILE WAITING FOR THE OTP TO BE SENT  -->
            <div class="sdn-loading-div hidden absolute flex flex-col justify-start items-center gap-3 w-11/12 sm:w-2/6 h-80 translate-y-[200px] sm:translate-y-[350px] translate-x-50px border rounded-lg bg-teleCreateAccColor">
                <div class="bg-mainColor w-full h-10 rounded-t-lg text-white flex flex-row justify-start items-center">
                    <h3 class="ml-5 text-xl"></h3>
                </div>
                
                <h3 class="ml-5 text-lg sm:text-xl font-bold">SENDING OTP TO YOUR EMAIL...</h3>
                <div class="loader"></div>
            </div>

            <!-- SDN LOGIN MODAL -->
            <div class="sdn-login-modal-div hidden absolute flex flex-col justify-center items-center w-full h-screen">
                <div class="absolute flex flex-col justify-start items-center gap-3 w-11/12 sm:w-2/6 h-[400px] translate-y-[50px] sm:translate-y-[50px] translate-x-50px border bg-[#ecf0f1] rounded-lg">
                    <div class="bg-mainColor w-full h-[50px] rounded-t-lg text-white flex flex-row justify-between items-center">
                        <h3 class="ml-5 text-xl">SERVICE DELIVERY NETWORK</h3>
                        <button class="sdn-login-btn-close text-xl sm:text-3xl mr-5 text-white">X</button>

                    </div>

                    <h3 class="ml-5 text-lg sm:text-xl font-bold">LOGIN TO YOUR ACCOUNT</h3>

                    <!-- <div class="w-11/12 h-36 flex flex-col justify-around items-center rounded-2xl">
                        <div class="sdn-choose-modal-div w-11/12 sm:w-2/4 h-2/5 border-2 border-titleDivColor rounded-3xl flex flex-row justify-start items-center bg-mainColor cursor-pointer">
                            <img src="./assets/main_imgs/add-user.png" alt="logo-img" class="w-6 h-2/5 ml-5">
                            <h1 class="text-white text-xl ml-2">Service Delivery Network</h1>
                        </div>
                        <div class="tms-choose-modal-div w-11/12 sm:w-2/4 h-2/5 border-2 border-titleDivColor rounded-3xl flex flex-row justify-start items-center bg-mainColor cursor-pointer">
                            <img src="./assets/main_imgs/add-user.png" alt="logo-img" class="w-6 h-2/5 ml-9">
                            <h1 class="text-white text-xl ml-2">Telemedicine Service</h1>
                        </div>
                    </div> -->
                    <form action="index.php" method="POST" class="w-full h-full flex flex-col justify-around items-center rounded-lg"> 
                        <div class="flex flex-col justify-start items-center w-full">
                            <div class="w-11/12 h-[100px] rounded-lg border flex flex-col justify-start items-center bg-[#d9d9d9] border-2 mt-1">
                                <div class="w-full flex flex-row justify-start items-center p-1">
                                    <label class="text-lg font-bold ml-3" for="sdn-username"> <span class="text-red-600">*</span> Username </label>
                                </div>
                                <input type="text" name="sdn_username" id="sdn-username" class="w-[95%] h-[50%] border-2 border-sdnRegistraionColor rounded-lg outline-none p-2" required autocomplete="off">
                            </div>

                            <div class="w-11/12 h-[100px] rounded-lg border flex flex-col justify-start items-center bg-[#d9d9d9] border-2 mt-3">
                                <div class="w-full flex flex-row justify-start items-center p-1">
                                    <label class="text-lg font-bold ml-3" for="sdn-password"> <span class="text-red-600">*</span> Password </label>
                                </div>
                                <input type="password" name="sdn_password" id="sdn-password" class="w-[95%] h-[50%] border-2 border-sdnRegistraionColor rounded-lg outline-none p-2" required autocomplete="off">
                            </div>
                        </div>

                        <div class="w-full h-9 flex flex-row justify-center items-center">
                            <button id="sdn-login-main-btn" class="sdn-login-main-btn bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-4 h-full rounded">LOGIN</button>
                        </div>
                    </form>
                </div>
            </div>


            <!-- Modal -->
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header flex flex-row justify-between items-center">
                        <div class="flex flex-row justify-between items-center">
                            <h5 id="modal-title" class="modal-title" id="exampleModalLabel">Verification</h5>
                            <i id="modal-icon" class="fa-solid fa-triangle-exclamation ml-2"></i>
                            <!-- <i class="fa-solid fa-circle-check"></i> -->
                        </div>
                        <button type="button" class="close text-3xl" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="modal-body" class="modal-body">
                        Verified OTP
                    </div>
                    <div class="modal-footer">
                        <button id="ok-modal-btn" type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2" data-bs-dismiss="modal">OK</button>
                        <button id="yes-modal-btn" type="button" class="hidden bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2" data-bs-dismiss="modal">Yes</button>
                    </div>
                    </div>
                </div>
            </div>
        </div>
            
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    

    <script src="./js/styling.js?v=<?php echo time(); ?>"></script>
    <script src="./js/sdn_reg.js?v=<?php echo time(); ?>"></script>
    <script src="./js/sdn_autho.js?v=<?php echo time(); ?>"></script>
    <script src="./js/closed_otp.js?v=<?php echo time(); ?>"></script>
    <script src="./js/resend_otp.js?v=<?php echo time(); ?>"></script>
    <script src="./js/verify_otp.js?v=<?php echo time(); ?>"></script>
    <script src="./js/location.js?v=<?php echo time(); ?>"></script>
</body>
</html>