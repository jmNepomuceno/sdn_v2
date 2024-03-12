$(document).ready(function(){
    var search_clicked = false;
    // console.log($('#current-page-input').val())

    // data table functionalities
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

    let pencil_index_clicked = 0;
    let pencil_index_clicked_temp = 0;
    let pat_clicked_code = 0
    let pat_clicked_code_temp = 0;
    let approved_clicked_bool = 'false';
    let approved_clicked_hpercode = 0;

    var search_clicked = false;
    let processing_time;
    let processing_time_running = false;
    let incoming_time;
    // Set the time threshold for considering the user as idle (e.g., 5 minutes)
    // var idleTime = 5*60*1000; // 1 minutes in milliseconds
    var idleTime = 3000; // 3sec in milliseconds

    // Initialize a timer variable
    var idleTimer;
    let global_realtime_update; 
    let global_timer_continue = [];
    let global_hpercode; // current clicked value
    let global_hpercode_all = document.querySelectorAll('.hpercode')
    let global_hpercode_arr = [];

    const stopwatchDisplay = document.querySelectorAll('.stopwatch');

    let hpercode_with_timer_running = []
    let table_list = [];
    let dom_table_list = []
    let status_table_list = []
    let secs_add = 0

    let status_search = ""

    let intervalIDs = {};

    table_list = jsonData
    status_table_list = document.querySelectorAll('.pat-status-incoming')

    dom_table_list = stopwatchDisplay
    // Add event listeners for user activity
    document.addEventListener("mousemove", resetIdleTimer);
    document.addEventListener("keydown", resetIdleTimer);

    

    // $(window).on('beforeunload', function() {
    //     return 'Are you sure you want to leave this page?';
    // });
    
    // check if the logout date has a value for all the data
    let logout_date_has_value = false;

    for(let i = 0; i < logout_data.length; i++){
        console.log(logout_data[i].logout_date)
        if(logout_data[i].logout_date !== undefined){
            logout_date_has_value = true
            break;
        }
    }

    if(logout_date_has_value){
        console.log('here')
        var dateString1 = login_data
        var dateString2 = logout_data[0].logout_date
    
        // Create Date objects
        console.log(dateString1)
        var date1 = new Date(dateString1);
        var date2 = new Date(dateString2);
    
        // Calculate the difference in milliseconds
        var differenceInMilliseconds = date1 - date2; 
    
        // Convert milliseconds to seconds
        secs_add = differenceInMilliseconds
    
        console.log(secs_add)
    }
    

    for(let i = 0; i < document.querySelectorAll('.pat-status-incoming').length; i++){
        
        if(document.querySelectorAll('.pat-status-incoming')[i].textContent === ' On-Process '){
            hpercode_with_timer_running.push({ 'hpercode' : document.querySelectorAll('.hpercode')[i].value})
        }
    }

    if($('#current-page-input').val() !== "incoming_page"){
        console.log($('#current-page-input').val())
        processing_time_running = true
    }

    // Function to reset the idle timer
    function resetIdleTimer() {
        // console.log('reset idle timer')
        // console.log(processing_time_running)
        
        if(processing_time_running === false){
            // console.log('pota')
            clearTimeout(incoming_time)
            clearTimeout(idleTimer); // Clear the previous timer
            idleTimer = setTimeout(userIsIdle, idleTime); // Set a new timer
        }
    }

    // Function to be called when the user is considered idle
    function userIsIdle() {
        // console.log("User is idle. You can perform idle actions here.");

        if(processing_time_running === false){
            fetchMySQLData_incoming(); 
        }
    }

    // console.log($('#timer-running-input').val() === '1'  , $('#post-value-reload-input').val())

    // ETO TRY MO BUKAS, NAG DADALAWANG RUN KASI YUNG TIMER, KAPAG MAY VALUE AFTER REFRESH SAKA PAG MAY VALUE AFTER LOGGING OUT. gl hf bukas :))))))

    if($('#post-value-reload-input').val() === 'true' && $('#timer-running-input').val() !== '1' ){
        let prev_running_timer_before_logout = []
        console.log("reload")

        for(let i = 0; i < document.querySelectorAll('.pat-status-incoming').length; i++){
            if(document.querySelectorAll('.pat-status-incoming')[i].textContent === " On-Process "){
                prev_running_timer_before_logout.push(i)
            }
        }

        $.ajax({
            url: './php/save_process_time.php',
            method: "POST",
            data : {what: 'continue'},
            success: function(response){
                response = JSON.parse(response);  
                // console.log(response)

                for(let i = 0; i < prev_running_timer_before_logout.length; i++){
                    try_arr[i]['func'](prev_running_timer_before_logout[i] , response[i].progress_timer , response[i].hpercode);
                }

            }
        })
    }

    if($('#timer-running-input').val() === '1' && $('#post-value-reload-input').val() !== '1'){   
        // console.log("refresh")  
        processing_time_running = true;
        const data = {
            timer_running : true,
        }
        // console.log(pencil_index_clicked)
        // document.querySelectorAll('.pat-status-incoming')[pencil_index_clicked].textContent = "Approved" 
        
        $.ajax({
            url: './php/continue_process_timer.php',
            method: "POST",
            data:data,
            success: function(response){
                // console.log(response)

                response = JSON.parse(response);
                console.log(response)

                global_realtime_update = response
                

                // console.log(document.querySelectorAll('.hpercode')[index].value)

                const stopwatchDisplay = document.querySelectorAll('.stopwatch');
                // for(let i = 0; i < response.length; i++){
                //     stopwatchDisplay[response[i]['table']].textContent = formatTime(elapsedTime) 
                // }

                let continue_timer_arr = []
                for(let i = 0; i < document.querySelectorAll('.hpercode').length; i++){
                    for(let j = 0; j < response.length; j++){
                        if(response[j]['pat_clicked_code'] === document.querySelectorAll('.hpercode')[i].value){
                            continue_timer_arr.push(i)
                        }
                    }
                }

                global_timer_continue = continue_timer_arr

                // console.log('index: ' + continue_timer_arr)
                // print the value then fixed the other bug
                // console.log('yawa')
                // console.log(response)
                for(let i = 0; i < continue_timer_arr.length; i++){
                    stopwatchDisplay[continue_timer_arr[i]].textContent = response[i].elapsedTime
                }

            }
        })
    }
    

    const ajax_method = (index, event) => {
        // console.log('okay')
        // console.log(document.querySelectorAll('.hpercode')[index].value)

        // if(processing_time_running === true){
        //     index -= 1
        // }

        pencil_index_clicked_temp = index
        // pat_clicked_code = document.querySelectorAll('.hpercode')[index].value
        pat_clicked_code_temp = document.querySelectorAll('.hpercode')[index].value

        // console.log(pat_clicked_code)

        global_hpercode = document.querySelectorAll('.hpercode')[index].value

        // for(let i = 0; i < document.querySelectorAll('.hpercode').length; i++){
        //     console.log(document.querySelectorAll('.hpercode')[i].value)
        // }

        const data = {
            status : 'Approved',
            hpercode: document.querySelectorAll('.hpercode')[index].value
        }

        // console.log(data)
        $.ajax({
            url: './php/process_pending.php',
            method: "POST",
            data:data,
            success: function(response){
                // console.log(response)
                response = JSON.parse(response);
                // console.log(response)   
                pendingFunction(response)
            }
        })
        
    }

    for(let i = 0; i < $('.pencil-btn').length; i++){
        document.querySelectorAll('.pencil-btn')[i].addEventListener('click', () => ajax_method(i))
    }

    const pencil_elements = document.querySelectorAll('.pencil-btn');
    // console.log(pencil_elements.length)
    pencil_elements.forEach(function(element, index) {
        element.addEventListener('click', function() {
            ajax_method(index)
        });
    });

    // for(let i = 0; i < totalRecords; i++){
    //     console.log(pencil_elements[i])
    // }

    const pendingFunction = (response) =>{
        $('#pendingModal').removeClass('hidden')
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

    const populateTbody = (response) =>{
        console.log('tbody' , processing_time_running)
        // console.log("plain: " + response)
        response = JSON.parse(response);
        global_response = response
        let index = 0;
        let previous = 0;
        console.log(response)
        table_list = response


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
            }

            const tr = document.createElement('tr')
            tr.className = 'h-[61px]'

            const td_name = document.createElement('td')
            td_name.textContent = response[i]['reference_num'] + " - " + index

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

            const td_time_div_label_1 = document.createElement('label')
            td_time_div_label_1.textContent = " Referred: " + response[i]['date_time']
            td_time_div_label_1.className = `text-md`

            const td_time_div_label_2 = document.createElement('label')
            td_time_div_label_2.textContent = " Processed: " + response[i]['final_progressed_timer']
            td_time_div_label_2.className = `text-md`

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
            // td_processing.textContent = "Processing: "

            const td_processing_div = document.createElement('div')
            td_processing_div.className = 'flex flex-row justify-around items-center'
            td_processing_div.textContent = "Processing: "
            const td_processing_div_2 = document.createElement('div')

            td_processing_div_2.textContent = (response[i]['final_progressed_timer'] === null || response[i]['final_progressed_timer'] === "") ? "00:00:00" : response[i]['final_progressed_timer']
            if(status_search === 'Approved'){
                td_processing_div_2.textContent = 'Done'
            }
            var timeString = td_processing_div_2.textContent; // Example time string in "hh:mm:ss" format
            var match = timeString.match(/(\d+):(\d+):(\d+)/);

            if (match) {
                var hours = parseInt(match[1], 10);
                var minutes = parseInt(match[2], 10);
                var seconds = parseInt(match[3], 10);

                var totalMinutes = hours * 60 + minutes + seconds / 60;
                // console.log(totalMinutes); // Output: 3.466666666666667
                if(totalMinutes > 0.05){ // to be change
                    td_processing_div_2.style.color = 'red'
                }
            }

            // td_processing_div_2.id = 'stopwatch'
            td_processing_div_2.className = 'stopwatch'
            // console.log(td_processing_div_2)
            dom_table_list.push(td_processing_div_2)
            // <td class="border-2 border-black">-
            //     <div class="flex flex-row justify-around items-center">
            //         Processing: 
            //         <div> 
            //             <div id="stopwatch">00:00:00</div>
                        
            //         </div>
            //     </div>
            // </td>

            //start
            console.log(dom_table_list[i].textContent)
            const td_status = document.createElement('td')
            td_status.className = `font-bold text-center bg-gray-500`

            const td_status_div = document.createElement('div')
            td_status_div.className = `pat-status-incoming flex flex-row justify-around items-center`
            td_status_div.textContent = response[i]['status']
            status_table_list.push(td_status_div)

            const td_status_div_i = document.createElement('i')
            td_status_div_i.className = `pencil-btn fa-solid fa-pencil cursor-pointer hover:text-white`

            const td_status_div_input = document.createElement('input')
            td_status_div_input.className = `hpercode`
            td_status_div_input.type = "hidden";
            td_status_div_input.name = "hpercode";
            td_status_div_input.value = response[i]['hpercode'];
            global_hpercode_all.push(td_status_div_input)

            td_status_div.appendChild(td_status_div_i)
            td_status_div.appendChild(td_status_div_input)
            td_status.appendChild(td_status_div)
            // end

            td_time.appendChild(td_time_div_label_1)
            td_time.appendChild(td_time_div_label_2)

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

            
            if(response[i].status === 'On-Process'){
                hpercode_with_timer_running.push({ 'hpercode' : response[i].hpercode})
            }

            // console.log(hpercode_with_timer_running)

            // console.log(document.querySelectorAll('.stopwatch'))
            // console.log(dom_table_list)

            // for(let j = 0; j < hpercode_with_timer_running.length; j++){
            //     if(hpercode_with_timer_running[j].hpercode === response[i].hpercode){
            //         td_processing_div_2.textContent = "00:00:00"
            //     }
            // }
        }

        if(global_timer_continue){
            for(let i = 0; i  < global_timer_continue.length; i++){
                // console.log('mags')
                // console.log(global_realtime_update[i].elapsedTime)
                
                //andito yung mali. para ready ka na agad sa tuesday :))

                // console.log(global_timer_continue)
                // console.log(table_list)

                // if(global_timer_continue.length === table_list.length){
                //     document.querySelectorAll('.stopwatch')[i].textContent = global_realtime_update[i].elapsedTime
                // }else{
                //     document.querySelectorAll('.stopwatch')[global_timer_continue[i]].textContent = global_realtime_update[i].elapsedTime
                // }

                document.querySelectorAll('.stopwatch')[global_timer_continue[i]].textContent = global_realtime_update[i].elapsedTime

                // document.querySelectorAll('.stopwatch')[global_timer_continue[i]].textContent = "00:00:17"

            }
        }
        
        const ajax_method = (index) => {
            console.log('hgere')
            // pencil_index_clicked = index
            pencil_index_clicked_temp = index
            pat_clicked_code_temp = document.querySelectorAll('.hpercode')[index].value
            const data = {
                status : 'Approved',
                hpercode: document.querySelectorAll('.hpercode')[index].value
            }
    
            $.ajax({
                url: './php/process_pending.php',
                method: "POST",
                data:data,
                success: function(response){
                    response = JSON.parse(response);
                    // console.log(response)   
                    pendingFunction(response)
                }
            })
        }
    
        const pencil_elements = document.querySelectorAll('.pencil-btn');

        pencil_elements.forEach(function(element, index) {
            element.addEventListener('click', function() {
                ajax_method(index)
            });
        }); 

        if(processing_time_running === false){
            incoming_time = setTimeout(fetchMySQLData_incoming, 5000);
        }
    }

    function fetchMySQLData_incoming() {
        $.ajax({
            url: 'php/fetch_interval.php',
            method: "POST",
            data : {
                from_where : 'incoming'
            },
            success: function(response) {
                populateTbody(response)
            }
        });
        
    }

    // SEARCH BAR

    //incoming-search-btn
    $('#incoming-search-btn').on('click' , function(event){
        // console.log(global_timer_continue)
        // console.log(dom_table_list)
        // processing_time_running = true;
        // console.log(processing_time_running)
        // clearTimeout(incoming_time)
        // clearTimeout(idleTimer);
        processing_time_running = true;
        
        $('#incoming-clear-search-btn').removeClass('opacity-30 pointer-events-none')
 
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
        if(data.ref_no === "" && data.last_name === "" && data.first_name === "" && data.middle_name === "" && data.case_type === "" && data.agency === "" && data.status === 'Pending'){
            $('#modal-title-incoming').text('Warning')
            $('#modal-icon').addClass('fa-triangle-exclamation')
            $('#modal-icon').removeClass('fa-circle-check')
            $('#modal-body-incoming').text('Fill at least one bar.')
            $('#ok-modal-btn-incoming').text('Ok')

            $('#myModal-incoming').modal('show');
            
        }else{
            data['stopwatch_arr'] = []
            data['hpercode_arr'] = []
            if(global_timer_continue.length > 0){

                data['stopwatch_arr'] = []
                for(let i = 0; i < global_timer_continue.length; i++){
                    global_hpercode_arr.push(global_hpercode_all[global_timer_continue[i]].value)
                    data['stopwatch_arr'].push(dom_table_list[global_timer_continue[i]].textContent)
                }
                data['hpercode_arr'] = global_hpercode_arr

                for (let i = 0; i < global_timer_continue.length; i++) {
                    clearInterval(intervalIDs['interval_' + global_timer_continue[i]]);
                    delete intervalIDs['interval_' + global_timer_continue[i]];
                }

                for(let i = 0; i < global_timer_continue.length; i++){
                    try_arr[i]['func'](i , dom_table_list[global_timer_continue[i]].textContent , global_hpercode_arr[i]);
                }
                

            }
            
            console.log(data.stopwatch_arr.length)

            status_search = data.status
            $.ajax({
                url: './php/incoming_search.php',
                method: "POST", 
                data:data,
                success: function(response){
                    search_clicked = true
                    dom_table_list = []
                    status_table_list = []
                    global_hpercode_all = []
                    populateTbody(response)

                    response = JSON.parse(response);    
                    console.log(response)

                }
            })
        }
        
        
    })

    $('#incoming-clear-search-btn').on('click' , function(event){
        // console.log(processing_time_running)
        // processing_time_running = false;
        // console.log(processing_time_running)
        // clearTimeout(incoming_time)
        // clearTimeout(idleTimer);
        search_clicked = false;
        status_search = ""  
        let data = {
            get_all : true
        }

        global_hpercode_arr = []

        data['stopwatch_arr'] = []
        for(let i = 0; i < global_timer_continue.length; i++){
            global_hpercode_arr.push(global_hpercode_all[i].value)
            data['stopwatch_arr'].push(dom_table_list[i].textContent)
        }
        data['hpercode_arr'] = global_hpercode_arr

        for (let i = 0; i < global_timer_continue.length; i++) {
            clearInterval(intervalIDs['interval_' + i]);
            delete intervalIDs['interval_' + i];
        }

        console.log(global_timer_continue)

        for(let i = 0; i < global_timer_continue.length; i++){
            try_arr[global_timer_continue[i]]['func'](global_timer_continue[i] , data['stopwatch_arr'][i] , global_hpercode_arr[i]);
        }

        $.ajax({
            url: './php/incoming_search.php',
            method: "POST",
            data:data,
            success: function(response){
                // console.log(response)
                dom_table_list = []
                populateTbody(response)

                response = JSON.parse(response);    
                console.log(response)

                $('#incoming-referral-no-search').val("")
                $('#incoming-last-name-search').val("")
                $('#incoming-first-name-search').val("")
                $('#incoming-middle-name-search').val("")
                $('#incoming-type-select').val("")
                $('#incoming-agency-select').val("")
                $('#incoming-status-select').val('Pending')

                $('#incoming-clear-search-btn').addClass('opacity-30 pointer-events-none')

            }
        })
    })

    //$('#pendingModal').removeClass('hidden')
    $('#close-pending-modal').on('click' , function(event){
        $('#pendingModal').addClass('hidden')
    })

    $('#yes-modal-btn-incoming').on('click' , function(event){
        console.log('here')
        if(modal_filter === 'approval_confirmation'){
            console.log(pencil_index_clicked_temp)
            console.log(intervalIDs , `interval_${pencil_index_clicked_temp}` , typeof `interval_${pencil_index_clicked}`)

            const stopwatchDisplay = document.querySelectorAll('.stopwatch');
            const all_hpercode = document.querySelectorAll('.hpercode');

            if (intervalIDs.hasOwnProperty(`interval_${pencil_index_clicked_temp}`)) {
                console.log('here')
                // `interval_${pencil_index_clicked}`
                clearInterval(intervalIDs['interval_' + pencil_index_clicked_temp]);
                delete intervalIDs['interval_' + pencil_index_clicked_temp];

                // console.log(`interval_${pencil_index_clicked_temp}`)
                // processing_time_running = true
                document.querySelectorAll('.pat-status-incoming')[pencil_index_clicked_temp].textContent = "Approved"
            }
            console.log(global_hpercode)

            approved_clicked_bool = 'true';
            approved_clicked_hpercode = global_hpercode

            let index = 0;

            for(let i = 0; i < all_hpercode.length; i++){
                if(all_hpercode[i].value === global_hpercode){
                    index = i;
                }
            }

            const data = {
                hpercode : global_hpercode,
                timer : stopwatchDisplay[index].textContent,
                pat_class : $('#approve-classification-select').val()
            }

            console.log(data);

            // $.ajax({
            //     url: './php/approved_pending.php',
            //     method: "POST",
            //     data : data,
            //     success: function(response){     
            //         response = JSON.parse(response);    
            //         console.log(response)         
            //         $('#pendingModal').addClass('hidden')
            //         location.reload();
            //     }
            //  })
        }
    })


    function parseTimeToMilliseconds(timeString) {
        const [hours, minutes, seconds] = timeString.split(":");
        // console.log(hours, minutes, seconds)
        const totalMilliseconds = ((parseInt(hours, 10) * 60 + parseInt(minutes, 10)) * 60 + parseInt(seconds, 10)) * 1000;
        return totalMilliseconds;
        //5000
    }


    const try_timer = (index , timeVar, after_reload) =>{ // after reload  = hpercode or pat_clicked_code
        // console.log(index, timeVar)
        // console.log(pat_clicked_code)
        
        
        // const stopwatchDisplay = document.querySelectorAll('.stopwatch');
        let startTime = 0; 
        let elapsedTime = 0;

        function formatTime(milliseconds) {
            const date = new Date(milliseconds);
            return date.toISOString().substr(11, 8);
        }

        if(timeVar !== "0"){
            startTime =  parseTimeToMilliseconds(timeVar);
        }else{
            startTime = new Date().getTime() - elapsedTime;
        }
        // console.log(startTime)
        // startTime = new Date().getTime() - elapsedTime;

        const uniqueIdentifier = `interval_${index}`;

        // console.log(uniqueIdentifier)

        intervalIDs[uniqueIdentifier] = setInterval(() => {
            processing_time_running = true;

            // console.log("approved click bool: " + approved_clicked_bool)
            let data;
            if(timeVar === "0"){
                console.log('pisti')
                console.log(dom_table_list)
                const currentTime = new Date().getTime();

                startTime += secs_add
                secs_add = 0

                elapsedTime = currentTime - startTime;

                try_arr[index].time += 1;    
                // console.log(formatTime(elapsedTime))

                // console.log(hpercode_with_timer_running)


                if(search_clicked === false){
                    console.log('here')
                }

                dom_table_list[index].textContent = formatTime(elapsedTime)  

                // stopwatchDisplay[index].textContent = formatTime(elapsedTime)   
                //for initial load or whenever refreshed
                // if(search_clicked === false){
                //     // console.log("came from refresh, no value on table list")
                //     dom_table_list[index].textContent = formatTime(elapsedTime)  
                // }
                // if(hpercode_with_timer_running.length >= 1 && table_list.length >= 1 && search_clicked === true){
                //     // console.log(table_list)
                //     // console.log(hpercode_with_timer_running)

                //     let processing_in_table = false;
                //     for(let i = 0; i < table_list.length; i++){
                //         for(let j = 0; j < hpercode_with_timer_running.length; j++){
                //             // console.log(table_list[i].hpercode , hpercode_with_timer_running[j].hpercode)
                //             if(table_list[i].hpercode === hpercode_with_timer_running[j].hpercode){
                //                 processing_in_table = true;
                //             }
                //         }
                //     }
                //     if(processing_in_table === true){
                //         dom_table_list[index].textContent = formatTime(elapsedTime) 
                //         dom_table_list[index].style.color = "red"
                //     }else{
                //         for(let i = 0; i < dom_table_list.length; i++){
                //             dom_table_list[i].textContent = "00:00:00"
                //         }
                //     }
                // }

                // else{
                //     stopwatchDisplay[index].textContent = "00:00:00"
                //     // hanapin mo bat nag i stay yung value ng timer kapag nag search na.
                // }
                

                //120000 = 2 mins
                // if(elapsedTime >= 5000 && search_clicked === false){
                //     dom_table_list[index].style.color = "red"
                // }

                if(elapsedTime >= 5000){
                    dom_table_list[index].style.color = "red"
                }

                data = {
                    timer_running : false,
                    pat_clicked_code : after_reload,
                    elapsedTime : formatTime(elapsedTime),
                    table_index : index,
                    approved_bool : approved_clicked_bool,
                    approved_clicked_hpercode : approved_clicked_hpercode,
                    secs_add : secs_add
                }

                // console.log(secs_add)
            }else{
                console.log('ysaew')
                let index_inside_table_list = 0

                try_arr[index].time += 1;
                startTime += 1000

                startTime += secs_add
                secs_add = 0;

                // console.log(global_timer_continue)
                // console.log(table_list)


                // console.log(dom_table_list)
                // console.log(formatTime(startTime))
                // hpercode_with_timer_running

                // console.log(hpercode_with_timer_running.length)
                // console.log(hpercode_with_timer_running)
                // stopwatchDisplay[index].textContent = formatTime(startTime)   

                // if(index > table_list.length - 1){
                //     console.log('here')
                //     for(let i = 0; i < global_timer_continue.length; i++){
                //         if(global_timer_continue[i] === parseInt(index)){
                //             index_inside_table_list = i
                //         }
                //     }

                //     index = index_inside_table_list
                // }

                // console.log(index)

                //for initial load or whenever refreshed
                // if(search_clicked === false){
                //     // console.log("came from refresh, no value on table list")
                //     dom_table_list[index].textContent = formatTime(startTime)  
                // }
                
                // else if(hpercode_with_timer_running.length >= 1 && table_list.length >= 1 && search_clicked === true){
                //     console.log(index)
                //     // console.log(hpercode_with_timer_running)

                //     let processing_in_table = false;
                //     for(let i = 0; i < table_list.length; i++){
                //         for(let j = 0; j < hpercode_with_timer_running.length; j++){
                //             // console.log(table_list[i].hpercode , hpercode_with_timer_running[j].hpercode)
                //             if(table_list[i].hpercode === hpercode_with_timer_running[j].hpercode){
                //                 processing_in_table = true;
                //             }
                //         }
                //     }
                //     if(processing_in_table === true){
                //         dom_table_list[index].textContent = formatTime(startTime) 
                //         dom_table_list[index].style.color = "red"
                //     }else{
                //         for(let i = 0; i < dom_table_list.length; i++){
                //             dom_table_list[i].textContent = "00:00:00"
                //         }
                //     }
                // }

                // dom_table_list[index].textContent = formatTime(startTime)  


                console.log(status_search)
                if(search_clicked === false){
                    dom_table_list[index].textContent = formatTime(startTime)  
                }else{
                    // console.log(dom_table_list[index].textContent)
                    // console.log(index)
                    if(status_search === 'Approved'){
                        for(let i = 0; i < dom_table_list.length; i++){
                            // dom_table_list[i].textContent = 'Done'
                            dom_table_list[i].style.color = "black"
                        }
                    }
                }

                // else{
                //     stopwatchDisplay[index].textContent = "00:00:00"
                //     // hanapin mo bat nag i stay yung value ng timer kapag nag search na.
                // }
                

                //120000 = 2 mins
                // if(startTime >= 5000 && search_clicked === false){
                //     // console.log(index)
                //     dom_table_list[index].style.color = "red"
                // }

                if(startTime >= 5000){
                    dom_table_list[index].style.color = "red"
                }

                // console.log('patcode: ' + after_reload, ' --- index: ' + index)
                data = {
                    timer_running : true,
                    pat_clicked_code : after_reload,
                    elapsedTime : formatTime(startTime),
                    table_index : index,
                    approved_bool : approved_clicked_bool,
                    approved_clicked_hpercode : approved_clicked_hpercode, 
                    secs_add : secs_add
                }
            }

            // console.log(secs_add)  
            $.ajax({
               url: './php/process_timer.php',
               method: "POST",
               data:data,
               success: function(response){             
                    response = JSON.parse(response);  
                    // console.log(response)
                     
                    // console.log(hpercode_with_timer_running)
                    // for(let i = 0; i< document.querySelectorAll('.pat-status-incoming').length; i++){
                    //     // console.log(document.querySelectorAll('.pat-status-incoming')[i].textContent)
                    //     document.querySelectorAll('.pat-status-incoming')[i].textContent = "asdf"
                    // }

                    // di na gumaagana yung nasa process_timer.php kasi, pag clinick mo na yung approved humihinto na yung timer para doon sa index na yun.
                    // its either get mo yung index ng nag stop na timer tas pasa mo sa processtimer php or mag resign ka na lang. pero gl hf pa din 2morrow.

                    // console.log(table_list , response)
                    let index_array = [];
                    // console.log(table_list , response)
                    for(let i = 0; i < table_list.length; i++){
                        for(let j = 0; j < response.length; j++){
                            if(table_list[i].hpercode === response[j].pat_clicked_code){
                                index_array.push(i)
                            }
                        }
                    }
                    console.log(status_table_list[0].textContent)
                    for(let i = 0; i < index_array.length; i++){
                        status_table_list[index_array[i]].textContent = "On-Process"
                    }
               }
            })
        }, 1000); 
        
        $('#pendingModal').addClass('hidden')
    }

    // eto yung mag hahandle ng variables before ng reload
    let try_arr = [
        // {index : 0, time: 0 , func : try_timer},
        // {index : 1, time: 0 , func : try_timer},
        // {index : 2, time: 0 , func : try_timer}
    ]

    // eto naman mag hahandle ng variables after ng reload
    let after_reload = []

    for(let j = 0; j < totalRecords; j++){
        try_arr.push({index : j, time: 0 , func : try_timer})
    }

    if($('#timer-running-input').val() === '1'){
        // console.log(try_arr)    
        // try_arr[pencil_index_clicked]['func']('00:02:04');
        // try_arr[pencil_index_clicked]['func'](pencil_index_clicked);

        $.ajax({
            url: './php/fetch_onProcess.php',
            method: "POST",
            success: function(response){               
                response = JSON.parse(response);
                // response.pop()

                // for(let i = 0; i < response.length; i++){
                //     try_arr[response[i].table_index].time = response[i].elapsedTime
                // }
                // console.log(response)
                after_reload = response
                // console.log(after_reload)

                for(let i = 0; i < response.length; i++){
                    try_arr[after_reload[i].table_index]['func'](after_reload[i].table_index , after_reload[i].elapsedTime, after_reload[i].pat_clicked_code);
                }
            }
         })

        console.log(try_arr)    

        

    }
    
    $('#pending-start-btn').on('click' , function(event){
        // clearTimeout(incoming_time)
        // console.log(pencil_index_clicked)
        // console.log(document.querySelectorAll('.hpercode').length)
        // for(let i = 0; i < document.querySelectorAll('.hpercode').length; i++){
        //     console.log(document.querySelectorAll('.hpercode')[i].value + " , " + i)
        // }

        var prev_pencil_clicked_index

        pat_clicked_code = pat_clicked_code_temp
        pencil_index_clicked = pencil_index_clicked_temp
        // console.log("pat_clicked_code: " + pat_clicked_code + " - pencil_index_clicked: " + pencil_index_clicked)
        $.ajax({
            url: './php/fetch_onProcess.php',
            method: "POST",
            success: function(response){     
                response = JSON.parse(response);           
                // console.log(response) 
                // console.log(global_hpercode)

                let hpercode_index = 0;
                for(let i = 0; i < document.querySelectorAll('.hpercode').length; i++){
                    if( document.querySelectorAll('.hpercode')[i].value === global_hpercode){
                        hpercode_index = i;
                    }
                }

                // document.querySelectorAll('.pat-status-incoming')[hpercode_index].textContent = "On-Process"

                // document.querySelectorAll('.pat-status-incoming')[global_timer_continue[i]].textContent = "On-Process"
                // stopwatchDisplay[index].textContent = formatTime(elapsedTime);     
                // prev_pencil_clicked_index = pencil_index_clicked;          
            }
         })

        // for(let i = 0; i  < global_timer_continue.length; i++){
        //     document.querySelectorAll('.pat-status-incoming')[global_timer_continue[i]].textContent = "On-Process"
        // }
        // process_timerFunction(processing_time_running, prev_pencil_clicked_index) 
        try_arr[pencil_index_clicked]['func'](pencil_index_clicked , "0" , pat_clicked_code);
        
        // location.reload()

        // for(let i = 0; i < document.querySelectorAll('.hpercode').length; i++){
        // console.log(document.querySelectorAll('.hpercode')[i].value)
        // }

        // Event listener to start the stopwatch
        // document.getElementById('startButton').addEventListener('click', function() {
        // startTime = new Date().getTime() - elapsedTime;
        // updateStopwatch();
        // timer = setInterval(updateStopwatch, 1000); // Update every second
        // });

        // Event listener to stop the stopwatch
        // document.getElementById('stopButton').addEventListener('click', function() {
        // clearInterval(timer);
        // });

        // // Event listener to reset the stopwatch
        // document.getElementById('resetButton').addEventListener('click', function() {
        // clearInterval(timer);
        // elapsedTime = 0;
        // stopwatchDisplay.textContent = '00:00:00';
        // });
    })

    $('#pending-approved-btn').on('click' , function(event){
        // pencil_index_clicked
        // pat_clicked_code
        // document.querySelectorAll('.pat-status-incoming')[response[i].table_index].textContent = "On-Process"

        $('#modal-title-incoming').text('Warning')
        $('#modal-icon').addClass('fa-triangle-exclamation')
        $('#modal-icon').removeClass('fa-circle-check')
        $('#modal-body-incoming').text('Approval Confirmation')
        $('#yes-modal-btn-incoming').removeClass('hidden')
        $('#ok-modal-btn-incoming').text('No')

        modal_filter = 'approval_confirmation'

        $('#myModal-incoming').modal('show');
    })

    // $('#incoming-referral-no-search').val("")
    // $('#incoming-last-name-search').val("")
    // $('#incoming-first-name-search').val("")
    // $('#incoming-middle-name-search').val("")
    // $('#incoming-type-select').val("")
    // $('#incoming-agency-select').val("")
    // $('#incoming-status-select').val('Pending')
})

// process timer total date and time
// tas pag nag log out, add mo yung [log out time - log in again] sa prev progress time nila.
// on process pencil button should not be clickable on another account