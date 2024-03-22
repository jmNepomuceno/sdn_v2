$(document).ready(function(){
    const loadContent = (url) => {
        $.ajax({
            url:url,
            success: function(response){
                // console.log(response)
                $('#container').html(response);
            }
        })
    }

    // Function to parse query parameters from URL
    function getQueryVariable(variable) {
        var query = window.location.search.substring(1);
        var vars = query.split("&");
        for (var i = 0; i < vars.length; i++) {
            var pair = vars[i].split("=");
            if (pair[0] === variable) {
                return pair[1];
            }
        }
        return null;
    }

    // Check if the loadContent parameter exists in the URL
    var loadContentParam = getQueryVariable('loadContent');
    if (loadContentParam) {
        loadContent(loadContentParam);
    }else{
        loadContent('php/default_view.php');
    }

    jQuery.noConflict();
    let current_page = ""
    let fetch_timer = 0

    const playAudio = () =>{
        let audio = document.getElementById("notif-sound")
        audio.muted = false;
        audio.play().catch(function(error){
            'Error playing audio: ' , error
        })
    }

    const stopSound = () =>{
        let audio = document.getElementById("notif-sound")
        audio.pause;
        audio.muted = true;
        audio.currentTime = 0;
    }

    function fetchMySQLData() {
        $.ajax({
            url: 'php/fetch_interval.php',
            method: "POST",
            data : {
                from_where : 'bell'
            },
            success: function(response) {
                response = JSON.parse(response);  
                // console.log(response);
                // console.log('pot')

                $('#notif-span').text(response.length);
                if (parseInt(response.length) >= 1) {
                    if(current_page === 'incoming_page'){
                        stopSound()
                    }else{
                        playAudio();
                    }
                    timer_running = true;
                    $('#notif-circle').removeClass('hidden');
                    
                    // populate notif-sub-div
                    // document.querySelector('.notif-sub-div').innerHTML = 

                    let type_counter = []
                    for(let i = 0; i < response.length; i++){

                        if(!type_counter.includes(response[i]['type'])){
                            type_counter.push(response[i]['type'])
                        }
                    }

                    // console.log(type_counter)
                    
                    document.getElementById('notif-sub-div').innerHTML = '';
                    for(let i = 0; i < type_counter.length; i++){
                        let type_var  = type_counter[i]
                        let type_counts  = 0

                        for(let j = 0; j < response.length; j++){
                            if(type_counter[i] ===  response[j]['type']){
                                type_counts += 1
                            }
                        }

                        if(i % 2 === 0){
                            document.getElementById('notif-sub-div').innerHTML += '\
                            <div class="h-[30px] w-[90%] border border-black flex flex-row justify-evenly items-center mt-1 bg-transparent text-white opacity-30 hover:opacity-100">\
                            <h4 class="font-bold text-lg">' + type_counts + '</h4>\
                                <h4 class="font-bold text-lg">' + type_var + '</h4>\
                            </div>\
                        ';
                        }else{
                            document.getElementById('notif-sub-div').innerHTML += '\
                            <div class="h-[30px] w-[90%] border border-black flex flex-row justify-evenly items-center mt-1 bg-white opacity-30 hover:opacity-100">\
                            <h4 class="font-bold text-lg">' + type_counts + '</h4>\
                                <h4 class="font-bold text-lg">' + type_var + '</h4>\
                            </div>\
                        ';
                        }
                    }

                } else {
                    $('#notif-circle').addClass('hidden');
                    stopSound()
                }
                
                fetch_timer = setTimeout(fetchMySQLData, 10000);
            }
        });
    }   

    fetchMySQLData(); 

    let side_bar_btn_counter = 0
    $('#side-bar-mobile-btn').on('click' , function(event){
        document.querySelector('#side-bar-div').classList.toggle('hidden');

        if(side_bar_btn_counter === 0){
            document.querySelector('#side-bar-mobile-btn').className = 'side-bar-mobile-btn w-[50%] ml-2 h-[10px] absolute flex flex-row justify-start items-center cursor-pointer transition duration-700 ease-in-out'
            side_bar_btn_counter = 1;
            $('#sdn-title-h1').addClass('hidden')
        }else{
            document.querySelector('#side-bar-mobile-btn').className = 'side-bar-mobile-btn w-[50%] ml-2 h-full flex flex-row justify-start items-center cursor-pointer delay-150'
            $('#sdn-title-h1').removeClass('hidden')
            side_bar_btn_counter = 0;
        }
        
    })

    
    $('#logout-btn').on('click' , function(event){
        
        event.preventDefault(); // Prevent the default behavior (navigating to the link)

        $('#modal-title-main').text('Warning')
        // $('#modal-body').text('Are you sure you want to logout?')
        $('#ok-modal-btn-main').text('No')

        $('#yes-modal-btn-main').text('Yes');
        $('#yes-modal-btn-main').removeClass('hidden')

        // $('#myModal-main').modal('show');

    })

    $('#yes-modal-btn-main').on('click' , function(event){
        console.log('here')
        document.querySelector('#nav-drop-account-div').classList.toggle('hidden');

        let currentDate = new Date();

        let year = currentDate.getFullYear();
        let month = currentDate.getMonth() + 1; // Adding 1 to get the month in the human-readable format
        let day = currentDate.getDate();

        let hours = currentDate.getHours();
        let minutes = currentDate.getMinutes();
        let seconds = currentDate.getSeconds();

        let final_date = year + "/" + month + "/" + day + " " + hours + ":" + minutes + ":" + seconds
        // console.log('here')
        $.ajax({
            url: './php/save_process_time.php',
            data : {
                what: 'save',
                date : final_date,
                sub_what: 'logout'
            },
            method: "POST",
            success: function(response) {
                // response = JSON.parse(response);  
                console.log(response , " here")
                window.location.href = "./index.php" 
            }
        });
    })

    $('#ok-modal-btn-main').on('click' , function(event){
        console.log('asdfc')
    })

    $('#side-bar-mobile-btn').on('click' , function(event){
        event.preventDefault();
        document.querySelector('#side-bar-div').classList.toggle('-ml-[325px]');

        if(document.querySelector('#patient-reg-form-div-1')){
            if(document.querySelector('#patient-reg-form-div-1').classList.contains('w-[30%]')){
                document.querySelector('#patient-reg-form-div-1').classList.add('w-[25%]')
                document.querySelector('#patient-reg-form-div-1').classList.remove('w-[30%]')
            }else{
                document.querySelector('#patient-reg-form-div-1').classList.add('w-[30%]')
                document.querySelector('#patient-reg-form-div-1').classList.remove('w-[25%]')
            }

            if(document.querySelector('#patient-reg-form-div-2').classList.contains('w-[30%]')){
                document.querySelector('#patient-reg-form-div-2').classList.add('w-[25%]')
                document.querySelector('#patient-reg-form-div-2').classList.remove('w-[30%]')
            }else{
                document.querySelector('#patient-reg-form-div-2').classList.add('w-[30%]')
                document.querySelector('#patient-reg-form-div-2').classList.remove('w-[25%]')
            }

            if(document.querySelector('#patient-reg-form-div-3').classList.contains('w-[30%]')){
                document.querySelector('#patient-reg-form-div-3').classList.add('w-[25%]')
                document.querySelector('#patient-reg-form-div-3').classList.remove('w-[30%]')
            }else{
                document.querySelector('#patient-reg-form-div-3').classList.add('w-[30%]')
                document.querySelector('#patient-reg-form-div-3').classList.remove('w-[25%]')
            }
        }
        
        
        if(document.querySelector('#license-div')){
            if(document.querySelector('#license-div').classList.contains('w-full')){
                document.querySelector('#license-div').classList.add('w-[80%]')
                document.querySelector('#license-div').classList.remove('w-full')
            }else{
                document.querySelector('#license-div').classList.remove('w-[80%]')
                document.querySelector('#license-div').classList.add('w-full')
            }
        }

        
    })

    $('#nav-account-div').on('click' , function(event){
        event.preventDefault();
        document.querySelector('#nav-drop-account-div').classList.toggle('hidden');

    })

    // const loadContent = (url) => {
    //     $.ajax({
    //         url:url,
    //         success: function(response){
    //             // console.log(response)
    //             $('#container').html(response);
    //         }
    //     })
    // }

    loadContent('php/default_view.php')
    // loadContent('php/patient_register_form.php')
    // loadContent('php/opd_referral_form.php?type=OB&code=BGHMC-0001')
    // loadContent('php/incoming_form.php')
    // loadContent('php/outgoing_form.php')

    
    // loadContent('php_2/default_view2.php')


    $(window).on('load' , function(event){
        event.preventDefault();
        current_page = "default_page"
        $('#current-page-input').val(current_page)

        // loadContent('php/default_view.php')
        // loadContent('php/patient_register_form.php')
        // loadContent('php/opd_referral_form.php')
    })

    //$('#openModal')
    // $(window).on('load', function() {
    //     // $('#main-div').css('filter', 'blur(20px)');
    // });

    $(window).on('beforeunload', function() {
        localStorage.setItem('scrollPosition', $(window).scrollTop());
    });

    //welcome modal
    $('#closeModal').on('click' , function(event){
        $('#myModal').addClass('hidden')
        $('#main-div').css('filter', 'blur(0)');
        $('#modal-div').addClass('hidden')

        document.getElementById("notif-sound").play()
    })

    if(parseInt($('#notif-circle').text()) > 0){
        console.log("here")
        // document.getElementById("notif-sound").play()

        // setTimeout(function() {
        //     document.getElementById("notif-sound").play()
        // }, 2000); // Delay in milliseconds (2 seconds in this example)
    }

    $('#sdn-title-h1').on('click' , function(event){
        event.preventDefault();
        loadContent('php/default_view.php')
        
    })

    $('#dashboard-incoming-btn').on('click' , function(event){
        event.preventDefault();
        window.location.href = "../php/dashboard_incoming.php";
    })

    $('#dashboard-outgoing-btn').on('click' , function(event){
        event.preventDefault();
        window.location.href = "../php/dashboard_outgoing.php";
    })

    $('#history-log-btn').on('click' , function(event){
        event.preventDefault();
        // 
        let currentDate = new Date();

        let year = currentDate.getFullYear();
        let month = currentDate.getMonth() + 1; // Adding 1 to get the month in the human-readable format
        let day = currentDate.getDate();

        let hours = currentDate.getHours();
        let minutes = currentDate.getMinutes();
        let seconds = currentDate.getSeconds();

        let final_date = year + "/" + month + "/" + day + " " + hours + ":" + minutes + ":" + seconds
        // console.log('here')
        $.ajax({
            url: './php/save_process_time.php',
            data : {
                what: 'save',
                date : final_date,
                sub_what: 'history_log'
            },
            method: "POST",
            success: function(response) {
                window.location.href = "../php/history_log.php";
            }
        });
    })

    $('#admin-module-btn').on('click' , function(event){
        event.preventDefault();
        // 
        let currentDate = new Date();

        let year = currentDate.getFullYear();
        let month = currentDate.getMonth() + 1; // Adding 1 to get the month in the human-readable format
        let day = currentDate.getDate();

        let hours = currentDate.getHours();
        let minutes = currentDate.getMinutes();
        let seconds = currentDate.getSeconds();

        let final_date = year + "/" + month + "/" + day + " " + hours + ":" + minutes + ":" + seconds
        // console.log('here')
        $.ajax({
            url: './php/save_process_time.php',
            data : {
                what: 'save',
                date : final_date,
                sub_what: 'history_log'
            },
            method: "POST",
            success: function(response) {
                window.location.href = "../php/admin.php";
            }
        });
    })

    // NOTIFICATION FUNCTIONS
    // let num_pending = 0;

    // $('.pat-status-incoming').each(function() {
    //     // 'this' refers to the current element in the loop
    //     let str = $(this).text();
    //     str = str.replace(/\s/g, '');

    //     if(str === 'Pending'){
    //         num_pending++;
    //     }
    // });

    // let value = parseInt($('#notif-span').text())
    // $('#notif-span').text(num_pending)


    let notif_sub_div_open = false
    $('#notif-div').on('click' , function(event){
        console.log(notif_sub_div_open)

        if(!notif_sub_div_open){
            document.getElementById('notif-sub-div').style.display = 'none'
            notif_sub_div_open = true
        }else{
            notif_sub_div_open = false
            document.getElementById('notif-sub-div').style.display = 'flex'
        }
    })

    $('#notif-sub-div').on('click' , function(event){
        if(parseInt($('#notif-span').text() === 0)){
            console.log('here')
            $('#notif-circle').addClass('hidden')
            document.getElementById("notif-sound").pause();
            document.getElementById("notif-sound").currentTime = 0;
        }else{
            console.log('asdf')
            $('#notif-sub-div').addClass('hidden')
            loadContent('php/incoming_form.php')
            current_page = "incoming_page"
            $('#current-page-input').val(current_page)
        }

        document.getElementById('notif-sub-div').style.display = 'none'
        // $('#notif-sub-div').addClass('hidden')
    })

    // mikas
    // MIKAS3255

    $('#outgoing-sub-div-id').on('click' , function(event){
        event.preventDefault();

        loadContent('php/outgoing_form.php')
        current_page = "outgoing_page"
        $('#current-page-input').val(current_page)

        $('#outgoing-sub-div-id').removeClass('opacity-30')
        $('#outgoing-sub-div-id').addClass('opacity-100')
        $('#outgoing-sub-div-id').addClass('bg-[#0a0e0f]')

        // reset other side bar buttons
        $('#incoming-sub-div-id').addClass('opacity-30')
        $('#incoming-sub-div-id').removeClass('opacity-100')
        $('#incoming-sub-div-id').removeClass('bg-[#0a0e0f]')

        $('#patient-reg-form-sub-side-bar').addClass('opacity-30')
        $('#patient-reg-form-sub-side-bar').removeClass('opacity-100')
        $('#patient-reg-form-sub-side-bar').removeClass('bg-[#0a0e0f]')
    })

    $('#incoming-sub-div-id').on('click' , function(event){
        event.preventDefault();

        // stopSound()
        // clearTimeout(fetch_timer)
        // timer_running = false;

        loadContent('php/incoming_form.php')
        current_page = "incoming_page"
        $('#current-page-input').val(current_page)

        $('#incoming-sub-div-id').removeClass('opacity-30')
        $('#incoming-sub-div-id').addClass('opacity-100')
        $('#incoming-sub-div-id').addClass('bg-[#0a0e0f]')

        // reset other side bar buttons
        $('#outgoing-sub-div-id').addClass('opacity-30')
        $('#outgoing-sub-div-id').removeClass('opacity-100')
        $('#outgoing-sub-div-id').removeClass('bg-[#0a0e0f]')

        $('#patient-reg-form-sub-side-bar').addClass('opacity-30')
        $('#patient-reg-form-sub-side-bar').removeClass('opacity-100')
        $('#patient-reg-form-sub-side-bar').removeClass('bg-[#0a0e0f]')
    })

    $('#patient-reg-form-sub-side-bar').on('click' , function(event){
        event.preventDefault();

        loadContent('php/patient_register_form.php')
        current_page = "patient_register_page"
        $('#current-page-input').val(current_page)


        $('#patient-reg-form-sub-side-bar').removeClass('opacity-30')
        $('#patient-reg-form-sub-side-bar').addClass('opacity-100')
        $('#patient-reg-form-sub-side-bar').addClass('bg-[#0a0e0f]')

        // reset other side bar buttons
        $('#outgoing-sub-div-id').addClass('opacity-30')
        $('#outgoing-sub-div-id').removeClass('opacity-100')
        $('#outgoing-sub-div-id').removeClass('bg-[#0a0e0f]')

        $('#incoming-sub-div-id').addClass('opacity-30')
        $('#incoming-sub-div-id').removeClass('opacity-100')
        $('#incoming-sub-div-id').removeClass('bg-[#0a0e0f]')
    })

    $('#pcr-request-id').on('click' , function(event){
        event.preventDefault();
    })
    

})