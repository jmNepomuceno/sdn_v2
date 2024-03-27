$(document).ready(function(){
    $('#myDataTable').DataTable({
        "bSort": false,
        "paging": true, 
        "pageLength": 6, 
        "lengthMenu": [ [6, 10, 25, 50, -1], [6, 10, 25, 50, "All"] ],
    });

    var dataTable = $('#myDataTable').DataTable();
    $('#myDataTable thead th').removeClass('sorting sorting_asc sorting_desc');
    dataTable.search('').draw(); 

    const myModal = new bootstrap.Modal(document.getElementById('pendingModal'));
    const defaultMyModal = new bootstrap.Modal(document.getElementById('myModal-incoming'));
    // myModal.show()

    let global_index = 0, global_paging = 1, global_timer = "", global_breakdown_index = 0;
    let final_time_total = "", update_seconds = 0;
    let length_curr_table = document.querySelectorAll('.hpercode').length;
    let toggle_accordion_obj = {}
    for(let i = 0; i < length_curr_table; i++){
        toggle_accordion_obj[i] = true
    }
    
    // activity/inactivity user
    let inactivityTimer;
    let running_timer_interval = "", running_timer_interval_update;
    let userIsActive = true;

    // reusable functions
    function updateInterdeptFunc(){
        let data = {
            hpercode : document.querySelectorAll('.hpercode')[global_index].value
        }
        //
        $('#span-time').text("")
        $('#span-status').text("Loading...")
        $('#span-dept').text("")
        $.ajax({
            url: '../php_2/fetch_update_interdept.php',
            method: "POST", 
            data:data,
            success: function(response){
                clearInterval(running_timer_interval_update)
                response = JSON.parse(response);   
                console.log(response)

                if(response[0]['status_interdept'] === "On-Process"){
                    const timeString = "00:59:48";

                    // Split the time string into an array using the ":" delimiter
                    const timeParts = timeString.split(":");

                    var hours = parseInt(timeParts[0]);
                    var minutes = parseInt(timeParts[1]);
                    var seconds = parseInt(timeParts[2]);

                    running_timer_interval_update = setInterval(function() {
                        seconds++;
            
                        if (seconds === 60) {
                            seconds = 0;
                            minutes++;
                        }
            
                        if (minutes === 60) {
                            minutes = 0;
                            hours++;
                        }
            
                        const formattedTime = pad(hours) + ':' + pad(minutes) + ':' + pad(seconds);
                        $('#v2-update-stat').text(`Last update: ${response[0]['currentDateTime']}`)

                        // <label for="" id="v2-stat"> <span id="span-dept">Surgery</span> - <span id="span-status">Pending</span> - <span id="span-time">00:00:00</span></span></label>
                        $('#span-dept').text(response[1].department.charAt(0).toUpperCase() + response[1].department.slice(1) + " | ") 
                        $('#span-status').text(response[0].status_interdept + " | ") 
                        $('#span-time').text(formattedTime)

                        // here
                        $('.interdept-div').css('display','none')
                        $('#cancel-btn').css('display','block')
                        $('.approval-main-content').css('display','none')
                        clearInterval(running_timer_interval)

                        // check if the status of the thingy is approve or deferred
                        // $.ajax({
                        //     url: '../php_2/fetch_update_interdept.php',
                        //     method: "POST", 
                        //     data:data,
                        //     success: function(response){
                                
                                
                        //         // document.querySelectorAll('.pat-status-incoming')[global_index].textContent = 'Pending - ' + $('#inter-depts-select').val().toUpperCase();;
                        //     }
                        // })

                    }, 1000); 
                }
                else if(response[0]['status_interdept'] === "Approved"){
                    $('#v2-update-stat').text(`Last update: ${response[1]['final_progress_date']}`)

                    // <label for="" id="v2-stat"> <span id="span-dept">Surgery</span> - <span id="span-status">Pending</span> - <span id="span-time">00:00:00</span></span></label>
                    $('#span-dept').text(response[1].department.charAt(0).toUpperCase() + response[1].department.slice(1) + " | ") 
                    $('#span-status').text(response[0].status_interdept + " | ") 
                    $('#span-time').text(response[1]['final_progress_time'])
                    console.log(response[0]['sent_interdept_time'] ,  response[1]['final_progress_time'])
                    
                    const [hours1, minutes1, seconds1] = response[0]['sent_interdept_time'].split(':').map(Number);
                    const [hours2, minutes2, seconds2] = response[1]['final_progress_time'].split(':').map(Number);
                    
                    // Create Date objects in UTC with the provided hours, minutes, and seconds
                    const date1 = new Date(Date.UTC(1970, 0, 1, hours1, minutes1, seconds1));
                    const date2 = new Date(Date.UTC(1970, 0, 1, hours2, minutes2, seconds2));
                    
                    const totalMilliseconds = date1.getTime() + date2.getTime();
                    
                    // Create a new Date object in UTC with the total milliseconds
                    const newDate = new Date(totalMilliseconds);
                    
                    // Format the result in UTC time "HH:mm:ss"
                    const result = `${String(newDate.getUTCHours()).padStart(2, '0')}:${String(newDate.getUTCMinutes()).padStart(2, '0')}:${String(newDate.getUTCSeconds()).padStart(2, '0')}`;
                    
                    console.log(result);
                    final_time_total = result
                    $('#final-approve-btn').css('display','block')
                }
                
            }
        })
    }

    function handleUserActivity() {
        userIsActive = true;
        // console.log('active')
    }

    function handleUserInactivity() {
        // console.log('inactive')
        userIsActive = false;
        $.ajax({
            url: '../php/fetch_interval.php',
            method: "POST",
            data : {
                from_where : 'incoming'
            },
            success: function(response) {
                // console.log(response)

                dataTable.clear();
                dataTable.rows.add($(response)).draw();

                length_curr_table = $('.tr-incoming').length
                for(let i = 0; i < length_curr_table; i++){
                    toggle_accordion_obj[i] = true
                }

                const pencil_elements = document.querySelectorAll('.pencil-btn');
                    pencil_elements.forEach(function(element, index) {
                    element.addEventListener('click', function() {
                        console.log('den')
                        ajax_method(index)
                    });
                });

                const expand_elements = document.querySelectorAll('.accordion-btn');
                    expand_elements.forEach(function(element, index) {
                    element.addEventListener('click', function() {
                        console.log(index)
                        global_breakdown_index = index;
                    });
                });
            }
        });
    }

    document.addEventListener('mousemove', handleUserActivity);

    const inactivityInterval = 115000; 

    function startInactivityTimer() {
        inactivityTimer = setInterval(() => {
            if (!userIsActive) {
                handleUserInactivity();
            }
            userIsActive = false;
            
        }, inactivityInterval);
    }

    startInactivityTimer();

    const ajax_method = (index, event) => {
        global_index = index
        const data = {
            hpercode: document.querySelectorAll('.hpercode')[index].value,
            from:'incoming'
        }
        console.log(data)
        $.ajax({
            url: '../php/process_pending.php',
            method: "POST", 
            data:data,
            success: function(response){
                document.querySelector('.ul-div').innerHTML = ''
                document.querySelector('.ul-div').innerHTML += response
                if(document.querySelectorAll('.pat-status-incoming')[index].textContent == 'Pending'){
                    runTimer(index, 0, 0, 0) // secs, minutes, hours
                    let data = {
                        hpercode : document.querySelectorAll('.hpercode')[index].value,
                        from : 'incoming'
                    }
                    $.ajax({
                        url: '../php_2/pendingToOnProcess.php',
                        method: "POST", 
                        data:data
                    })
                }

                // checking if the patient is already referred interdepartamentally
                console.log(data)
                $.ajax({
                    url: '../php_2/check_interdept_refer.php',
                    method: "POST", 
                    data:data,
                    success: function(response){
                        response = JSON.parse(response);    
                        console.log(typeof response[0])
                        if(response[0]){
                            $('#approval-form').css('display','none')
                            $('.interdept-div-v2').css('display','flex')
                            $('#cancel-btn').css('display','block')
                            

                            updateInterdeptFunc()
                        }
                    }
                })

                myModal.show();
            }
        })
    }

    const pencil_elements = document.querySelectorAll('.pencil-btn');
        pencil_elements.forEach(function(element, index) {
        element.addEventListener('click', function() {
            console.log('den')
            //myModal.show();
            
            ajax_method(index)

            // lobal_index = index

            // // possible redundant
            // const data = {
            //     hpercode: document.querySelectorAll('.hpercode')[index].value,
            //     from: 'incoming'
            // }
            // $.ajax({
            //     url: '../php/process_pending.php',
            //     method: "POST", 
            //     data:data,
            //     success: function(response){
            //         document.querySelector('.ul-div').innerHTML = ''
            //         document.querySelector('.ul-div').innerHTML += response
                    
            //         // if(document.querySelectorAll('.pat-status-incoming')[index].textContent == 'Pending'){
            //         //     console.log('here')
            //         //     runTimer(index, 0, 0, 0) // secs, minutes, hours
            //         // }
            //         myModal.show();

            //     }
            // })


        });
    });


    function pad(num) {
        return (num < 10 ? '0' : '') + num;
    }

    function timeToSeconds(timeString) {
        // Split the time string into hours, minutes, and seconds
        const [hours, minutes, seconds] = timeString.split(':').map(Number);
        
        // Calculate the total number of seconds
        const totalSeconds = hours * 3600 + minutes * 60 + seconds;
        
        return totalSeconds;
    }

    // if theres a timer running before the reload
    if($('#running-timer-input').val() !== "" && $('#running-timer-input').val() !== "00:00:00"){
        console.log('den' , $('#pat-curr-stat-input').val())

        if($('#pat-curr-stat-input').val() === ""){
            OfflineAudioCompletionEvent()   
            const parts = $('#running-timer-input').val().split(':');
            // Extract hours, minutes, and seconds
            let hours = 0;
            let minutes = 0;
            let seconds = 0;
            
            if (parts.length === 3) {
                hours = parseInt(parts[0], 10);
                minutes = parseInt(parts[1], 10);
                seconds = parseInt(parts[2], 10);
            } else if (parts.length === 2) {
                minutes = parseInt(parts[0], 10);
                seconds = parseInt(parts[1], 10);
            } else if (parts.length === 1) {
                seconds = parseInt(parts[0], 10);
            }
            runTimer(0, seconds, minutes, hours)
        }
    }

    function runTimer(index, sec, min, hrs){
        let seconds = sec;
        let minutes = min;
        let hours = hrs;

        running_timer_interval = setInterval(function() {
            seconds++;

            if (seconds === 60) {
                seconds = 0;
                minutes++;
            }

            if (minutes === 60) {
                minutes = 0;
                hours++;
            }

            const formattedTime = pad(hours) + ':' + pad(minutes) + ':' + pad(seconds);
            global_timer = formattedTime
            if(global_paging === 1){
                document.querySelectorAll('.stopwatch')[index].textContent = formattedTime;
                document.querySelectorAll('.pat-status-incoming')[index].textContent = 'On-Process';
            }
            $.ajax({
                url: '../php_2/session_timer.php',
                method: "POST", 
                data:{
                    formattedTime: formattedTime,
                    hpercode: document.querySelectorAll('.hpercode')[0].value,
                    from : 'incoming'
                },
                success: function(response){
                    // Display the time in the HTML element
                    
                }
            })

        }, 1000); 
    }

    window.addEventListener('beforeunload', function(event) {
        $.ajax({
            url: '../php/fetch_onProcess.php',
            method: "POST", 
            data:{
                timer: document.querySelectorAll('.stopwatch')[0].textContent,
                hpercode: document.querySelectorAll('.hpercode')[0].value
            },
            success: function(response){
                response = JSON.parse(response);   
                console.log(response)

                // document.querySelector('.referral-details').innerHTML += response
                // runTimer(index)
                // myModal.show();
            }
        })
    });

    // search incoming patients
    $('#incoming-search-btn').on('click' , function(event){        
        $('#incoming-clear-search-btn').css('opacity' , '1')
        $('#incoming-clear-search-btn').css('pointer-events' , 'auto')

        let valid_search = false;
        let elements = [$('#incoming-referral-no-search').val(), $('#incoming-last-name-search').val(), $('#incoming-first-name-search').val(),
        $('#incoming-middle-name-search').val(), $('#incoming-type-select').val(),  $('#incoming-agency-select').val(), $('#incoming-status-select').val()]

        for(let i = 0; i < elements.length; i++){
            if(elements[i] !== "" && i != elements.length - 1){
                valid_search = true
            }
            if(elements[i] !== 'default' && i == elements.length - 1){
                valid_search = true
            }
        }

        console.log(valid_search)

        if(valid_search){
            let data = {
                hpercode : document.querySelectorAll('.hpercode')[global_index].value,
                ref_no : $('#incoming-referral-no-search').val(),
                last_name : $('#incoming-last-name-search').val(),
                first_name : $('#incoming-first-name-search').val(),
                middle_name : $('#incoming-middle-name-search').val(),
                case_type : $('#incoming-type-select').val(),
                agency : $('#incoming-agency-select').val(),
                status : $('#incoming-status-select').val()
            }


            console.log(data)
            $.ajax({
                url: '../php/incoming_search.php',
                method: "POST", 
                data:data,
                success: function(response){

                    dataTable.clear();
                    dataTable.rows.add($(response)).draw();

                    length_curr_table = $('.tr-incoming').length
                    for(let i = 0; i < length_curr_table; i++){
                        toggle_accordion_obj[i] = true
                    }

                    const expand_elements = document.querySelectorAll('.accordion-btn');
                    expand_elements.forEach(function(element, index) {
                        element.addEventListener('click', function() {
                            console.log(index)
                            global_breakdown_index = index;
                        });
                    });
                }
            }) 
        }else{
            defaultMyModal.show()
        }

    })

    dataTable.on('page.dt', function () {
        // clearInterval(running_timer_interval)

        var currentPageIndex = dataTable.page();
        var currentPageNumber = currentPageIndex + 1;

        global_paging = currentPageNumber
    });

    function parseTimeToMilliseconds(timeString) {
        const [hours, minutes, seconds] = timeString.split(":");
        // console.log(hours, minutes, seconds)
        const totalMilliseconds = ((parseInt(hours, 10) * 60 + parseInt(minutes, 10)) * 60 + parseInt(seconds, 10)) * 1000;
        return totalMilliseconds;
        //5000
    }

    if($('#post-value-reload-input').val() === 'true'){
        $.ajax({
            url: '../php/save_process_time.php',
            method: "POST",
            data : {what: 'continue'},
            success: function(response){
                response = JSON.parse(response);  
                console.log(response)

                if(response.length > 0){
                    // Function to format time as HH:MM:SS
                    function millisecondsToHMS(milliseconds) {
                        // Calculate total seconds
                        let totalSeconds = Math.floor(milliseconds / 1000);
                        
                        // Calculate hours, minutes, and remaining seconds
                        let hours = Math.floor(totalSeconds / 3600);
                        let minutes = Math.floor((totalSeconds % 3600) / 60);
                        let remainingSeconds = totalSeconds % 60;
                        
                        // Format the result as HH:MM:SS
                        let formattedTime = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;
                        
                        return formattedTime;
                    }


                    
                    // Get the current time
                    let currentTime = new Date();
                    
                    // Given formatted time string
                    let formattedTimeString = response[0].logout_date;
                    
                    // Parse the formatted time string
                    let formattedTime = new Date(formattedTimeString);
                    
                    // Calculate the time difference
                    let timeDifference = currentTime - formattedTime;
                    // console.log(currentTime , formattedTime)

                    // console.log(timeDifference) // milliseconds

                    let final_time = millisecondsToHMS(timeDifference  + parseTimeToMilliseconds(response[0].progress_timer));
                    console.log(final_time);  // Output: "00:11:50"

                    // Split the time string by colon
                    const timeParts = final_time.split(':');

                    // Extract hours, minutes, and seconds
                    const hours = parseInt(timeParts[0], 10);
                    const minutes = parseInt(timeParts[1], 10);
                    const seconds = parseInt(timeParts[2], 10);

                    runTimer(0, seconds, minutes, hours)
                }
            }
        })
    }


    $('#inter-dept-referral-btn').on('click' , function(event){
        $('.interdept-div').css('display' , 'flex')
    })

    $('#int-dept-btn-forward').on('click' , function(event){
        // 
        $('#modal-title-incoming').text('Successed')
        document.querySelector('#modal-icon').className = 'fa-solid fa-circle-check'
        $('#modal-body-incoming').text('Successfully Forwarded')
        defaultMyModal.show()
        $('.interdept-div-v2').css('display' , 'flex')

        // window.location.href = 'http://192.168.42.222:8070/'


        let data = {
            dept : $('#inter-depts-select').val(),
            hpercode : document.querySelectorAll('.hpercode')[global_index].value,
            pause_time : global_timer
        }
        console.log(data)

        $.ajax({
            url: '../php_2/incoming_interdept.php',
            method: "POST", 
            data:data,
            success: function(response){
                $('.interdept-div').css('display','none')
                $('#cancel-btn').css('display','block')
                $('.approval-main-content').css('display','none')
                clearInterval(running_timer_interval)
                
                document.querySelectorAll('.pat-status-incoming')[global_index].textContent = 'Pending - ' + $('#inter-depts-select').val().toUpperCase();
            }
        })
    })

    
    $('#imme-approval-btn').on('click' , function(event){
       defaultMyModal.show()
       $('#modal-body-incoming').text('Are you sure you want to approve this?')
       $('#modal-title-incoming').text('Confimation')
       $('#ok-modal-btn-incoming').text('No')
       $('#yes-modal-btn-incoming').css('display', 'block')
    })

    $('#yes-modal-btn-incoming').on('click' , function(event){

        const data = {
            global_single_hpercode : document.querySelectorAll('.hpercode')[global_index].value,
            timer : global_timer,
            approve_details : $('#eraa').val(),
            case_category : $('#approve-classification-select').val(),
            action : 'Approve' // approve or deferr
        }

        console.log(data);

        $.ajax({
            url: '../php_2/approved_pending.php',
            method: "POST",   
            data : data,
            success: function(response){
                clearInterval(running_timer_interval)
                document.querySelectorAll('.pat-status-incoming')[global_index].textContent = 'Approved';
                myModal.hide()
                
                dataTable.clear();
                dataTable.rows.add($(response)).draw();
                
                length_curr_table = $('.tr-incoming').length
                for(let i = 0; i < length_curr_table; i++){
                    toggle_accordion_obj[i] = true
                }
                
                const pencil_elements = document.querySelectorAll('.pencil-btn');
                    pencil_elements.forEach(function(element, index) {
                    element.addEventListener('click', function() {
                        console.log('den')
                        ajax_method(index)
                    });
                });

                const expand_elements = document.querySelectorAll('.accordion-btn');
                    expand_elements.forEach(function(element, index) {
                    element.addEventListener('click', function() {
                        console.log(index)

                        global_breakdown_index = index;
                    });
                });

            }
         })
     })

     $(document).on('click' , '.accordion-btn' , function(event){
        console.log(global_breakdown_index)

        if(toggle_accordion_obj[global_breakdown_index]){
            document.querySelectorAll('.tr-incoming #dt-turnaround')[global_breakdown_index].style.height = "300px"
            document.querySelectorAll('.tr-incoming #dt-turnaround')[global_breakdown_index].style.overflow = "auto"
            toggle_accordion_obj[global_breakdown_index] = false

            // fa-solid fa-plus
            $('.accordion-btn').eq(global_breakdown_index).removeClass('fa-plus')
            $('.accordion-btn').eq(global_breakdown_index).addClass('fa-minus')
        }else{
            document.querySelectorAll('.tr-incoming #dt-turnaround')[global_breakdown_index].style.height = "61px"
            document.querySelectorAll('.tr-incoming #dt-turnaround')[global_breakdown_index].style.overflow = "hidden"
            toggle_accordion_obj[global_breakdown_index] = true

            $('.accordion-btn').eq(global_breakdown_index).addClass('fa-plus')
            $('.accordion-btn').eq(global_breakdown_index).removeClass('fa-minus')
        }
    })

    $('.pre-emp-text').on('click' , function(event){
        var originalString = event.target.textContent;
        // Using substring
        var stringWithoutPlus = originalString.substring(2);

        // Or using slice
        // var stringWithoutPlus = originalString.slice(2);
        $('#eraa').val($('#eraa').val() + " " + stringWithoutPlus  + " ")
    })

    // 
    $('#inter-depts-select').on('change', function(event) {
        // Check if an option is selected
        if ($(this).val() !== '') {
            // Apply CSS changes when an option is selected
            $('#int-dept-btn-forward').css('opacity', '1');
            $('#int-dept-btn-forward').css('pointer-events', 'auto');
        } else {
            // Optionally, you can reset CSS when no option is selected
            $('#int-dept-btn-forward').css('opacity', '0.3');
            $('#int-dept-btn-forward').css('pointer-events', 'none');
        }
    });

    $('#approve-classification-select').on('change', function(event) {
        console.log('asdf')
        if ($(this).val() !== '' && $('#eraa').val().length > 1) {
            $('#imme-approval-btn').css('opacity' , '1')
            $('#imme-approval-btn').css('pointer-events' , 'auto')

            $('#inter-dept-referral-btn').css('opacity' , '1')
            $('#inter-dept-referral-btn').css('pointer-events' , 'auto')
        }else{
            
        }
    });

    $('#eraa').on('input', function(event) {
        if ($('#approve-classification-select').val() !== '' && $('#eraa').val().length > 1) {
            $('#imme-approval-btn').css('opacity' , '1')
            $('#imme-approval-btn').css('pointer-events' , 'auto')

            $('#inter-dept-referral-btn').css('opacity' , '1')
            $('#inter-dept-referral-btn').css('pointer-events' , 'auto')
        }else{
            
        }
    });
 
    $('#cancel-btn').on('click', function(event) {
        defaultMyModal.show()
        $('#modal-title-incoming').text('Confirmation')
        $('#modal-body-incoming').text('Are you sure you want to cancel this referral?')
        clearInterval(running_timer_interval_update)
    });

    $('#final-approve-btn').on('click', function(event) {
        const data = {
            global_single_hpercode : document.querySelectorAll('.hpercode')[global_index].value,
            timer : final_time_total,
            approve_details : $('#eraa').val(), 
            case_category : $('#approve-classification-select').val(),
            action : "Approve"
        }

        console.log(data);

        $.ajax({
            url: '../php/approved_pending.php',
            method: "POST",
            data : data,
            success: function(response){
                // response = JSON.parse(response);    
                // console.log(response)

                const pencil_elements = document.querySelectorAll('.pencil-btn');
                pencil_elements.forEach(function(element, index) {
                    element.addEventListener('click', function() {
                        console.log('den')
                        ajax_method(index)
                    });
                });
            }
         })


    });

})