<?php
    session_start();
    include('../database/connection2.php');
    $sql = "SELECT classifications FROM classifications";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // echo '<pre>'; print_r($data); echo '</pre>';

    if ($_SESSION['user_name'] === 'admin'){
        $user_name = 'Bataan General Hospital and Medical Center';
    }else{
        $user_name = $_SESSION['hospital_name'];
    }

    $classification_arr = array();
    for($i = 0; $i < count($data); $i++){
        array_push($classification_arr, $data[$i]['classifications']);
    }

    // print_r($classification_arr);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SDN</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="../output.css">

</head>
<body>
    <input id="tertiary-case" type="hidden" name="tertiary-case" value="">
    <input id="hpercode-input" type="hidden" name="hpercode-input" value="">
    <input id="hpatcode-input" type="hidden" name="hpatcode-input" value=<?php echo $_SESSION["hospital_code"] ?>>

    <main id="patient-reg-form-div" class="flex flex-col justify-start items-center w-full h-full bg-white duration-0">

        <div class="w-full h-[50px] flex flex-row flex flex-row justify-start items-center">
            <div class="w-[20%] h-full flex flex-row justify-around items-center">
                <button class="h-[79%] w-[78px] mt-3 z-10 border-t-2 border-r-2 border-l-2 border-[#bfbfbf] bg-white">Patient</button>
                <button class="text-blue-500 h-[79%] w-[100px] mt-3 bg-white"></button>
                <button class="text-blue-500 h-[79%] w-[100px] mt-3 bg-white"></button>
            </div>
            <div id="privacy-reminder-div" class="hidden w-[75%] h-[60%] flex flex-row justify-around items-baseline rounded-lg bg-amber-100 mt-3">
                <p class="text-sm"> <span class="font-bold">Notice: </span>For data integrity purposes. Changing of Name and Birthday will be restricted. Please send an email to bataan.bghmc.ihomp@gmail.com for your request of patient name change. </p>
                <button class="text-lg">x</button>
            </div>
        <!-- w-[492px] -->
            <button id="check-if-registered-btn" class="absolute w-[50px] h-[40px] mr-2 right-0 flex flex-row justify-around items-center bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg outline-none delay-150 duration-150">
                <i  class="cursor-pointer fa-solid fa-magnifying-glass text-2xl text-white"></i>
                <h3 id="check-if-registered-h3" class="hidden cursor-pointer">Check if the patient is already registered</h3>
            </button>
        </div>
            

        <div id="check-if-registered-div" class="hidden right-[8px] top-[92px] flex flex-col justify-start items-center bg-white border-b-2 border-r-2 border-l-2 border-[#bfbfbf] fixed w-[493px] h-[80%] rounded-b-lg delay-150 duration-150">
            <div class="w-full h-[18%] flex flex-col justify-between items-center">

                <div class="w-full h-[50%] mt-2 ">
                    <form action="" class="w-full h-full flex flex-row justify-around items-center">

                        <div class="w-[33%] h-full flex flex-col justify-center items-center">
                            <label for="search-lname" class="-ml-16 font-bold"> Last Name</label>
                            <input id="search-lname" type="text" name="search-lname" class="text-sm w-[95%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2" autocomplete="off" placeholder="Last Name">
                        </div>

                        <div class="w-[33%] h-full flex flex-col justify-center items-center">
                            <label for="search-fname" class="-ml-16 font-bold"> First Name</label>
                            <input id="search-fname" type="text" sname="search-fname" class="text-sm w-[95%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2" autocomplete="off" placeholder="First Name">
                        </div>

                        <div class="w-[33%] h-full flex flex-col justify-center items-center">
                            <label for="search-mname" class="-ml-11 font-bold"> Middle Name</label>
                            <input id="search-mname" type="text" name="search-mname" class="text-sm w-[95%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2" autocomplete="off" placeholder="Middle Name">
                        </div>

                    </form>
                </div>

                <button id="search-patient-btn" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded">
                    Search
                </button>

                <div class="w-[95%] h-[30%] flex flex-row justify-around items-center border-b-2 border-black">
                    <h3>Name</h3>
                    <h3>Birthday</h3>
                </div>

            </div>
            <div id="search-result-div" class="w-[95%] h-[82%] flex flex-col justify-start items-center">
                <!-- <h1 class="mt-2">No Patient Found</h1> -->
                
                <!-- SEARCH QUERY RESULT -->
                <div class="search-sub-div"></div>
                <!-- <div class="w-full h-[80px] flex flex-col justify-center items-center border-b border-black bg-[#e6e6e6]">
                    <div class="w-full h-[40%] flex flex-row justify-between items-center">
                        <h1 class="ml-2">Patient ID: 292719</h1>
                        <div class="w-[25%] h-full flex flex-row justify-around items-center">
                            <h1>9/22/2023</h1>
                            <span class="fa-solid fa-user"></span>
                        </div>
                    </div>
                    <div class="w-full h-[40%] flex flex-row justify-start items-center">
                        <h3 class="uppercase ml-2">Nepomuceno, John Marvin Gomez</h3>
                    </div>
                </div> -->

            </div>
        </div>

        <!-- <div class="w-full flex flex-row justify-end items-center mr-2">
            <button id="add-patform-btn-id" class="bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-2 px-4 rounded mr-2">Add</button>
            <button id="clear-patform-btn-id" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded h-[40px]">Clear</button>
        </div> -->

        <div class="w-[99%] h-[95%]  border-2 border-[#bfbfbf] border-2">
            <form action="" class="w-full h-full flex flex-row justify-evenly items-center">
                <aside class="w-[15%] h-full">
                    <!-- FUNCTION BUTTONS -->
                    <div class="patient-form-btns w-full mt-3 h-full flex flex-col justify-start items-center">       
                        <div class="w-full h-auto flex flex-row justify-center items-start mt-3">
                            <!-- <select id="classification-dropdown" class="hidden bg-[#526c7a] w-full text-white font-bold py-2 px-4 rounded outline-none cursor-pointer text-xl">
                                <option value="">Classification</option>
                                <option class="cursor-pointer" value="er">ER</option>
                                <option class="cursor-pointer" value="ob">OB</option>
                                <option class="cursor-pointer" value="opd">OPD</option>
                                <option class="cursor-pointer" value="pcr">PCR</option>
                            </select> -->
                            <!-- classification_arr -->
                            <select id="classification-dropdown" class="hidden bg-[#526c7a] w-full text-white font-bold py-2 px-4 rounded outline-none cursor-pointer text-xl">
                                <option value="">Classification</option>
                                <?php for($i = 0; $i < count($classification_arr); $i++){ ?>
                                    <option class="cursor-pointer" value=<?php echo strtolower($classification_arr[$i]) ?>><?php echo $classification_arr[$i] ?></option>
                                <?php }?>
                            </select>
                        </div>

                        <div id="add-clear-btn-div" class="w-full flex flex-row justify-evenly items-center">
                            <button id="add-patform-btn-id" class="bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-2 px-4 rounded">Add</button>
                            <button id="clear-patform-btn-id" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded h-[40px]">Clear</button>
                        </div>
                    </div>
                </aside>
                
                <div id="patient-reg-form-div-1" class="w-[27%] h-full flex flex-col justify-start items-center">
                    <!-- PERSONAL INFORMATION DIVSION -->
                    <div class="w-full h-[78%] flex flex-col justify-center items-center">
                        <div class="w-full h-[30px] flex flex-row justify-start items-center">
                            <h3 class="ml-3 font-bold">Personal Information</h3>
                        </div>
                        <!-- <div class="w-[98%] h-full border-2 border-[#bfbfbf] rounded-lg"> -->
                        <div class="w-[98%] h-full flex flex-col justify-evenly items-center border-2 border-[#bfbfbf] rounded-lg">

                                <div class="w-[90%] h-[66px] flex flex-col justify-around items-center">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-base ml-3" for="hperson-last-name"> Last Name </label>
                                    </div>
                                    <input id="hperson-last-name" type="text" name="hperson-last-name" class="w-[95%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2" autocomplete="off" placeholder="Dela Cruz" required>
                                </div>

                                <div class="w-[90%] h-[66px] flex flex-col justify-around items-center">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-base ml-3" for="hperson-first-name"> First Name </label>
                                    </div>
                                    <input id="hperson-first-name" type="text" name="hperson-first-name" class="w-[95%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2" autocomplete="off" placeholder="Juan" required>
                                </div>

                                <div class="w-[90%] h-[66px] flex flex-row justify-around items-center">
                                    
                                    <div class="w-[70%] h-full flex-col justify-around items-center">
                                        <div class="w-full flex flex-row justify-start items-center p-1">
                                            <label class="text-base ml-3" for="hperson-middle-name"> Middle Name </label>
                                        </div>
                                        <input id="hperson-middle-name" type="text" name="hperson-middle-name" class="ml-3 w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2" autocomplete="off" placeholder="Santos" required>
                                    </div>
                                    
                                    <div class="w-[30%] h-full flex-col justify-around items-center">
                                        <div class="w-full flex flex-row justify-start items-center p-1">
                                            <label class="text-base" for="hperson-ext-name"> Name Ext. </label>
                                        </div>
                                        <input id="hperson-ext-name" type="text" name="hperson-ext-name" class=" w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2" autocomplete="off" placeholder="Jr.">
                                    </div>
                                </div>

                                <div class="w-[90%] h-[66px] flex flex-row justify-around items-center">

                                    <div class="w-[70%] h-full flex-col justify-around items-center">
                                        <div class="w-full flex flex-row justify-start items-center p-1">
                                            <label class="text-base ml-3" for="hperson-birthday"> Birthday </label>
                                        </div>
                                        <input id="hperson-birthday" type="date" name="hperson-birthday" class="ml-3 w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2 cursor-pointer" autocomplete="off" required>
                                    </div>
                                    
                                    <div class="w-[30%] h-full flex-col justify-around items-center">
                                        <div class="w-full flex flex-row justify-start items-center p-1">
                                            <label class="text-base" for="hperson-age"> Age </label>
                                        </div>
                                        <input id="hperson-age" tabindex="-1" disabled="disabled" type="number" name="hperson-age" class="w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2 pointer-events-none " autocomplete="off">
                                    </div>

                                </div>

                                <div class="w-[90%] h-[66px] flex flex-row justify-around items-center">

                                    <div class="w-[30%] h-full flex-col justify-around items-center">
                                        <div class="w-full flex flex-row justify-start items-center p-1">
                                            <label class="text-base ml-3" for="hperson-gender"> Gender </label>
                                        </div>
                                        <!-- <input id="hperson-gender" type="text" name="hperson-gender" class="ml-3 w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2" autocomplete="off"> -->
                                        <select name="hperson-gender" id="hperson-gender" class="ml-3 text-sm w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none cursor-pointer" autocomplete="off" required>
                                            <option value="">Choose</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>
                                    
                                    <div class="w-[70%] h-full flex-col justify-around items-center">
                                        <div class="w-full flex flex-row justify-start items-center p-1">
                                            <label class="text-base ml-5" for="hperson-civil-status"> Civil Status </label>
                                        </div>
                                        <!-- <input id="hperson-civil-status" type="text" name="hperson-civil-status" class="ml-5 w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2" autocomplete="off"> -->
                                        <select name="hperson-civil-status" id="hperson-civil-status" class="ml-3 w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none cursor-pointer" autocomplete="off" required>
                                            <option value="">Choose</option>
                                            <option value="Single">Single</option>
                                            <option value="Married">Married</option>
                                            <option value="Divorced">Divorced</option>
                                            <option value="Widowed">Widowed</option>
                                        </select>
                                    </div>

                                </div>

                                <div class="w-[90%] h-[66px] flex flex-col justify-around items-center">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-base ml-3" for="hperson-religion"> Religion </label>
                                    </div>
                                    <input id="hperson-religion" type="text" name="hperson-religion" class="w-[95%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2" autocomplete="off" placeholder="ex. Roman Catholic" required>
                                </div>

                                <div class="w-[90%] h-[66px] flex flex-col justify-around items-center">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-base ml-3" for="hperson-occupation"> Occupation </label>
                                    </div>
                                    <input id="hperson-occupation" type="text" name="hperson-occupation" class="w-[95%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2" autocomplete="off" placeholder="ex. Doctor" required >
                                </div>

                                <div class="w-[90%] h-[66px] flex flex-row justify-around items-center">

                                    <div class="w-[50%] h-full flex-col justify-around items-center">
                                        <div class="w-full flex flex-row justify-start items-center p-1">
                                            <label class="text-base ml-3" for="hperson-nationality"> Nationality </label>
                                        </div>
                                        <input id="hperson-nationality" type="text" name="hperson-nationality" class="ml-3 w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2" autocomplete="off" placeholder="ex. Filipino" required>
                                    </div>
                                    
                                    <div class="w-[50%] h-full flex-col justify-around items-center">
                                        <div class="w-full flex flex-row justify-start items-center p-1">
                                            <label class="text-base ml-3" for="hperson-passport-no"> Passport No. </label>
                                        </div>
                                        <input id="hperson-passport-no" type="text" name="hperson-passport-no" class="ml-3 w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2" autocomplete="off" required>
                                    </div>

                                </div>
                        </div>
                    </div>

                    <!-- OTHERS -->
                    <div class="w-full h-[12%] flex flex-col justify-center items-center mt-4">
                        <div class="w-full h-[30px] flex flex-row justify-start items-center">
                            <h3 class="ml-3 font-bold">Others</h3>
                        </div>
                        <div class="w-[98%] h-full border-2 border-[#bfbfbf] rounded-lg flex flex-col justify-evenly items-center">
                            <div class="w-[90%] h-[66px] flex flex-row justify-around items-center">
                                <div class="w-[50%] h-full flex-col justify-around items-center">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-base ml-3" for="hperson-hospital-no"> Hospital No. </label>
                                    </div>
                                    <input id="hperson-hospital-no" type="number" name="hperson-hospital-no" class="ml-3 w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2 pointer-events-none bg-slate-500 font-bold text-white" autocomplete="off" value=<?php echo $_SESSION['hospital_code'] ?>>
                                </div>
                                
                                <div class="w-[50%] h-full flex-col justify-around items-center">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-base" for="hperson-phic">PHIC </label>
                                    </div>
                                    <input id="hperson-phic" type="text" name="hperson-phic" class=" w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2" autocomplete="off" placeholder="PhilHealth Number" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="patient-reg-form-div-2" class="w-[27%] h-full flex flex-col justify-start items-center">
                    <div class="w-full h-[45%] flex flex-col justify-center items-center">
                        <div class="w-full h-[30px] flex flex-row justify-start items-center">
                            <h3 class="ml-3 font-bold">Permanent Address</h3>
                        </div>
                        <div class="w-[98%] h-full flex flex-col justify-evenly items-center border-2 border-[#bfbfbf] rounded-lg">

                            <div class="w-[90%] h-[66px] flex flex-row justify-around items-center">
                                <div class="w-[50%] h-full flex-col justify-around items-center">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-base ml-3" for="hperson-house-no-pa"> House No./Lot/Bldg </label>
                                    </div>
                                    <input id="hperson-house-no-pa" type="text" name="hperson-house-no-pa" class="ml-3 w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2" autocomplete="off" placeholder="Lot 1" required>
                                </div>
                                
                                <div class="w-[50%] h-full flex-col justify-around items-center">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-base" for="hperson-street-block-pa"> Street/Block </label>
                                    </div>
                                    <input id="hperson-street-block-pa" type="text" name="hperson-street-block-pa" class=" w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2" autocomplete="off" placeholder="Block 1" required>
                                </div>
                            </div>

                            <div class="w-[90%] h-[66px] flex flex-row justify-around items-center">
                                <div class="w-[50%] h-full flex-col justify-around items-center">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-base ml-3" for="hperson-region-select-pa"> Region </label>
                                    </div>
                                    <!-- <input id="hperson-middle-name" type="text" name="hperson-middle-name" class="ml-3 w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2" autocomplete="off"> -->
                                    <select id="hperson-region-select-pa" required onchange="getLocations('region', 'pa-region')" name="region" class="ml-3 text-sm w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none cursor-pointer" autocomplete="off" required>
                                        <option value="" class="">Choose a Region</option>
                                        <?php 
                                            $stmt = $pdo->query('SELECT region_code, region_description from region');
                                            while($data = $stmt->fetch(PDO::FETCH_ASSOC)){
                                                echo '<option value="' , $data['region_code'] , '">' , $data['region_description'] , '</option>';
                                            }                                        
                                        ?>
                                    </select>
                                </div>
                                
                                <div class="w-[50%] h-full flex-col justify-around items-center">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-base" for="hperson-province-select-pa"> Province </label>
                                    </div>
                                    <!-- <input id="hperson-ext-name" type="text" name="hperson-ext-name" class=" w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2" autocomplete="off"> -->
                                    <select id="hperson-province-select-pa" required onchange="getLocations('province', 'pa-province')" name="province" class="ml-3 text-sm w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none cursor-pointer" required>
                                        <option value="" class="">Choose a Province</option>
                                    </select>
                                </div>
                            </div>

                            <div class="w-[90%] h-[66px] flex flex-row justify-around items-center">
                                <div class="w-[50%] h-full flex-col justify-around items-center">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-base ml-3" for="hperson-city-select-pa"> Municipality / City </label>
                                    </div>
                                    <!-- <input id="hperson-middle-name" type="text" name="hperson-middle-name" class="ml-3 w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2" autocomplete="off"> -->
                                    <select id="hperson-city-select-pa" required onchange="getLocations('city', 'pa-city')" name="city" class="ml-3 text-sm w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none cursor-pointer" required>
                                        <option value="" class="">Choose a Municipality</option>
                                    </select>
                                </div>
                                
                                <div class="w-[50%] h-full flex-col justify-around items-center">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-base" for="hperson-brgy-select-pa"> Barangay </label>
                                    </div>
                                    <!-- <input id="hperson-ext-name" type="text" name="hperson-ext-name" class=" w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2" autocomplete="off"> -->
                                    <select id="hperson-brgy-select-pa" required name="brgy" class="ml-3 text-sm w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none cursor-pointer" required>
                                        <option value="" class="">Choose a Barangay</option>
                                    </select>
                                </div>
                            </div>

                            <div class="w-[90%] h-[66px] flex flex-row justify-around items-center">
                                <div class="w-[50%] h-full flex-col justify-around items-center">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-base ml-3" for="hperson-home-phone-no-pa"> Home Phone No. </label>
                                    </div>
                                    <input id="hperson-home-phone-no-pa" type="text" name="hperson-home-phone-no-pa" class="ml-3 w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2" autocomplete="off">
                                </div>
                                
                                <div class="w-[50%] h-full flex-col justify-around items-center">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-base" for="hperson-mobile-no-pa"> Mobile Phone No. </label>
                                    </div>
                                    <input id="hperson-mobile-no-pa" type="number" name="hperson-mobile-no-pa" class=" w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2" autocomplete="off" required>
                                </div>
                            </div>

                            <div class="w-[90%] h-[66px] flex flex-row justify-start items-center">
                                <div class="w-[50%] h-full flex-col justify-around items-center">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-base ml-3" for="hperson-email-pa"> Email Address </label>
                                    </div>
                                    <input id="hperson-email-pa" type="email" name="hperson-email-pa" class="ml-3 w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="w-full h-[45%] flex flex-col justify-center items-center mt-4">
                        <div class="w-full h-[30px] flex flex-row justify-between items-center">
                            <h3 class="ml-3 font-bold">Current Address</h3>
                            <h3 id="same-as-perma-btn" class="mr-3 text-blue-500 cursor-pointer">Same as permanent</h3>
                        </div>
                        <div class="w-[98%] h-full flex flex-col justify-evenly items-center border-2 border-[#bfbfbf] rounded-lg">

                        <div class="w-[90%] h-[66px] flex flex-row justify-around items-center">
                                <div class="w-[50%] h-full flex-col justify-around items-center">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-base ml-3" for="hperson-house-no-ca"> House No./Lot/Bldg </label>
                                    </div>
                                    <input id="hperson-house-no-ca" type="text" name="hperson-house-no-ca" class="ml-3 w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2" autocomplete="off" placeholder="Lot 1">
                                </div>
                                
                                <div class="w-[50%] h-full flex-col justify-around items-center">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-base" for="hperson-street-block-ca"> Street/Block </label>
                                    </div>
                                    <input id="hperson-street-block-ca" type="text" name="hperson-street-block-ca" class=" w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2" autocomplete="off" placeholder="Block 1">
                                </div>
                            </div>

                            <div class="w-[90%] h-[66px] flex flex-row justify-around items-center ">
                                <div class="w-[48%] h-full flex-col justify-around items-center">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-base ml-1" for="hperson-region-select-ca"> Region CA </label>
                                    </div>
                                    <!-- <input id="hperson-middle-name" type="text" name="hperson-middle-name" class="ml-3 w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2" autocomplete="off"> -->
                                    <select id="hperson-region-select-ca" required onchange="getLocations('region' , 'ca-region')" name="region" class="ml-1 text-sm w-[96%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none cursor-pointer" autocomplete="off">
                                        <option value="" class="">Choose a Region</option>
                                        <?php 
                                            $stmt = $pdo->query('SELECT region_code, region_description from region');
                                            while($data = $stmt->fetch(PDO::FETCH_ASSOC)){
                                                echo '<option value="' , $data['region_code'] , '">' , $data['region_description'] , '</option>';
                                            }                                        
                                        ?>
                                    </select>
                                </div>
                                
                                <div class="w-[48%] h-full flex-col justify-around items-center">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-base" for="hperson-province-select-ca"> Province </label>
                                    </div>
                                    <!-- <input id="hperson-ext-name" type="text" name="hperson-ext-name" class=" w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2" autocomplete="off"> -->
                                    <select id="hperson-province-select-ca" required onchange="getLocations('province' , 'ca-province')" name="province" class="text-sm w-[93%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none cursor-pointer">
                                        <option value="ABUCAY" class="">Choose a Province</option>
                                    </select>
                                </div>
                            </div>

                            <div class="w-[90%] h-[66px] flex flex-row justify-around items-center">
                                <div class="w-[48%] h-full flex-col justify-around items-center">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-base ml-1" for="hperson-city-select-ca"> Municipality / City </label>
                                    </div>
                                    <!-- <input id="hperson-middle-name" type="text" name="hperson-middle-name" class="ml-3 w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2" autocomplete="off"> -->
                                    <select id="hperson-city-select-ca" required onchange="getLocations('city' , 'ca-city')" name="province" class="ml-1 text-sm w-[96%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none cursor-pointer">
                                        <option value="" class="">Choose a Municipality</option>
                                    </select>
                                </div>
                                
                                <div class="w-[48%] h-full flex-col justify-around items-center">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-base" for="hperson-brgy-select-ca"> Barangay </label>
                                    </div>
                                    <!-- <input id="hperson-ext-name" type="text" name="hperson-ext-name" class=" w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2" autocomplete="off"> -->
                                    <select id="hperson-brgy-select-ca" required name="province" class="text-sm w-[93%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none cursor-pointer">
                                        <option value="" class="">Choose a Barangay</option>
                                    </select>
                                </div>
                            </div>

                            <div class="w-[90%] h-[66px] flex flex-row justify-around items-center">
                                <div class="w-[50%] h-full flex-col justify-around items-center">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-base ml-3" for="hperson-home-phone-no-ca"> Home Phone No. </label>
                                    </div>
                                    <input id="hperson-home-phone-no-ca" type="text" name="hperson-home-phone-no-ca" class="ml-3 w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2" autocomplete="off">
                                </div>
                                
                                <div class="w-[50%] h-full flex-col justify-around items-center">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-base" for="hperson-mobile-no-ca"> Mobile Phone No. </label>
                                    </div>
                                    <input id="hperson-mobile-no-ca" type="number" name="hperson-mobile-no-ca" class=" w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2" autocomplete="off">
                                </div>
                            </div>

                            <div class="w-[90%] h-[66px] flex flex-row justify-start items-center">
                                <div class="w-[50%] h-full flex-col justify-around items-center">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-base ml-3" for="hperson-email-ca"> Email Address </label>
                                    </div>
                                    <input id="hperson-email-ca" type="email" name="hperson-email-ca" class="ml-3 w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2" autocomplete="off">
                                </div>
                            </div>

                        </div>

                    </div>

                    <!-- <div class="patient-form-btns w-full mt-3 h-[50px] right-[25%] bottom-[7px] flex flex-row justify-end items-center border-2 border-black">
                        <button id="add-patform-btn-id" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">Add</button>
                        <h3 id="clear-patform-btn-id" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mr-2 h-[40px]">Clear</h3>
                    </div> -->
                </div>

                <div id="patient-reg-form-div-3" class="w-[27%] h-full flex flex-col justify-start items-center">
                    <div class="w-full h-[45%] flex flex-col justify-center items-center">
                        <div class="w-full h-[30px] flex flex-row justify-start items-center">
                            <h3 class="ml-3 font-bold">Current Workplace Address</h3>
                        </div>
                        <div class="w-[98%] h-full flex flex-col justify-evenly items-center border-2 border-[#bfbfbf] rounded-lg">

                            <div class="w-[90%] h-[66px] flex flex-row justify-around items-center">
                                <div class="w-[50%]  h-full flex-col justify-around items-center">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-base ml-3" for="hperson-house-no-cwa"> House No./Lot/Bldg </label>
                                    </div>
                                    <input id="hperson-house-no-cwa" type="text" name="hperson-house-no-cwa" class="ml-3 w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2" autocomplete="off">
                                </div>
                                
                                <div class="w-[50%] h-full flex-col justify-around items-center">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-base" for="hperson-street-block-cwa"> Street/Block </label>
                                    </div>
                                    <input id="hperson-street-block-cwa" type="text" name="hperson-street-block-cwa" class=" w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2" autocomplete="off">
                                </div>
                            </div>

                            <div class="w-[90%] h-[66px] flex flex-row justify-around items-center">
                                <div class="w-[50%] h-full flex-col justify-around items-center">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-base ml-3" for="hperson-region-select-cwa"> Region </label>
                                    </div>
                                    <!-- <input id="hperson-middle-name" type="text" name="hperson-middle-name" class="ml-3 w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2" autocomplete="off"> -->
                                    <select id="hperson-region-select-cwa" required onchange="getLocations('region' , 'cwa-region')" name="region" class="ml-3 text-sm w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none cursor-pointer" autocomplete="off">
                                        <option value="" class="">Choose a Region</option>
                                        <?php 
                                            $stmt = $pdo->query('SELECT region_code, region_description from region');
                                            while($data = $stmt->fetch(PDO::FETCH_ASSOC)){
                                                echo '<option value="' , $data['region_code'] , '">' , $data['region_description'] , '</option>';
                                            }                                        
                                        ?>
                                    </select>
                                </div>
                                
                                <div class="w-[50%] h-full flex-col justify-around items-center">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-base" for="hperson-province-select-cwa"> Province </label>
                                    </div>
                                    <!-- <input id="hperson-ext-name" type="text" name="hperson-ext-name" class=" w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2" autocomplete="off"> -->
                                    <select id="hperson-province-select-cwa" required onchange="getLocations('province' , 'cwa-province')" name="province" class="ml-3 text-sm w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none cursor-pointer">
                                        <option value="" class="">Choose a Province</option>
                                    </select>
                                </div>
                            </div>

                            <div class="w-[90%] h-[66px] flex flex-row justify-around items-center">
                                <div class="w-[50%] h-full flex-col justify-around items-center">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-base ml-3" for="hperson-city-select-cwa"> Municipality / City </label>
                                    </div>
                                    <!-- <input id="hperson-middle-name" type="text" name="hperson-middle-name" class="ml-3 w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2" autocomplete="off"> -->
                                    <select id="hperson-city-select-cwa" required onchange="getLocations('city' , 'cwa-city')" name="city" class="ml-3 text-sm w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none cursor-pointer">
                                        <option value="" class="">Choose a Municipality</option>
                                    </select>
                                </div>
                                
                                <div class="w-[50%] h-full flex-col justify-around items-center">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-base" for="hperson-brgy-select-cwa"> Barangay </label>
                                    </div>
                                    <!-- <input id="hperson-ext-name" type="text" name="hperson-ext-name" class=" w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2" autocomplete="off"> -->
                                    <select id="hperson-brgy-select-cwa" name="brgy" class="ml-3 text-sm w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none cursor-pointer">
                                        <option value="" class="">Choose a Barangay</option>
                                    </select>
                                </div>
                            </div>

                            <div class="w-[90%] h-[66px] flex flex-row justify-around items-center">
                                <div class="w-[50%] h-full flex-col justify-around items-center">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-base ml-3" for="hperson-workplace-cwa"> Name of Workplace </label>
                                    </div>
                                    <input id="hperson-workplace-cwa" type="text" name="hperson-workplace-cwa" class="ml-3 w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2" autocomplete="off">
                                </div>
                                
                                <div class="w-[50%] h-full flex-col justify-around items-center">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-base" for="hperson-ll-mb-no-cwa"> Landline / Mobile Phone No. </label>
                                    </div>
                                    <input id="hperson-ll-mb-no-cwa" type="text" name="hperson-ll-mb-no-cwa" class=" w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2" autocomplete="off">
                                </div>
                            </div>

                            <div class="w-[90%] h-[66px] flex flex-row justify-start items-center">
                                <div class="w-[50%] h-full flex-col justify-around items-center">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-base ml-3" for="hperson-email-cwa"> Email Address </label>
                                    </div>
                                    <input id="hperson-email-cwa" type="text" name="hperson-email-cwa" class="ml-3 w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2" autocomplete="off">
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="w-full h-[54%] flex flex-col justify-center items-center mt-1">
                        <div class="w-full h-[30px] flex flex-row justify-start items-center">
                            <h3 class="ml-3 font-bold">Address Outside the Philippines (For OFW only) </h3>
                        </div>
                        <div class="w-[98%] h-full flex flex-col justify-evenly items-center border-2 border-[#bfbfbf] rounded-lg">
                            <div class="w-[90%] h-[66px] flex flex-row justify-around items-center">
                                <div class="w-full  h-full flex-col justify-around items-center">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-base ml-3" for="hperson-emp-name-ofw"> Employers Name </label>
                                    </div>
                                    <input id="hperson-emp-name-ofw" type="text" name="hperson-emp-name-ofw" class="ml-3 w-[92%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2" autocomplete="off">
                                </div>
                            </div>

                            <div class="w-[90%] h-[66px] flex flex-row justify-around items-center">
                                <div class="w-[50%] h-full flex-col justify-around items-center">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-base ml-3" for="hperson-occupation-ofw"> Occupation </label>
                                    </div>
                                    <input id="hperson-occupation-ofw" type="text" name="hperson-occupation-ofw" class="ml-3 w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2" autocomplete="off">
                                </div>
                                
                                <div class="w-[50%] h-full flex-col justify-around items-center">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-base" for="hperson-place-work-ofw"> Place of Work </label>
                                    </div>
                                    <input id="hperson-place-work-ofw" type="text" name="hperson-place-work-ofw" class=" w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2" autocomplete="off">
                                </div>
                            </div>

                            <div class="w-[90%] h-[66px] flex flex-row justify-around items-center">
                                <div class="w-[50%] h-full flex-col justify-around items-center">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-base ml-3" for="hperson-house-no-ofw"> House No./Lot/Bldg </label>
                                    </div>
                                    <input id="hperson-house-no-ofw" type="text" name="hperson-house-no-ofw" class="ml-3 w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2" autocomplete="off">
                                </div>
                                
                                <div class="w-[50%] h-full flex-col justify-around items-center">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-base" for="hperson-street-ofw"> Street/Block </label>
                                    </div>
                                    <input id="hperson-street-ofw" type="text" name="hperson-street-ofw" class=" w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2" autocomplete="off">
                                </div>
                            </div>

                            <div class="w-[90%] h-[66px] flex flex-row justify-around items-center">
                                <div class="w-[50%] h-full flex-col justify-around items-center">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-base ml-3" for="hperson-region-select-ofw"> Region </label>
                                    </div>
                                    <input id="hperson-region-select-ofw" type="text" name="hperson-region-select-ofw" class="ml-3 w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2" autocomplete="off">
                                </div>
                                
                                <div class="w-[50%] h-full flex-col justify-around items-center">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-base" for="hperson-province-select-ofw"> Province </label>
                                    </div>
                                    <input id="hperson-province-select-ofw" type="text" name="hperson-province-select-ofw" class="w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2" autocomplete="off">
                                    <!-- <select id="hperson-province-select-ofw" required onchange="getLocations('province' , 'ofw-province')" name="province" class="ml-3 w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none cursor-pointer">
                                        <option value="" class="">Choose a Province</option>
                                    </select> -->
                                </div>
                            </div>

                            <div class="w-[90%] h-[66px] flex flex-row justify-around items-center">
                                <div class="w-[50%] h-full flex-col justify-around items-center">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-base ml-3" for="hperson-city-select-ofw"> Municipality / City </label>
                                    </div>
                                    <input id="hperson-city-select-ofw" type="text" name="hperson-city-select-ofw" class="ml-3 w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2" autocomplete="off">
                                    <!-- <select id="hperson-city-select-ofw" required onchange="getLocations('city' , 'ofw-city')" name="city" class="ml-3 w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none cursor-pointer">
                                        <option value="" class="">Choose a Municipality/City</option>
                                    </select> -->
                                </div>
                                
                                <div class="w-[50%] h-full flex-col justify-around items-center">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-base" for="hperson-country-select-ofw"> Country </label>
                                    </div>
                                    <input id="hperson-country-select-ofw" type="text" name="hperson-country-select-ofw" class="w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2" autocomplete="off">
                                    <!-- <select id="hperson-country-select-ofw" required name="country" class="ml-3 w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none cursor-pointer">
                                        <option value="" class="">Choose a Country</option>
                                    </select> -->
                                </div>
                            </div>

                            <div class="w-[90%] h-[66px] flex flex-row justify-start items-center">
                                <div class="w-[50%] h-full flex-col justify-around items-center">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-base ml-3" for="hperson-office-phone-no-ofw"> Office Phone No. </label>
                                    </div>
                                    <input id="hperson-office-phone-no-ofw" type="text" name="hperson-office-phone-no-ofw" class="ml-3 w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2" autocomplete="off">
                                </div>
                                <div class="w-[50%] h-full flex-col justify-around items-center">
                                    <div class="w-full flex flex-row justify-start items-center p-1">
                                        <label class="text-base" for="hperson-mobile-no-ofw"> Mobile Phone No. </label>
                                    </div>
                                    <input id="hperson-mobile-no-ofw" type="text" name="hperson-mobile-no-ofw" class=" w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
            </form>
        </div>

    </main>

    <!-- Modal -->
    <div class="modal fade" id="myModal_pat_reg" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header flex flex-row justify-between items-center">
                <div class="flex flex-row justify-between items-center">
                    <h5 id="modal-title" class="modal-title" id="exampleModalLabel">Warning</h5>
                    <i id="modal-icon" class="fa-solid fa-triangle-exclamation ml-2"></i>
                    <!-- <i class="fa-solid fa-circle-check"></i> -->
                </div>
                <button type="button" class="close text-3xl" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modal-body" class="modal-body">
                Please fill out the required fields.
            </div>
            <div class="modal-footer">
                <button id="ok-modal-btn" type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2" data-bs-dismiss="modal">OK</button>
                <button id="yes-modal-btn" type="button" class="hidden bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2" data-bs-dismiss="modal">Yes</button>
            </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="./js/patient_register_form.js?v=<?php echo time(); ?>"></script>
    <script src="./js/search_name_2.js?v=<?php echo time(); ?>"></script>    
</body>
</html>