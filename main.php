<?php
    session_start();
    include('database/connection2.php');

    if ($_SESSION['user_name'] === 'admin'){
        $user_name = 'Bataan General Hospital and Medical Center';
    }else{
        $user_name = $_SESSION['hospital_name'];
    }

    // echo $user_name;
    // $sql = "SELECT username FROM sdn_users WHERE user_isActive=1 AND hospital_code=9312";
    // $stmt = $pdo->prepare($sql);
    // $stmt->execute();
    // $data_username = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // echo '<pre>'; print_r($data_username); echo '</pre>';
    // echo $data_username[0]['username'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SDN</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous"> -->
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <link rel="stylesheet" href="output.css" />
    <style>
         .scrollbar-hidden {
            /* Hide scrollbar for Firefox */
            scrollbar-width: none;
            /* Hide scrollbar for WebKit/Blink */
            -webkit-scrollbar {
            display: none;
            }
        }
    </style>
</head>
<body>
    <!-- <div id="modal-div" class="absolute w-full h-full flex flex-col justify-center items-center z-10">
        <div id="myModal" class="fixed inset-0 flex flex-col justify-center items-center w-[20%] border-4 border-[#bfbfbf] rounded">
                <div class="modal-content bg-white p-4 rounded shadow-md z-40 flex flex-col justify-center items-left ">
                    <h2 class="text-2xl font-bold mb-2">Service Delivery Network</h2>
                    <p class="text-xl">Welcome, Administrator!</p>
                    <div class="w-full flex flex-row justify-center items-center">
                        <button id="closeModal" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 focus:outline-none w-[30%]">OK</button>
                    </div>
                </div>
        </div>
    </div> -->

    <input id="current-page-input" type="hidden" name="current-page-input" value="" />
    <input id="clicked-logout-input" type="hidden" name="clicked-logout-input" value="" />
    

    <div id="main-div" class="flex flex-col justify-between items-left w-full h-screen overflow-hidden">
        <header class="header-div w-full h-[50px] flex flex-row justify-between items-center bg-[#1f292e]">
            <div class="w-[30%] h-full flex flex-row justify-start items-center">
                <h1 id="sdn-title-h1" class="text-white text-xl ml-7 mr-4 cursor-pointer"> Service Delivery Network</h1>
                <div id="side-bar-mobile-btn" class="side-bar-mobile-btn w-[50%] ml-4 h-full flex flex-row justify-start items-center cursor-pointer delay-150 bg-[#1f292e]">
                    <i class="fa-solid fa-bars text-white text-4xl"></i>
                </div>
            </div>
            <div class="account-header-div w-[35%] h-full flex flex-row justify-end items-center mr-2">

                <div class="w-auto h-5/6 flex flex-row justify-end items-center mr-4">
                    <!-- <div class="w-[33.3%] h-full   flex flex-row justify-end items-center -mr-1">
                        <h1 class="text-center w-full rounded-full p-1 bg-yellow-500 font-bold">6</h1>
                    </div> -->
                    
                        <div id="notif-div" class="w-[20px] h-full flex flex-col justify-center items-center cursor-pointer relative">
                            <h1 id="notif-circle" class="absolute top-2 text-center w-[17px] h-[17px] rounded-full bg-red-600 ml-5 text-white text-xs "><span id="notif-span"></span></h1>
                            <i class="fa-solid fa-bell text-white text-xl"></i>
                            <audio id="notif-sound" preload='auto' muted loop>
                                <source src="../assets/sound/water_droplet.mp3" type="audio/mpeg">
                            </audio>

                            <div id="notif-sub-div" class="hidden absolute top-[85%] w-[200px] h-[90px] bg-[#1f292e] border-4 border-[#7694a2] rounded-sm overflow-y-scroll scrollbar-hidden flex flex-col justify-start items-center">
                                <!-- <div class="h-[30px] w-full border border-black flex flex-row justify-evenly items-center">
                                    <h4 class="font-bold text-lg">3</h4>
                                    <h4 class="font-bold text-lg">OB</h4>
                                </div> -->
                                <!-- b3b3b3 -->
                            </div>
                        </div>

                        <!-- <div class="w-[20px] h-full flex flex-col justify-center items-center">
                            <i class="fa-solid fa-caret-down text-white text-xs mt-2"></i>
                        </div> -->
                </div>

                <div id="nav-account-div" class="header-username-div w-auto h-5/6 flex flex-row justify-end items-center mr-2">
                    <div class="w-[15%] h-full flex flex-row justify-end items-center mr-1">
                        <i class="fa-solid fa-user text-white text-xl"></i>
                    </div>
                    <div id="" class="w-auto h-full whitespace-nowrap flex flex-col justify-center items-center cursor-pointer">
                        <!-- <h1 class="text-white text-lg hidden sm:block">John Marvin Nepomuceno</h1> -->
                        <?php 
                            if($_SESSION['last_name'] === 'Administrator'){
                                echo '<h1 class="text-white text-base hidden sm:block">' . $user_name . ' | ' . $_SESSION["last_name"] . '</h1>';
                            }else{
                                echo '<h1 class="text-white text-base hidden sm:block">' . $user_name . ' | ' . $_SESSION["last_name"] . ', ' . $_SESSION['first_name'] . ' ' . $_SESSION['middle_name'] . '</h1>';;

                            }
                        ?>
                        
                    </div>
                    <div class="w-[5%] h-full flex flex-col justify-center items-center sm:m-1">
                        <i class="fa-solid fa-caret-down text-white text-xs"></i>
                    </div>
                </div>
            </div>
        </header>
        <!-- <i class="fa-solid fa-bars"></i> -->
        <!-- <div id="nav-mobile-account-div" class="overflow-hidden w-2/3 h-full bg-[#1f292e] absolute -right-[70%] transition-transform duration-300">
            
        </div> -->

        <div id="nav-mobile-account-div" class="sm:hidden flex flex-col justify-start items-center bg-[#1f292e] text-white fixed w-64 h-full overflow-y-auto transition-transform duration-300 transform translate-x-96 z-10">
            <div id="close-nav-mobile-btn" class="w-full h-[50px] mt-2 flex flex-row justify-start items-center">
                <i class="fa-solid fa-x ml-2 text-2xl"></i>
            </div>
            <div class="w-full h-[350px] flex flex-col justify-around items-center">
                <div class="w-2/3 h-[50px] border-b-2 border-[#29363d] flex flex-row justify-center items-center">
                    <h2 class="" >Dashboard (Incoming)</h2>
                </div>

                <div class="w-2/3 h-[50px] border-b-2 border-[#29363d] flex flex-row justify-center items-center">
                    <h2 class="">Dashboard (Outgoing)</h2>
                </div>

                <div class="w-2/3 h-[50px] border-b-2 border-[#29363d] flex flex-row justify-center items-center">
                    <h2 class="">Dashboard (ER/OPD)</h2>
                </div>

                <div class="w-2/3 h-[50px] border-b-2 border-[#29363d] flex flex-row justify-center items-center">
                    <h2 class="">History Log</h2>
                </div>

                <div class="w-2/3 h-[50px] border-b-2 border-[#29363d] flex flex-row justify-center items-center">
                    <h2 class="">Settings</h2>
                </div>

                <div class="w-2/3 h-[50px] border-b-2 border-[#29363d] flex flex-row justify-center items-center">
                    <h2 class="">Help</h2>
                </div>

                <div class="w-2/3 h-[50px] border-b-2 border-[#29363d] flex flex-row justify-center items-center">
                    <h2 class="">Logout</h2>
                </div>
            </div>
        </div>

        <div id="nav-drop-account-div" class="hidden z-10 absolute right-0 top-[45px] flex flex-col justify-start items-center bg-[#1f292e] text-white fixed w-[15%] h-[400px]">
                <div class="w-full h-[350px] flex flex-col justify-around items-center">
                    <?php if($_SESSION["user_name"] == "admin") {?>
                        <div id="admin-module-btn" class="w-2/3 h-[50px] border-b-2 border-[#29363d] flex flex-row justify-center items-center cursor-pointer opacity-30 hover:opacity-100 duration-150">
                            <h2 id="admin-module-id" class="">Admin</h2>
                        </div>
                    <?php } ?>
                    <div id="dashboard-incoming-btn" class="w-2/3 h-[50px] border-b-2 border-[#29363d] flex flex-row justify-center items-center cursor-pointer opacity-30 hover:opacity-100 duration-150">
                        <h2 class="">Dashboard (Incoming)</h2>
                    </div>

                    <div id="dashboard-outgoing-btn" class="w-2/3 h-[50px] border-b-2 border-[#29363d] flex flex-row justify-center items-center cursor-pointer opacity-30 hover:opacity-100 duration-150">
                        <h2 class="">Dashboard (Outgoing)</h2>
                    </div>

                    <div class="w-2/3 h-[50px] border-b-2 border-[#29363d] flex flex-row justify-center items-center cursor-pointer opacity-30 hover:opacity-100 duration-150">
                        <h2 class="">Dashboard (ER/OPD)</h2>
                    </div>

                    <div id="history-log-btn" class="w-2/3 h-[50px] border-b-2 border-[#29363d] flex flex-row justify-center items-center cursor-pointer opacity-30 hover:opacity-100 duration-150">
                        <h2 class="">History Log</h2>
                    </div>

                    <div class="w-2/3 h-[50px] border-b-2 border-[#29363d] flex flex-row justify-center items-center cursor-pointer opacity-30 hover:opacity-100 duration-150">
                        <h2 class="">Settings</h2>
                    </div>

                    <div class="w-2/3 h-[50px] border-b-2 border-[#29363d] flex flex-row justify-center items-center cursor-pointer opacity-30 hover:opacity-100 duration-150">
                        <h2 class="">Help</h2>
                    </div>

                    <div class="w-2/3 h-[50px] border-b-2 border-[#29363d] flex flex-row justify-center items-center cursor-pointer opacity-30 hover:opacity-100 duration-150">
                        <h2 id='logout-btn' class="" data-bs-toggle="modal" data-bs-target="#myModal-main">Logout</h2>
                    </div>
                </div>
        </div>

        <div class="flex flex-row justify-start items-center w-full h-full"> 

            <aside id="side-bar-div" class="z-10 side-bar-div text-lg w-[17%] h-full flex flex-col justify-start items-center bg-mainColor duration-200 ml-0">
                <div class="w-[95%] h-[10%] flex flex-row justify-center items-center text-center border-b-4 border-[#29363d]">
                    <img src="assets/login_imgs/main_bg.png" alt="logo-img" class="w-[28%] h-[75%]" />
                    <p class="text-white text-sm w-[65%]">Bataan General Hospital and Medical Center</p>
                </div>

                <div id="main-side-bar-1" class="w-full h-auto flex flex-col justify-start items-center cursor-pointer text-base">
                    <div class="w-full h-[50px] flex flex-row justify-start items-center">
                        <i class="ml-3 fa-solid fa-hospital-user text-lg text-white opacity-80"></i>
                        <h3 class="ml-3 mt-1 text-white">Patient Registration</h3>
                    </div>

                    <div id="sub-side-bar-1" class="w-full h-auto bg-[#1f292e]">
                        <div id="patient-reg-form-sub-side-bar" class="w-full h-[50px] flex flex-row justify-start items-center opacity-30 hover:opacity-100 duration-150">
                            <i class="ml-8 fa-solid fa-hospital-user text-lg text-white opacity-80"></i>  
                            <h3 class="ml-2 text-white">Patient Registration Form</h3>
                        </div>
                    </div>
                </div>


                    <div id="main-side-bar-2" class="w-full h-auto flex flex-col justify-start items-center cursor-pointer text-base">
                        <div class="w-full h-[50px] flex flex-row justify-start items-center">
                            <i class="ml-3 fa-solid fa-retweet text-lg text-white opacity-80"></i>
                            <h3 class="m-3 text-white">Online Referral </h3>
                        </div>

                        <div id="sub-side-bar-2" class="w-full h-auto bg-[#1f292e]">
                            <div id="outgoing-sub-div-id" class="w-full h-[50px] flex flex-row justify-start items-center border-b border-[#29363d] opacity-30 hover:opacity-100 duration-150">
                                <i class="fa-solid fa-inbox ml-8 text-lg text-white opacity-80"></i>
                                <h3 class="m-3 text-white">Outgoing</h3>
                            </div>
                            <div id="incoming-sub-div-id" class="w-full h-[50px] flex flex-row justify-start items-center border-b border-[#29363d] opacity-30 hover:opacity-100 duration-150">
                                <!-- <h3 class="m-16 text-white">Incoming</h3> -->
                                <i class="fa-solid fa-inbox ml-8 text-lg text-white opacity-80"></i>
                                <h3 class="m-3 text-white">Incoming</h3>
                            </div>
                            <!-- <div id="pcr-request-id" class="w-full h-[50px] flex flex-row justify-start items-center border-b border-[#29363d] opacity-30 hover:opacity-100 duration-150">
                                <i class="fa-solid fa-inbox ml-8 text-lg text-white opacity-80"></i>
                                <h3 class="m-3 text-white">PCR Request List</h3>
                            </div> -->
                        </div>
                    </div>
            </aside>

            
            
            

            <div id="container" class="w-full h-full flex flex-row justify-center items-center">
            
            </div>
            <!-- ADMIN MODULE -->
        
        </div>
        
    </div>

    <!-- Modal -->
    <div class="modal fade" id="myModal-main" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header flex flex-row justify-between items-center">
                <div class="flex flex-row justify-between items-center">
                    <h5 id="modal-title-main" class="modal-title-main" id="exampleModalLabel">Warning</h5>
                    <i id="modal-icon" class="fa-solid fa-triangle-exclamation ml-2"></i>
                    <!-- <i class="fa-solid fa-circle-check"></i> -->
                </div>
                <button type="button" class="close text-3xl" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modal-body-main" class="modal-body-main ml-7">
                Are you sure you want to logout?
            </div>
            <div class="modal-footer">
                <button id="ok-modal-btn-main" type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2" data-bs-dismiss="modal">OK</button>
                <button id="yes-modal-btn-main" type="button" class="hidden bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2" data-bs-dismiss="modal">Yes</button>
            </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <script type="text/javascript"  charset="utf8" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>


    <script src="./js/main_styling.js?v=<?php echo time(); ?>"></script>
    <script src="./js/patient_register_form.js?v=<?php echo time(); ?>"></script>
    <script src="./js/opd_referral_form.js?v=<?php echo time(); ?>"></script>
    <script src="./js/location.js?v=<?php echo time(); ?>"></script>
    <script src="./js/search_name_2.js?v=<?php echo time(); ?>"></script>
    <script src="./js/opd_referral_form.js?v=<?php echo time(); ?>"></script>
    
    <!-- <script src="./js/incoming_form_2.js?v=<?php echo time(); ?>"></script> -->
    <!-- <script src="./js/fetch_interval.js?v=<?php echo time(); ?>"></script> -->
</body>
</html>

