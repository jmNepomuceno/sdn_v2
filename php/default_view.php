<?php 
    session_start();
    include('../database/connection2.php');
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../output.css">
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    

</head>
<body>
        <div id="default-div" class="flex flex-col justify-center items-center w-full h-full duration-0">
            <img class="absolute w-[25%] h-[50%] opacity-10" src="./assets/login_imgs/main_bg.png" alt="sdn-main-bg" />
            <h1 class="font-bold text-8xl">Service Delivery Network</h1>
            <h3 class="text-3xl mt-3">Bataan General Hospital and Medical Center</h3>

            <div id="license-div" class="absolute bottom-0 w-[80%] h-[10%] flex flex-col justify-around items-center bg-gray-300">
                <p class="font-bold">Philippine Copyright © 2023 Dr. Glory V. Baltazar</p>
                <p>This software program is protected by the Republic of the Philippines copyright laws. Reproduction and distribution of the software without prior written permission of the author is prohibited.</p>
                <p>If you wish to use the software for commercial or other purposes, please contact us at bgh_bataan2005@yahoo.com.ph.</p>
            </div>
        </div>


    <!-- <script>
        $(document).ready(function(){
            $('#sdn-title-h1').on('click' , function(event){
                const default_div = document.querySelector('#default-div')
                console.log(default_div)
                if(default_div.classList.contains('hidden')){
                    console.log("here")
                    // default_div.classList.remove('hidden')
                }else{
                    console.log("asdf")

                    // default_div.classList.add('hidden')
                }
            })
        })
    </script> -->
</body>
</html>