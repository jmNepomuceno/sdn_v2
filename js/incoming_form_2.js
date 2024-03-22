$(document).ready(function(){
    // array that will hold the data that fetch from the database. 
    let data_arr = {} // structure
    // {hpercode : {time: 0 , func : run_timer},
    // {hpercode : { time: 0 , func : run_timer},
    // {hpercode : { time: 0 , func : run_timer}

    // data table varibles and data table functionalities
    $('#myDataTable').DataTable({
        "bSort": false
    });

    var dataTable = $('#myDataTable').DataTable();
    $('#myDataTable thead th').removeClass('sorting sorting_asc sorting_desc');
    // Disable the search input 
    dataTable.search('').draw(); 

    // Disable the search button
    $('.dataTables_filter').addClass('hidden');

    let modal_filter = ''

    var table = $('#myDataTable').DataTable();
    var totalRecords = table.rows().count();

    //global variables
    let global_single_hpercode = "";
    let global_hpercode_all = document.querySelectorAll('.hpercode')
    let global_stopwatch_all = document.querySelectorAll('.stopwatch')
    let global_pat_status = document.querySelectorAll('.pat-status-incoming')
    let global_breakdown_index = 0;
    let prev_clicked_breakdown_index = 0
    let global_init_referred_lbl = document.querySelectorAll('.referred-time-lbl')
    let global_reception_lbl = document.querySelectorAll('.reception-time-lbl')
    let global_queue_lbl = document.querySelectorAll('.queue-time-lbl')

    let intervalIDs = {};
    let length_curr_table = document.querySelectorAll('.hpercode').length;
    let toggle_accordion_obj = {}
    for(let i = 0; i < length_curr_table; i++){
        toggle_accordion_obj[i] = true
    }

    // ---------------------------------------------------------------------------------------------------------
    let inactivityTimer;
    let userIsActive = true;
    function handleUserActivity() {
        userIsActive = true;
        // Additional code to handle user activity if needed
        // console.log('active')
    }

    function handleUserInactivity() {
        // console.log('inactive')
        userIsActive = false;
        // Additional code to handle user inactivity if needed
        $.ajax({
            url: 'php/fetch_interval.php',
            method: "POST",
            data : {
                from_where : 'incoming'
            },
            success: function(response) {
                global_stopwatch_all = []
                global_hpercode_all = []

                populateTbody(response)

                // console.log(response)
                const pencil_elements = document.querySelectorAll('.pencil-btn');
                    pencil_elements.forEach(function(element, index) {
                    element.addEventListener('click', function() {
                        console.log('den')
                        ajax_method(index)
                    });
                });
            }
        });
    }

    // Attach event listeners
    document.addEventListener('mousemove', handleUserActivity);

    // Set up a timer to check user inactivity periodically
    const inactivityInterval = 20000; // Execute every 5 seconds (adjust as needed)

    function startInactivityTimer() {
        inactivityTimer = setInterval(() => {
            if (!userIsActive) {
                handleUserInactivity();
            }
            userIsActive = false; // Reset userIsActive after each check
            
        }, inactivityInterval);
    }

    function resetInactivityTimer() {
        clearInterval(inactivityTimer);
        startInactivityTimer();
    }

    // Start the inactivity timer when the page loads
    startInactivityTimer();

    //start - open modal 
    const ajax_method = (index, event) => {
        global_single_hpercode = document.querySelectorAll('.hpercode')[index].value
        const data = {
            hpercode: document.querySelectorAll('.hpercode')[index].value
        }
        console.log(data)
        $.ajax({
            url: './php/process_pending.php',
            method: "POST",
            data:data,
            success: function(response){
                response = JSON.parse(response); 
                console.log(response)
                pendingFunction(response)
            }
        })

        console.log(data_arr , global_single_hpercode)
        if(data_arr[global_single_hpercode].status === 'Pending'){
            // console.log('here')
            $('#approval-form').removeClass('hidden')
            // $('#pat-status-form').text('On-Process')
            // console.log($('#pat-status-form').text())
            $.ajax({
                url: './php/fetch_onProcess.php',
                data : {
                    hpercode : global_single_hpercode
                },
                method: "POST",
                success: function(response){     
                    response = JSON.parse(response);           

                    let hpercode_index = 0;
                    for(let i = 0; i < document.querySelectorAll('.hpercode').length; i++){
                        if( document.querySelectorAll('.hpercode')[i].value === global_single_hpercode){
                            hpercode_index = i;
                        }
                    } 

                    console.log(hpercode_index)
                }
            })

            // run_timers[pencil_index_clicked]['func'](pencil_index_clicked , "0" , pat_clicked_code);

            // starting the timer // current_time parameter = 0 it is for whenever there is a patient data processing
            data_arr[global_single_hpercode]['func'](global_single_hpercode , "0") // calling the run_timer function
            // {hpercode : {time: 0 , func : run_timer},
            // {hpercode : { time: 0 , func : run_timer},
            // {hpercode : { time: 0 , func : run_timer}
            
            let index_pat_status = 0
            for(let i = 0; i < global_pat_status.length; i++){
                if(global_hpercode_all[i].value === global_single_hpercode){
                    index_pat_status = i
                    break
                }
            }

            console.log("roflmao: " + index_pat_status)
            // getting current date for reception time
            var currentDate = new Date();

            // Get the current date components
            var currentYear = currentDate.getFullYear();
            var currentMonth = (currentDate.getMonth() + 1).toString().padStart(2, '0'); // Months are zero-indexed
            var currentDay = currentDate.getDate().toString().padStart(2, '0');

            // Get the current time components
            var currentHours = currentDate.getHours().toString().padStart(2, '0');
            var currentMinutes = currentDate.getMinutes().toString().padStart(2, '0');
            var currentSeconds = currentDate.getSeconds().toString().padStart(2, '0');

            // Format the date and time as a string
            var formattedDateTime = `${currentYear}-${currentMonth}-${currentDay} ${currentHours}:${currentMinutes}:${currentSeconds}`;

            //getting the difference between reception time and initial referred time to get the value of queue time
            const date1 = new Date(global_init_referred_lbl[index_pat_status].textContent);
            const date2 = new Date(formattedDateTime);

            
            // Calculate the difference in milliseconds
            const differenceInMilliseconds = Math.abs(date1 - date2);

            // Convert the difference to hours, minutes, and seconds
            let hours_bd = Math.floor(differenceInMilliseconds / 3600000);
            let minutes_bd = Math.floor((differenceInMilliseconds % 3600000) / 60000);
            let seconds_bd = Math.floor((differenceInMilliseconds % 60000) / 1000);

            global_pat_status[index_pat_status].textContent = "On-Process"
            global_reception_lbl[index_pat_status].textContent  = "Reception: " + formattedDateTime
            global_queue_lbl[index_pat_status].textContent  = `Queue Time: ${hours_bd}:${minutes_bd}:${seconds_bd}`
            data_arr[global_single_hpercode].status = "On-Process"

        }
    }
        
    const pencil_elements = document.querySelectorAll('.pencil-btn');
    pencil_elements.forEach(function(element, index) {
        element.addEventListener('click', function() {
            console.log('den')
            ajax_method(index)
        });
    });

    //end - open modal 

    const pendingFunction = (response) =>{
        console.log(response)
        $('#pat-status-form').text(response[0].status)

        if(response[0].status === 'On-Process'){
            $('#pat-status-form').removeClass('text-gray-500')
            $('#pat-status-form').addClass('text-green-500')
            $('#pat-status-form').addClass('text-cyan-500')

            $('#status-bg-div').removeClass('bg-gray-600')
            $('#status-bg-div').addClass('bg-green-500')
            $('#status-bg-div').addClass('bg-cyan-500')

            $('#approval-form').removeClass('hidden')
            

            $('#arrival-form').addClass('hidden')
            $('#approval-details').addClass('hidden')
            $('#cancel-form').addClass('hidden')
            $('#pending-start-div').addClass('hidden')
            $('#followup-form').addClass('hidden')
            $('#discharged-form').addClass('hidden')

        }

        if(response[0].status === 'Approved'){
            $('#temp-forward-form').addClass('hidden')

            $('#pat-status-form').removeClass('text-gray-500')
            $('#pat-status-form').addClass('text-green-500')

            $('#status-bg-div').removeClass('bg-gray-600')
            $('#status-bg-div').addClass('bg-green-500')

            
            $('#approval-form').addClass('hidden')
            $('#pending-start-div').addClass('hidden')
            $('#followup-form').addClass('hidden')
            $('#discharged-form').addClass('hidden')

            $('#arrival-form').removeClass('hidden')
            $('#approval-details').removeClass('hidden')
            $('#cancel-form').removeClass('hidden')

            $('#approval-details-id').text('Approval Details')
            $('#classification-lbl').text(response[0].pat_class)
            $('#admin-action-lbl').text(response[0].approval_details)
        }

        if(response[0].status === 'Deferred'){
            $('#temp-forward-form').addClass('hidden')
    
            $('#pat-status-form').removeClass('text-gray-500')
            $('#pat-status-form').addClass('text-green-500')

            $('#status-bg-div').removeClass('bg-gray-600')
            $('#status-bg-div').addClass('bg-green-500')

            
            $('#approval-form').addClass('hidden')
            $('#pending-start-div').addClass('hidden')
            $('#followup-form').addClass('hidden')
            $('#discharged-form').addClass('hidden')
            $('#arrival-form').addClass('hidden')
            $('#cancel-form').addClass('hidden')

            $('#approval-details').removeClass('hidden')

            $('#approval-details-id').text('Deferral Details')
            $('#classification-lbl').text(response[0].pat_class)
            $('#admin-action-lbl').text(response[0].approval_details)
        }   

        if(response[0].status === 'Arrived'){
            $('#temp-forward-form').addClass('hidden')

            $('#pat-status-form').removeClass('text-gray-500')
            $('#pat-status-form').addClass('text-green-500')

            $('#status-bg-div').removeClass('bg-gray-600')
            $('#status-bg-div').addClass('bg-green-500')

            // $('#approval-form').addClass('hidden')
            $('#pending-start-div').addClass('hidden')
            $('#arrival-form').addClass('hidden')
            $('#cancel-form').addClass('hidden')
            $('#followup-form').addClass('hidden')

            $('#checkup-form').removeClass('hidden')
            $('#arrival-details').removeClass('hidden')
            $('#approval-details').removeClass('hidden')
        }

        if(response[0].status === 'Checked'){
            $('#temp-forward-form').addClass('hidden')

            $('#pat-status-form').removeClass('text-gray-500')
            $('#pat-status-form').addClass('text-green-500')

            $('#status-bg-div').removeClass('bg-gray-600')
            $('#status-bg-div').addClass('bg-green-500')

            // $('#approval-form').addClass('hidden')
            $('#pending-start-div').addClass('hidden')
            $('#arrival-form').addClass('hidden')
            $('#cancel-form').addClass('hidden')
            $('#checkup-form').addClass('hidden')
            $('#discharged-form').addClass('hidden')

            $('#followup-form').removeClass('hidden')
        }

        // if(response[0].status === 'Checked'){
        //     $('#temp-forward-form').addClass('hidden')

        //     $('#pat-status-form').removeClass('text-gray-500')
        //     $('#pat-status-form').addClass('text-green-500')

        //     $('#status-bg-div').removeClass('bg-gray-600')
        //     $('#status-bg-div').addClass('bg-green-500')

        //     // $('#approval-form').addClass('hidden')
        //     $('#pending-start-div').addClass('hidden')
        //     $('#arrival-form').addClass('hidden')
        //     $('#cancel-form').addClass('hidden')
        //     $('#checkup-form').addClass('hidden')
        //     $('#followup-form').addClass('hidden')

        //     $('#discharged-form').removeClass('hidden')
        // }

        console.log(response)
        $('#pendingModal').removeClass('hidden')
        $('#refer-agency').text(" " + response[0].referred_by)
        $('#refer-reason').text(" " + response[0].reason)
        $('#pending-type-lbl').text(response[0].type)
        $('#pending-name').text(" " + response[0].patlast + ", " + response[0].patfirst + " " + response[0].patmiddle)
        $('#pending-bday').text(" " + response[1].patbdate)
        $('#pending-age').text(" " + response[1].pat_age + " years old")
        $('#pending-sex').text(" " + response[1].patsex)
        $('#pending-civil').text(" " + response[1].patcstat)
        $('#pending-religion').text(" " + response[1].relcode)
        $('#pending-address').text(" " + response[1].pat_bldg + " " + response[1].pat_street_block + " " + response[1].pat_barangay + " " + response[1].pat_municipality + " " + response[1].pat_province + " " + response[1].pat_region)

        $('#pending-parent').text(" " + response[0].parent_guardian)
        $('#pending-phic').text(" " + (response[0].phic_member === 'true') ? " Yes" : "No")
        $('#pending-transport').text(" " + response[0].transport)
        $('#pending-admitted').text(" " + response[1].created_at)
        $('#pending-referring-doc').text(" " + response[0].referring_doctor)
        $('#pending-contact-no').text(" 0" + response[1].pat_mobile_no)

        if(response[0].type === 'OB'){
            $('#pending-ob').text(" " + response[1].created_at) // not yet
            $('#pending-last-mens').text(" " + response[0].referring_doctor) // not yet
            $('#pending-gestation').text(" " + response[1].pat_mobile_no) // not yet

            $('.pending-type-ob').removeClass('hidden') // not yet
            // Fetal Heart Tone:This is where you put the data
            // Fundal Height:This is where you put the data

            // Internal ExaminationThis is where you put the data
            // Cervical Dilatation:This is where you put the data
            // Bag of Water:This is where you put the data
            // Presentation:This is where you put the data
            // Others:This is where you put the data
        }else if(response[0].type === 'OPD'){
            $('.pending-type-ob').addClass('hidden') // not yet
        }
        else if(response[0].type === 'ER'){
            $('.pending-type-ob').addClass('hidden') // not yet
        }


        $('#pending-complaint-history').text(" " + response[0].chief_complaint_history)

        $('#pending-pe').text(" " + response[0].chief_complaint_history) // not yet
        $('#pending-bp').text(" " + response[0].bp)
        $('#pending-hr').text(" " + response[0].hr)
        $('#pending-rr').text(" " + response[0].rr)
        $('#pending-temp').text(" " + response[0].temp)
        $('#pending-weight').text(" " + response[0].weight)

        $('#pending-p-pe-find').text(" " + response[0].pertinent_findings)

        $('#pending-diagnosis').text(" " + response[0].diagnosis)
    }

    // populate the body
    const populateTbody = (response) =>{
        // console.log('tbody')    
        response = JSON.parse(response);
        // console.log(response)
        let index = 0;
        let previous = 0;

        // stop the sound notif if the response.length is 0
        // if(Object.entries(response).length === 0){
        //     let audio = document.getElementById("notif-sound")
        //     audio.pause;
        //     audio.currentTime = 0;
        // }

         // get first the last value of the response , which is the new value upon fetching the data from the database.
         if(Object.entries(response).length > 1){
            let keysArray = Object.keys(response);
            let lastKey = keysArray[keysArray.length - 1];

            if (!data_arr.hasOwnProperty(response[lastKey]['hpercode'])) {
                data_arr[response[lastKey]['hpercode']] = {
                    time : null,
                    status : "",
                    time_logout: null,
                    func : run_timer
                }
            }
         }
        
        // need to update the laman of all global variables on every populate of tbody.
        // update the global_hpercode_all based on the current laman of the table
        length_curr_table = response.length
        for(let i = 0; i < length_curr_table; i++){
            toggle_accordion_obj[i] = true
        }
        const incoming_tbody = document.querySelector('#incoming-tbody')
        // console.log(incoming_tbody.hasChildNodes())
        while (incoming_tbody.hasChildNodes()) {
            incoming_tbody.removeChild(incoming_tbody.firstChild);
        }
        // console.log(response.length)
        for(let i = 0; i < response.length; i++){
            
            if(previous == 0){
                index += 1;
            }else{
                if(response[i]['reference_num'] == previous){
                    index += 1;
                }else{
                    index = 1;
                }  
            }

            let type_color;
            if(response[i]['type'] == 'OPD'){
                type_color = 'bg-amber-600';
            }else if(response[i]['type'] == 'OB'){
                type_color = 'bg-green-500';
            }else if(response[i]['type'] == 'ER'){
                type_color = 'bg-sky-700';
            }else if(response[i]['type'] == 'PCR' || response[i]['type'] == 'Toxicology'){
                type_color = 'bg-red-600';
            }

            let fifo_style = ""
            if(i != 0 && response[i]['status'] === 'Pending'){
                fifo_style = 'opacity-50 pointer-events-none'
            }
            const tr = document.createElement('tr')
            tr.className = 'tr-incoming ' + fifo_style

            const td_name = document.createElement('td')
            td_name.textContent = response[i]['reference_num'] + " - " + index
            td_name.className = 'text-sm'
            const td_reference_num = document.createElement('td')
            td_reference_num.textContent = response[i]['patlast'] + ", " + response[i]['patfirst'] + " " + response[i]['patmiddle']

            const td_type = document.createElement('td')
            td_type.textContent = response[i]['type']
            td_type.className = `h-full font-bold text-center ${type_color}`

            const td_referr = document.createElement('td')

            const td_referr_label = document.createElement('label')
            td_referr_label.textContent = "Referred: " + response[i]['referred_by']
            td_referr_label.className = `text-xs ml-1`

            const td_referr_div = document.createElement('div')
            td_referr_div.className = 'flex flex-row justify-start items-center'

            const td_referr_label_1 = document.createElement('label')
            td_referr_label_1.textContent = "Landline: " + response[i]['landline_no']
            td_referr_label_1.className = `text-[7.7pt] ml-1`

            const td_referr_label_2 = document.createElement('label')
            td_referr_label_2.textContent = "Mobile: " + response[i]['mobile_no']
            td_referr_label_2.className = `text-[7.7pt] ml-1`

            const td_time = document.createElement('td')
            td_time.className = "flex flex-col justify-center items-left relative"

            const fa_plus = document.createElement('i')
            fa_plus.className = "accordion-btn absolute bottom-0 right-0 fa-solid fa-plus border-2 border-[#a4b7c1] p-1 text-xs rounded bg-[#d1dbe0] opacity-40 cursor-pointer hover:opacity-100"

            const td_time_div_label_1 = document.createElement('label')
            td_time_div_label_1.textContent = " Referred: " + response[i]['date_time']
            td_time_div_label_1.className = `text-sm w-[95%] border-b border-[#bfbfbf] mt-1`

            let hours_bd = ""
            let minutes_bd = ""
            let seconds_bd = ""

            if(response[i]['reception_time'] !== null){
                //calculate the difference between initial Referred to Reception time
                const date1 = new Date(response[i]['date_time']);
                const date2 = new Date(response[i]['reception_time']);

                
                // Calculate the difference in milliseconds
                const differenceInMilliseconds = Math.abs(date1 - date2);
                
                // Convert the difference to hours, minutes, and seconds
                hours_bd = Math.floor(differenceInMilliseconds / 3600000);
                minutes_bd = Math.floor((differenceInMilliseconds % 3600000) / 60000);
                seconds_bd = Math.floor((differenceInMilliseconds % 60000) / 1000);

                if(seconds_bd < 10){
                    seconds_bd = seconds_bd.toString()
                    seconds_bd = "0" + seconds_bd;
                }
                if(minutes_bd < 10){
                    minutes_bd = minutes_bd.toString()
                    minutes_bd = "0" + minutes_bd;
                }
            }

            // console.log(`Difference: ${hours_bd}:${minutes_bd}:${seconds_bd}`);

            const td_time_div_label_1_1 = document.createElement('label')
            td_time_div_label_1_1.textContent = (response[i]['reception_time'] !== "") ? `Queue Time: ${hours_bd}:${minutes_bd}:${seconds_bd}` : 'Queue Time: 00:00:00'
            td_time_div_label_1_1.className = `text-sm w-[95%] border-b border-[#bfbfbf] mt-1`

            const td_time_div_label_2 = document.createElement('label')
            // td_time_div_label_2.textContent = (response[i]['status'] !== 'Pending') ?  "Processed: " + response[i]['approved_time'] : " Reception: 00:00:00"
            td_time_div_label_2.textContent = (response[i]['status'] !== 'Pending') ?  "Reception: " + response[i]['reception_time'] : " Reception: 00:00:00"
            td_time_div_label_2.className = `text-sm w-[95%] border-b border-[#bfbfbf] mt-1`

            const td_time_div_label_3 = document.createElement('label')
            td_time_div_label_3.textContent = " Deferred: " + "00:00:00"
            td_time_div_label_3.className = `text-sm mt-1`

            const breakdown_div = document.createElement('div')
            breakdown_div.className = "breakdown-div"

            // try for loop
            // for(let i = 0; i < 10; i++){
            //     let breakdown_labels = document.createElement('label')
            //     breakdown_labels.className = "text-sm w-full border-b border-[#bfbfbf]"
            // }

            const processed_lbl_bd = document.createElement('label')
            processed_lbl_bd.className = "text-sm w-full border-b border-[#bfbfbf] mt-1"
            processed_lbl_bd.textContent = "Processed: " + (response[i]['final_progressed_timer'])

            const approval_lbl_bd = document.createElement('label')
            approval_lbl_bd.className = "text-sm w-full border-b border-[#bfbfbf] mt-1"
            approval_lbl_bd.textContent = "Approval: " + response[i]['approved_time']

            const deferral_lbl_bd = document.createElement('label')
            deferral_lbl_bd.className = "text-sm w-full border-b border-[#bfbfbf] mt-1"
            deferral_lbl_bd.textContent = "Deferral: 0000-00-00 00:00:00"

            const cancelled_lbl_bd = document.createElement('label')
            cancelled_lbl_bd.className = "text-sm w-full border-b border-[#bfbfbf] mt-1"
            cancelled_lbl_bd.textContent = "Cancelled: 0000-00-00 00:00:00"

            const arrived_lbl_bd = document.createElement('label')
            arrived_lbl_bd.className = "text-sm w-full border-b border-[#bfbfbf] mt-1"
            arrived_lbl_bd.textContent = "Arrived: 0000-00-00 00:00:00"

            const checked_lbl_bd = document.createElement('label')
            checked_lbl_bd.className = "text-sm w-full border-b border-[#bfbfbf] mt-1"
            checked_lbl_bd.textContent = "Checked: 0000-00-00 00:00:00"

            const admitted_lbl_bd = document.createElement('label')
            admitted_lbl_bd.className = "text-sm w-full border-b border-[#bfbfbf] mt-1"
            admitted_lbl_bd.textContent = "Admitted: 0000-00-00 00:00:00"

            const discharged_lbl_bd = document.createElement('label')
            discharged_lbl_bd.className = "text-sm w-full border-b border-[#bfbfbf] mt-1"
            discharged_lbl_bd.textContent = "Discharged: 0000-00-00 00:00:00"

            const follow_lbl_bd = document.createElement('label')
            follow_lbl_bd.className = "text-sm w-full border-b border-[#bfbfbf] mt-1"
            follow_lbl_bd.textContent = "Follow Up: 0000-00-00 00:00:00"

            const referred_lbl_bd = document.createElement('label')
            referred_lbl_bd.className = "text-xs w-full border-b border-[#bfbfbf] mt-1"
            referred_lbl_bd.textContent = "Referred Back: 0000-00-00 00:00:00"

            if(response[i]['final_progressed_timer'] !== null){
                // Input time duration in "hh:mm:ss" format
                let timeString = response[i]['final_progressed_timer'];

                // Split the time string into hours, minutes, and seconds
                let [hours, minutes, seconds] = timeString.split(':').map(Number);

                // Calculate the total duration in milliseconds
                let totalMilliseconds = (hours * 60 * 60 + minutes * 60 + seconds) * 1000;  

                // console.log(totalMilliseconds); // Output: 99000
            }

            const td_processing = document.createElement('td')
            // td_processing.textContent = "Processing: " // from 1 to 4

            const td_processing_div = document.createElement('div')
            td_processing_div.className = 'flex flex-row justify-around items-center'
            td_processing_div.textContent = "Processing: "
            const td_processing_div_2 = document.createElement('div')

            // console.log(data_arr)


            if (data_arr[response[i]['hpercode']].status === 'On-Process') {
                // if it shows the patient who has currently processing, we are going to delete first the timer, then run again the timer 
                // upon sorting, whenever it shows or disappears, we are going to delete and re run the timer, to show the current timer
                // console.log('kyla')
                // clearInterval(intervalIDs['interval_' + response[i]['hpercode']]);
                // delete intervalIDs['interval_' + response[i]['hpercode']];

                // // continue
                // data_arr[global_single_hpercode]['func'](response[i]['hpercode'] , data_arr[response[i]['hpercode']].time) // calling the run_timer function
            }
            else if (data_arr[response[i]['hpercode']].status === 'Approved' || data_arr[response[i]['hpercode']].status === 'Arrived'){
                td_processing_div_2.textContent = response[i]['final_progressed_timer']
            }
            else if (data_arr[response[i]['hpercode']].status === 'Deferred' || data_arr[response[i]['hpercode']].status === 'Cancelled' || data_arr[response[i]['hpercode']].status === 'Checked'){
                td_processing_div_2.textContent = response[i]['final_progressed_timer']
            }
            else{
                td_processing_div_2.textContent = (data_arr[response[i]['hpercode']].time) ? data_arr[response[i]['hpercode']].time : "00:00:00"
            }
            
            // need to update the laman of all global variables on every populate of tbody.
            // update the global_hpercode_all based on the current laman of the table
            global_stopwatch_all.push(td_processing_div_2)

            var timeString = td_processing_div_2.textContent; // Example time string in "hh:mm:ss" format
            var match = timeString.match(/(\d+):(\d+):(\d+)/);

            if (match) {
                var hours = parseInt(match[1], 10);
                var minutes = parseInt(match[2], 10);
                var seconds = parseInt(match[3], 10);

                var totalMinutes = hours * 60 + minutes + seconds / 60;
                // console.log(totalMinutes); // Output: 3.466666666666667
                if(totalMinutes > 2.00){ // to be change
                    td_processing_div_2.style.color = 'red'
                }else{
                    td_processing_div_2.style.color = 'green'
                }
            }

            td_processing_div_2.style.color = (td_processing_div_2.textContent === "00:00:00") ? "black" : td_processing_div_2.style.color

            // td_processing_div_2.id = 'stopwatch'
            td_processing_div_2.className = 'stopwatch'

            const td_status = document.createElement('td')
            td_status.className = `font-bold text-center bg-gray-500`

            const td_status_div = document.createElement('div')
            td_status_div.className = `pat-status-incoming flex flex-row justify-around items-center`
            td_status_div.textContent = response[i]['status']

            const td_status_div_i = document.createElement('i')
            td_status_div_i.className = `pencil-btn fa-solid fa-pencil cursor-pointer hover:text-white`

            const td_status_div_input = document.createElement('input')
            td_status_div_input.className = `hpercode`
            td_status_div_input.type = "hidden";
            td_status_div_input.name = "hpercode";
            td_status_div_input.value = response[i]['hpercode'];  
            
            // update the global_hpercode_all based on the current laman of the table
            global_hpercode_all.push(td_status_div_input)

            td_status_div.appendChild(td_status_div_i)
            td_status_div.appendChild(td_status_div_input)
            td_status.appendChild(td_status_div)
            // end

            // console.log(data_arr)
            
            td_time.appendChild(fa_plus)
            td_time.appendChild(td_time_div_label_1)
            td_time.appendChild(td_time_div_label_1_1)
            td_time.appendChild(td_time_div_label_2)
            if (data_arr[response[i]['hpercode']].status === 'Deferred'){
                console.log('asdf')
                td_time.appendChild(td_time_div_label_3)
            }
            breakdown_div.appendChild(processed_lbl_bd)
            breakdown_div.appendChild(approval_lbl_bd)
            breakdown_div.appendChild(deferral_lbl_bd)
            breakdown_div.appendChild(cancelled_lbl_bd)
            breakdown_div.appendChild(arrived_lbl_bd)
            breakdown_div.appendChild(checked_lbl_bd)   
            breakdown_div.appendChild(admitted_lbl_bd)
            breakdown_div.appendChild(discharged_lbl_bd)
            breakdown_div.appendChild(follow_lbl_bd)
            breakdown_div.appendChild(referred_lbl_bd)

            td_time.appendChild(breakdown_div)
            
            
            td_referr_div.appendChild(td_referr_label_1)
            td_referr_div.appendChild(td_referr_label_2)

            td_referr.appendChild(td_referr_label)
            td_referr.appendChild(td_referr_div)

            td_processing_div.appendChild(td_processing_div_2)

            td_processing.appendChild(td_processing_div)     
            
            tr.appendChild(td_name)
            tr.appendChild(td_reference_num)
            tr.appendChild(td_type)
            tr.appendChild(td_referr)
            tr.appendChild(td_time)
            tr.appendChild(td_processing)
            tr.appendChild(td_status)

            document.querySelector('#incoming-tbody').appendChild(tr)

            previous = response[i]['reference_num'];

            
            // if(response[i].status === 'On-Process'){
            //     hpercode_with_timer_running.push({ 'hpercode' : response[i].hpercode})
            // }
        }

        // console.log(document.querySelectorAll('.accordion-btn').length)
    }

    // MAIN BUTTON FUNCTIONALITIES - START - APPROVED - CLOSED - N
    $('#pending-start-btn').on('click' , function(event){
        $('#approval-form').removeClass('hidden')
        $('#pat-status-form').text('On-Process')
        $.ajax({
            url: './php/fetch_onProcess.php',
            method: "POST",
            success: function(response){     
                response = JSON.parse(response);           

                let hpercode_index = 0;
                for(let i = 0; i < document.querySelectorAll('.hpercode').length; i++){
                    if( document.querySelectorAll('.hpercode')[i].value === global_single_hpercode){
                        hpercode_index = i;
                    }
                } 

                console.log(hpercode_index)
            }
        })

        // run_timers[pencil_index_clicked]['func'](pencil_index_clicked , "0" , pat_clicked_code);

        // starting the timer // current_time parameter = 0 it is for whenever there is a patient data processing
        data_arr[global_single_hpercode]['func'](global_single_hpercode , "0") // calling the run_timer function
        // {hpercode : {time: 0 , func : run_timer},
        // {hpercode : { time: 0 , func : run_timer},
        // {hpercode : { time: 0 , func : run_timer}
        
        let index_pat_status = 0
        for(let i = 0; i < global_pat_status.length; i++){
            if(global_hpercode_all[i].value === global_single_hpercode){
                index_pat_status = i
                break
            }
        }

    

        console.log("roflmao: " + index_pat_status)
        global_pat_status[index_pat_status].textContent = "On-Process"
        data_arr[global_single_hpercode].status = "On-Process"

    })
    
    $('#pending-approved-btn').on('click' , function(event){
        $('#modal-title-incoming').text('Warning')
        $('#modal-icon').addClass('fa-triangle-exclamation')
        $('#modal-icon').removeClass('fa-circle-check')

        if($('#approved-action-select').val() === 'Approve'){
            $('#modal-body-incoming').text('Approval Confirmation')
        }else{
            $('#modal-body-incoming').text('Deferral Confirmation')
        }

        modal_filter = 'approval_confirmation'
        $('#yes-modal-btn-incoming').removeClass('hidden')
        $('#ok-modal-btn-incoming').text('No')

        // $('#myModal-incoming').modal('show');
    })

    $('#close-pending-modal').on('click' , function(event){
        $('#pendingModal').addClass('hidden')
    })

    // modal showing upon clicking the approval
    $('#yes-modal-btn-incoming').on('click' , function(event){
        // clear the timer  
        if(modal_filter === 'approval_confirmation'){
            console.log(intervalIDs)
            // console.log(global_single_hpercode)
            if (intervalIDs.hasOwnProperty(`interval_${global_single_hpercode}`)) {
                // console.log('here')

                clearInterval(intervalIDs['interval_' + global_single_hpercode]);
                delete intervalIDs['interval_' + global_single_hpercode];
                // document.querySelectorAll('.pat-status-incoming')[pencil_index_clicked_temp].textContent = "Approved"
            }
            console.log(intervalIDs)
            // updating the status of that patient from the data_arr and in the database
            if($('#approved-action-select').val() === 'Approve'){
                data_arr[global_single_hpercode].status = "Approved"
            }else{
                data_arr[global_single_hpercode].status = "Deferred"
            }



            const data = {
                global_single_hpercode : global_single_hpercode,
                timer : data_arr[global_single_hpercode].time,
                approve_details : $('#eraa').val(),
                case_category : $('#approve-classification-select').val(),
                action : $('#approved-action-select').val()
            }

            console.log(data);

            $.ajax({
                url: './php/approved_pending.php',
                method: "POST",
                data : data,
                success: function(response){
                    // response = JSON.parse(response);    
                    // console.log(response)

                    // $('#pendingModal').addClass('hidden')
                    global_stopwatch_all = []
                    global_hpercode_all = []

                    populateTbody(response)

                    console.log(document.querySelectorAll('.pencil-btn').length)
                    const pencil_elements = document.querySelectorAll('.pencil-btn');
                    pencil_elements.forEach(function(element, index) {
                        element.addEventListener('click', function() {
                            console.log('den')
                            ajax_method(index)
                        });
                    });
                }
             })


            
        }

        else if(modal_filter === 'arrival_confirmation'){
            const data = {
                global_single_hpercode : global_single_hpercode,
                arrival_details : $('#arrival-text-area').val(),
            }

            // updating the status of that patient from the data_arr and in the database
            data_arr[global_single_hpercode].status = "Arrived"

            $.ajax({
                url: './php/approved_to_arrival.php',
                method: "POST",
                data : data,
                success: function(response){
                    $('#pendingModal').addClass('hidden')
                    global_stopwatch_all = []
                    global_hpercode_all = []
                    populateTbody(response)
                }
             })
        }

        else if(modal_filter === 'cancellation_confirmation'){
            const data = {
                global_single_hpercode : global_single_hpercode,
                cancel_details : $('#cancellation-textarea').val(),
            }

            // updating the status of that patient from the data_arr and in the database
            data_arr[global_single_hpercode].status = "Cancelled"

            $.ajax({
                url: './php/approved_to_cancellation.php',
                method: "POST",
                data : data,
                success: function(response){
                    $('#pendingModal').addClass('hidden')
                    global_stopwatch_all = []
                    global_hpercode_all = []
                    populateTbody(response)
                }
             })
        }
        else if(modal_filter === 'checked_confirmation'){
            const data = {
                global_single_hpercode : global_single_hpercode,
                checkup_classification_select : $('#checkup-classification-select').val(),
                checkup_textarea : $('#checkup-textarea').val(),
            }

            // updating the status of that patient from the data_arr and in the database
            data_arr[global_single_hpercode].status = "Checked"

            $.ajax({
                url: './php/arrived_to_checked.php',
                method: "POST",
                data : data,
                success: function(response){
                    $('#pendingModal').addClass('hidden')
                    global_stopwatch_all = []
                    global_hpercode_all = []
                    populateTbody(response)
                }
             })
        }
    })

    // incase of forwarding the patient
    $('#forward-continue-btn').on('click' , function(event){
        $('#temp-forward-form').addClass('hidden')
        $('#pat-forward-form').removeClass('hidden')
    })

    $('#forward-cancel-btn').on('click' , function(event){
        $('#temp-forward-form').removeClass('hidden')
        $('#pat-forward-form').addClass('hidden')
    })

    $('.pre-emp-text').on('click' , function(event){
        var originalString = event.target.textContent;
        // Using substring
        var stringWithoutPlus = originalString.substring(2);

        // Or using slice
        // var stringWithoutPlus = originalString.slice(2);
        $('#eraa').val($('#eraa').val() + " " + stringWithoutPlus  + " ")
    })

    $('#approved-action-select').change(function(){
        let selectedValue = $(this).val();
        // console.log($('#eraa').val())
        // Check if a value is selected
        if (selectedValue != "") {
            // alert("Selected value: " + selectedValue);
            $('#pending-approved-btn').removeClass('opacity-30 pointer-events-none')
        } else {
            $('#pending-start-btn').addClass('opacity-50 pointer-events-none')
        }
    })

    $('#arrival-submit').on('click' , function(){
        // console.log($('#arrival-text-area').val())

        $('#modal-title-incoming').text('Warning')
        $('#modal-icon').addClass('fa-triangle-exclamation')
        $('#modal-icon').removeClass('fa-circle-check')
        $('#modal-body-incoming').text('Arrival Confirmation')
        $('#yes-modal-btn-incoming').removeClass('hidden')
        $('#ok-modal-btn-incoming').text('No')

        modal_filter = 'arrival_confirmation'

        // $('#myModal-incoming').modal('show');
    })

    $('#cancel-submit').on('click' , function(event){
        $('#modal-title-incoming').text('Warning')
        $('#modal-icon').addClass('fa-triangle-exclamation')
        $('#modal-icon').removeClass('fa-circle-check')
        $('#modal-body-incoming').text('Cancellation Confirmation')
        $('#yes-modal-btn-incoming').removeClass('hidden')
        $('#ok-modal-btn-incoming').text('No')

        modal_filter = 'cancellation_confirmation'

        // $('#myModal-incoming').modal('show');
    })

    $('#check-submit-btn').on('click' , function(event){
        $('#modal-title-incoming').text('Warning')
        $('#modal-icon').addClass('fa-triangle-exclamation')
        $('#modal-icon').removeClass('fa-circle-check')
        $('#modal-body-incoming').text('Checked Confirmation')
        $('#yes-modal-btn-incoming').removeClass('hidden')
        $('#ok-modal-btn-incoming').text('No')

        modal_filter = 'checked_confirmation'

        // $('#myModal-incoming').modal('show');
    })

    // END MAIN BUTTON FUNCTIONALITIES - START - APPROVED - CLOSED - N

    function parseTimeToMilliseconds(timeString) {
        const [hours, minutes, seconds] = timeString.split(":");
        // console.log(hours, minutes, seconds)
        const totalMilliseconds = ((parseInt(hours, 10) * 60 + parseInt(minutes, 10)) * 60 + parseInt(seconds, 10)) * 1000;
        return totalMilliseconds;
        //5000
    }

    const run_timer = (global_single_hpercode, current_time) =>{
        // console.log(current_time)
        let startTime = 0; 
        let elapsedTime = 0;
        function formatTime(milliseconds) {
            const date = new Date(milliseconds);
            return date.toISOString().substr(11, 8);
        }

        if(current_time !== "0"){ // if it is from after the reload
            startTime =  parseTimeToMilliseconds(current_time);
        }else{ // for a new patient processing process :D 
            startTime = new Date().getTime() - elapsedTime;
        }

      
        // const uniqueIdentifier = `interval_${index}`;

        
        let uniqueIdentifier = `interval_${global_single_hpercode}`;
        let data;
        // console.log(data_arr)
        intervalIDs[uniqueIdentifier] = setInterval(() => {
            // console.log('here')
            if(current_time === "0"){
                // console.log('initial')
                const currentTime = new Date().getTime();
                elapsedTime = currentTime - startTime;

                // find the current index of the clicked hpercode based on the current data in the table
                let index_hpercode;
                for(let i = 0; i < length_curr_table; i++){
                    if(global_single_hpercode === global_hpercode_all[i].value){
                        index_hpercode = i;
                    }
                }

                if(index_hpercode !== undefined){
                    // printing the formatTime
                    global_stopwatch_all[index_hpercode].textContent = formatTime(elapsedTime)
                    global_pat_status[index_hpercode].textContent = "On-Process"
                    $('#pat-status-form').text('On-Process')

                    $('#pat-status-form').removeClass('text-gray-500')
                    $('#pat-status-form').addClass('text-green-500')
                    
                    $('#status-bg-div').addClass('bg-gray-600')
                    $('#status-bg-div').removeClass('bg-cyan-500')

                    // changing the color of the text based on the 'matagal ma process'
                    if(elapsedTime >= 15000){
                        global_stopwatch_all[index_hpercode].style.color = "red"
                    }else{
                        global_stopwatch_all[index_hpercode].style.color = 'green'
                    }
                }

                data_arr[global_single_hpercode].time = formatTime(elapsedTime)

                data = {
                    // timer_running : true,
                    global_single_hpercode : global_single_hpercode,
                    elapsedTime : formatTime(elapsedTime),
                    table_index : index_hpercode,
                    // approved_bool : approved_clicked_bool,
                    // approved_clicked_hpercode : approved_clicked_hpercode, 
                    // secs_add : secs_add
                }

                // console.log(data_arr)
            }else{
                // console.log('refreshed')
                startTime += 1000

                // find the current index of the clicked hpercode based on the current data in the table
                let index_hpercode;
                for(let i = 0; i < length_curr_table; i++){
                    // console.log(global_hpercode_all[i].value , global_single_hpercode)
                    if(global_single_hpercode === global_hpercode_all[i].value){
                        index_hpercode = i;
                    }
                }
                
                // condition mo dapat pag wala value si index_hpercode, wala dapat mangyayare or di dapat mag r run yung number 486
                // printing the formatTime
                if(index_hpercode !== undefined){
                    // may laman
                    global_stopwatch_all[index_hpercode].textContent = formatTime(startTime)
                    global_pat_status[index_hpercode].textContent = "On-Process"
                    $('#pat-status-form').text('On-Process')
                    $('#pat-status-form').removeClass('text-gray-500')
                    $('#pat-status-form').addClass('text-green-500')

                    // changing the color of the text based on the 'matagal ma process'
                    if(startTime >= 15000){
                        global_stopwatch_all[index_hpercode].style.color = "red"
                    }else{
                        global_stopwatch_all[index_hpercode].style.color = 'green'
                    }
                }

                data_arr[global_single_hpercode].time = formatTime(startTime)

                data = {
                    // timer_running : true,
                    global_single_hpercode : global_single_hpercode,
                    elapsedTime : formatTime(startTime),
                    table_index : index_hpercode,
                    // approved_bool : approved_clicked_bool,
                    // approved_clicked_hpercode : approved_clicked_hpercode, 
                    // secs_add : secs_add
                }   

                // console.log(data_arr)

            }
            
            // console.log(data)
            $.ajax({
                url: './php/process_timer_2.php',
                method: "POST",
                data:data,
                success: function(response){
                    // console.log(response)
                    // response = JSON.parse(response);    
                    // console.log(response)            
                }
            })
        }, 1000)
    }

    // console.log(data_arr)
    // initialize the structure of the table_arr based on the data that have been fetched from the database // fetch the status ?
    $.ajax({
        url: './php/fetch_status.php',
        method: "POST",
        success: function(response){
            response = JSON.parse(response);  
            console.log(response)
            for(let i = 0; i < response.length; i++){
                try {
                    // Your code that may cause an error
                    data_arr[response[i].hpercode] = { time: response[i].response_time, status:response[i].status, time_logout: response[i].progress_timer,  func: run_timer };
                } catch (error) {
                    console.error("Error:", error);
                
                    // You can also add more specific handling based on the type of error
                    if (error instanceof TypeError) {
                        // Handle TypeError
                        console.error("This is a TypeError. Check the type and value of global_hpercode_all[i].value");
                    } else {
                        // Handle other types of errors
                        console.error("An unexpected error occurred. Check the console for details.");
                    }
                }   
            }

            // check if the length of session process_timer is > 1, this is for whenever the there is a timer running and the user refresh the page
            let after_reload = []
            // console.log($('#timer-running-input').val(), $('#post-value-reload-input').val())
            if($('#timer-running-input').val() === '1'){ // if global_process_timer_running === 1
                // console.log("refresh")
                if($('#post-value-reload-history-input').val() === 'history_log'){
                    $.ajax({
                        url: './php/save_process_time.php',
                        method: "POST",
                        data : {what: 'continue'},
                        success: function(response){
                            response = JSON.parse(response);  
                            // console.log(response)
    
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
                            
                                console.log(data_arr)
                                console.log(response)
                                for(let i = 0; i <response.length; i++){
                                    // data_arr[response[i].hpercode]['func'](response[i].hpercode , final_time)
                                    data_arr[response[i].hpercode]['func'](response[i].hpercode , final_time)
                                }
                            }
    
                        }
                    })
                }else{
                    $.ajax({
                        url: './php/fetch_onProcess.php',
                        data : {
                            hpercode : "none"
                        },
                        method: "POST",
                        success: function(response){               
                            response = JSON.parse(response);
                            after_reload = response
                            console.log(after_reload)
    
                            for(let i = 0; i < response.length; i++){
                                if(data_arr[after_reload[i].global_single_hpercode].status === 'Pending' || data_arr[after_reload[i].global_single_hpercode].status === 'On-Process'){
                                    global_single_hpercode = after_reload[i].global_single_hpercode
                                    data_arr[after_reload[i].global_single_hpercode]['func'](global_single_hpercode, after_reload[i].elapsedTime)    
                                }
                            }
                        }
                    })
                }
                
            }

            // after reload, the exisiting processing timer are still running after logout and logging in.
            if($('#post-value-reload-input').val() === 'true' && $('#timer-running-input').val() !== '1' ){
                // console.log("after logout")
                // console.log($('#post-value-reload-input').val())
                $.ajax({
                    url: './php/save_process_time.php',
                    method: "POST",
                    data : {what: 'continue'},
                    success: function(response){
                        response = JSON.parse(response);  
                        // console.log(response)

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
                        
                            console.log(data_arr)
                            console.log(response)
                            for(let i = 0; i <response.length; i++){
                                // data_arr[response[i].hpercode]['func'](response[i].hpercode , final_time)
                                data_arr[response[i].hpercode]['func'](response[i].hpercode , final_time)
                            }
                        }

                    }
                })
            }
        }
    })

    $(document).on('keypress', function(event) {
        if (event.which === 13 || event.keyCode === 13) {
            $('#incoming-search-btn').trigger('click');
        }
    });

    // SEARCHING FUNCTIONALITIES
    $('#incoming-search-btn').on('click' , function(event){        
        $('#incoming-clear-search-btn').removeClass('opacity-30 pointer-events-none')
        console.log(data_arr)
        let data = {
            get_all : false,
            ref_no : $('#incoming-referral-no-search').val(),
            last_name : $('#incoming-last-name-search').val(),
            first_name : $('#incoming-first-name-search').val(),
            middle_name : $('#incoming-middle-name-search').val(),
            case_type : $('#incoming-type-select').val(),
            agency : $('#incoming-agency-select').val(),
            status : $('#incoming-status-select').val()
        }


        // console.log(data)
            $.ajax({
                url: './php/incoming_search.php',
                method: "POST", 
                data:data,
                success: function(response){
                    // console.log(response)
                    global_stopwatch_all = []
                    global_hpercode_all = []

                    clearInterval(inactivityTimer);

                    populateTbody(response)

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
                            global_breakdown_index = index;
                        });
                    });
                }
            })

        // // console.log(data)
        // if(data.ref_no === "" && data.last_name === "" && data.first_name === "" && data.middle_name === "" && data.case_type === "" && data.agency === "" && data.status === "default" ){
        //     $('#modal-title-incoming').text('Warning')
        //     $('#modal-icon').addClass('fa-triangle-exclamation')
        //     $('#modal-icon').removeClass('fa-circle-check')
        //     $('#modal-body-incoming').text('Fill at least one bar.')
        //     $('#ok-modal-btn-incoming').text('Ok')

        //     $('#myModal-incoming').modal('show');
            
        // }else{
            
        // }
        
        
    })

    $('#incoming-clear-search-btn').on('click' , function(event){
        $.ajax({
            url: './php/fetch_interval.php',
            method: "POST",
            data:{
                from_where : 'incoming'
            },
            success: function(response){
                global_stopwatch_all = []
                global_hpercode_all = []
                populateTbody(response)
    
                response = JSON.parse(response);    
                console.log(response)

                startInactivityTimer()

                $('#incoming-referral-no-search').val("")
                $('#incoming-last-name-search').val("")
                $('#incoming-first-name-search').val("")
                $('#incoming-middle-name-search').val("")
                $('#incoming-type-select').val("")
                $('#incoming-agency-select').val("")
                $('#incoming-status-select').val('Pending')

                $('#incoming-clear-search-btn').addClass('opacity-30 pointer-events-none')

                const pencil_elements = document.querySelectorAll('.pencil-btn');
                    pencil_elements.forEach(function(element, index) {
                    element.addEventListener('click', function() {
                        console.log('den')
                        ajax_method(index)
                    });
                    });

            }
        })
    })

    // MA DRUP PRESCRIPTION
    // DG reference number doctors order // ORT 

    $(window).on('load' , function(event){
        event.preventDefault();
        clearInterval(inactivityTimer);
    })

    $('#sdn-title-h1').on('click' , function(event){
        event.preventDefault();
        clearInterval(inactivityTimer);
    })

    $('#outgoing-sub-div-id').on('click' , function(event){
        event.preventDefault();
        clearInterval(inactivityTimer);
    })

    $('#patient-reg-form-sub-side-bar').on('click' , function(event){
        event.preventDefault();
        clearInterval(inactivityTimer);
    })

    
    $(document).on('click' , '.accordion-btn' , function(event){
        console.log(toggle_accordion_obj)
        if(toggle_accordion_obj[global_breakdown_index]){
            document.querySelectorAll('.tr-incoming')[global_breakdown_index].style.height = "300px"
            document.querySelectorAll('.breakdown-div')[global_breakdown_index].style.display = 'block'
            toggle_accordion_obj[global_breakdown_index] = false

            // fa-solid fa-plus
            $('.accordion-btn').eq(global_breakdown_index).removeClass('fa-plus')
            $('.accordion-btn').eq(global_breakdown_index).addClass('fa-minus')
        }else{
            document.querySelectorAll('.tr-incoming')[global_breakdown_index].style.height = "61px"
            document.querySelectorAll('.breakdown-div')[global_breakdown_index].style.display = 'none'
            toggle_accordion_obj[global_breakdown_index] = true

            $('.accordion-btn').eq(global_breakdown_index).addClass('fa-plus')
            $('.accordion-btn').eq(global_breakdown_index).removeClass('fa-minus')
        }
    })
})