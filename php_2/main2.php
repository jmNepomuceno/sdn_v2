<?php
    session_start();
    include('../database/connection2.php');

    if ($_SESSION['user_name'] === 'admin'){
        $user_name = 'Bataan General Hospital and Medical Center';
    }else{
        $user_name = $_SESSION['hospital_name'];
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SDN</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <link rel="stylesheet" href="../css/main_style.css" />
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
    <input id="current-page-input" type="hidden" name="current-page-input" value="" />
    <input id="clicked-logout-input" type="hidden" name="clicked-logout-input" value="" />
    

    <div id="main-div">
        <header class="header-div">
            <div class="side-bar-title">
                <h1 id="sdn-title-h1"> Service Delivery Network</h1>
                <div class="side-bar-mobile-btn">
                    <i id="navbar-icon" class="fa-solid fa-bars"></i>
                </div>
            </div>
            <div class="account-header-div">
                <div class="notif-main-div">
                    <!-- <div class="w-[33.3%] h-full   flex flex-row justify-end items-center -mr-1">
                        <h1 class="text-center w-full rounded-full p-1 bg-yellow-500 font-bold">6</h1>
                    </div> -->
                    
                        <div id="notif-div">
                            <h1 id="notif-circle"><span id="notif-span"></span></h1>
                            <i class="fa-solid fa-bell"></i> 
                            <audio id="notif-sound" preload='auto' muted loop>
                                <source src="../assets/sound/water_droplet.mp3" type="audio/mpeg">
                            </audio>

                            <div id="notif-sub-div">
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

                <div id="nav-account-div" class="header-username-div">
                    <div class="user-icon-div">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div class="user-name-div">
                        <!-- <h1 class="text-white text-lg hidden sm:block">John Marvin Nepomuceno</h1> -->
                        <?php 
                            if($_SESSION['last_name'] === 'Administrator'){
                                echo '<h1 id="user_name-id">' . $user_name . ' | ' . $_SESSION["last_name"] . '</h1>';
                            }else{
                                echo '<h1 id="user_name-id">' . $user_name . ' | ' . $_SESSION["last_name"] . ', ' . $_SESSION['first_name'] . ' ' . $_SESSION['middle_name'] . '</h1>';;

                            }
                        ?>
                        
                    </div>
                    <div class="username-caret-div">
                        <i class="fa-solid fa-caret-down"></i>
                    </div>
                </div>
            </div>
        </header>

        <div id="nav-drop-account-div">
            <div id="nav-drop-acc-sub-div">
                <?php if($_SESSION["user_name"] == "admin") {?>
                    <div id="admin-module-btn" class="nav-drop-btns">
                        <h2 id="admin-module-id" class="nav-drop-btns-txt">Admin</h2>
                    </div>
                <?php } ?>
                <div id="dashboard-incoming-btn" class="nav-drop-btns">
                    <h2 class="nav-drop-btns-txt">Dashboard (Incoming)</h2>
                </div>

                <div id="dashboard-outgoing-btn" class="nav-drop-btns">
                    <h2 class="nav-drop-btns-txt">Dashboard (Outgoing)</h2>
                </div>

                <div class="nav-drop-btns">
                    <h2 class="nav-drop-btns-txt">Dashboard (ER/OPD)</h2>
                </div>

                <div id="history-log-btn" class="nav-drop-btns">
                    <h2 class="nav-drop-btns-txt">History Log</h2>
                </div>

                <div class="nav-drop-btns">
                    <h2 class="nav-drop-btns-txt">Settings</h2>
                </div>

                <div class="nav-drop-btns">
                    <h2 class="nav-drop-btns-txt">Help</h2>
                </div>

                <div class="nav-drop-btns">
                    <h2 id='logout-btn' class="nav-drop-btns-txt" data-bs-toggle="modal" data-bs-target="#myModal-main">Logout</h2>
                </div>
            </div>
        </div>

        <div class="aside-main-div"> 

            <aside id="side-bar-div">
                <div id="side-bar-title-bgh">
                    <img src="../assets/login_imgs/main_bg.png" alt="logo-img">
                    <p id="bgh-name">Bataan General Hospital and Medical Center</p>
                </div>

                <div id="main-side-bar-1">
                    <div id="main-side-bar-1-subdiv">
                        <i class="fa-solid fa-hospital-user"></i>
                        <h3>Patient Registration</h3>
                    </div>

                    <div id="sub-side-bar-1">
                        <div id="patient-reg-form-sub-side-bar">
                            <i class="fa-solid fa-hospital-user"></i>  
                            <h3>Patient Registration Form</h3>
                        </div>
                    </div>
                </div>

                <div id="main-side-bar-2">
                    <div id="main-side-bar-2-subdiv">
                        <i class="fa-solid fa-retweet"></i>
                        <h3>Online Referral </h3>
                    </div>

                    <div id="sub-side-bar-2">
                        <div id="outgoing-sub-div-id">
                            <i class="fa-solid fa-inbox"></i>
                            <h3>Outgoing</h3>
                        </div>
                        <div id="incoming-sub-div-id">
                            <!-- <h3 class="m-16 text-white">Incoming</h3> -->
                            <i class="fa-solid fa-inbox"></i>
                            <h3>Incoming</h3>
                        </div>
                        <div id="interdept-sub-div-id">
                            <i class="fa-solid fa-inbox"></i>
                            <h3 class="m-3 text-white">Interdept</h3>
                        </div>
                    </div>
                </div>

               
            </aside>

            
            
            

            <div id="container">
            
            </div>
            <!-- ADMIN MODULE -->
        
        </div>
        
    </div>

    <!-- Modal -->
    <div class="modal fade" id="myModal-main" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title-div">
                    <h5 id="modal-title-main" class="modal-title-main" id="exampleModalLabel">Warning</h5>
                    <i id="modal-icon" class="fa-solid fa-triangle-exclamation"></i>
                    <!-- <i class="fa-solid fa-circle-check"></i> -->
                </div>
                <button id="x-btn" type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modal-body-main" class="modal-body-main">
                Are you sure you want to logout?
            </div>
            <div class="modal-footer">
                <button id="ok-modal-btn-main" type="button" data-bs-dismiss="modal">OK</button>
                <button id="yes-modal-btn-main" type="button" data-bs-dismiss="modal">Yes</button>
            </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    
    <script type="text/javascript"  charset="utf8" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>


    <script src="../js_2/main_style.js?v=<?php echo time(); ?>"></script>
    <script src="../js/location.js?v=<?php echo time(); ?>"></script>

    <!-- <script src="../js_2/patient_register_form2.js?v=<?php echo time(); ?>"></script>
    <script src="../js/search_name_2.js?v=<?php echo time(); ?>"></script>     -->
    
    <!-- <script src="./js/incoming_form_2.js?v=<?php echo time(); ?>"></script> -->
    <!-- <script src="./js/fetch_interval.js?v=<?php echo time(); ?>"></script> -->
</body>
</html>