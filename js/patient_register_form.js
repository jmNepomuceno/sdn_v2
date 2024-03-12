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
    
    $('#check-if-registered-btn').on('click' , function(event){
        // console.log("asdf")

        // document.querySelector('#check-if-registered-btn').classList.toggle('w-[492px]');
        if(document.querySelector('#check-if-registered-btn').classList.contains('w-[50px]')){
            document.querySelector('#check-if-registered-btn').classList.remove('w-[50px]');
            document.querySelector('#check-if-registered-btn').classList.add('w-[492px]');


            setTimeout(function() {
                // Your code here
                // document.querySelector('#check-if-registered-h3').classList.toggle('hidden');
                console.log('here')
                document.querySelector('#check-if-registered-h3').classList.remove('hidden');


                if(document.querySelector('#check-if-registered-div').classList.contains('hidden')){
                    document.querySelector('#check-if-registered-div').classList.remove('hidden')
            
                    document.querySelector('#check-if-registered-btn').classList.remove('rounded-lg')
                    document.querySelector('#check-if-registered-btn') .classList.add('rounded-t-lg')
                }else{
                    
                }
            }, 200);

        }else {
            document.querySelector('#check-if-registered-btn').classList.add('w-[50px]');
            document.querySelector('#check-if-registered-btn').classList.remove('w-[492px]');

            setTimeout(function() {
                // Your code here
                // document.querySelector('#check-if-registered-h3').classList.toggle('hidden');

                document.querySelector('#check-if-registered-h3').classList.add('hidden');
                document.querySelector('#check-if-registered-div').classList.add('hidden')
            
                    document.querySelector('#check-if-registered-btn').classList.add('rounded-lg')
                    document.querySelector('#check-if-registered-btn') .classList.remove('rounded-t-lg')
            }, 100);
        }
        

        // setTimeout(function() {
        //     // Your code here
        //     // document.querySelector('#check-if-registered-h3').classList.toggle('hidden');
        //     if(check_if_registered_div.classList.contains('hidden')){
        //         check_if_registered_div.classList.remove('hidden')
        
        //         check_if_registered_btn.classList.remove('rounded-lg')
        //         check_if_registered_btn .classList.add('rounded-t-lg')
        //     }else{
        //         check_if_registered_div.classList.add('hidden')
        
        //         check_if_registered_btn.classList.add('rounded-lg')
        //         check_if_registered_btn .classList.remove('rounded-t-lg')
        //     }
        // }, 200);

        // const check_if_registered_btn = document.querySelector('#check-if-registered-btn');
        // const check_if_registered_div = document.querySelector('#check-if-registered-div')

        
    })

    // $('#check-if-registered-btn').on('mouseout ' , function(event){
    //     document.querySelector('#check-if-registered-btn').classList.toggle('w-[492px]');
    // })

    let input_arr = ['#hperson-last-name' , '#hperson-first-name' , '#hperson-middle-name' , ' #hperson-birthday' , '#hperson-gender' , '#hperson-civil-status' , 
                        '#hperson-religion' , '#hperson-nationality' , '#hperson-phic'  , '#hperson-house-no-pa' , '#hperson-street-block-pa' , '#hperson-region-select-pa' ,
                         '#hperson-region-select-pa' , '#hperson-province-select-pa' , '#hperson-city-select-pa' , '#hperson-brgy-select-pa' , '#hperson-mobile-no-pa',
                         '#hperson-house-no-ca' , '#hperson-street-block-ca' , '#hperson-region-select-ca' , '#hperson-province-select-ca' , 
                         '#hperson-city-select-ca' , '#hperson-brgy-select-ca' , '#hperson-mobile-no-ca']

    let all_non_req_input_arr = ['#hperson-ext-name' , '#hperson-age', '#hperson-occupation' , '#hperson-passport-no' , '#hperson-home-phone-no-pa' , '#hperson-email-pa',
                         '#hperson-email-ca' , '#hperson-home-phone-no-ca' , '#hperson-house-no-cwa' , '#hperson-street-block-cwa' , '#hperson-region-select-cwa' , 
                         '#hperson-province-select-cwa','#hperson-city-select-cwa', '#hperson-brgy-select-cwa' , '#hperson-workplace-cwa', '#hperson-ll-mb-no-cwa' , '#hperson-email-cwa',
                         '#hperson-emp-name-ofw','#hperson-occupation-ofw','#hperson-place-work-ofw','#hperson-house-no-ofw','#hperson-street-ofw','#hperson-region-select-ofw',
                         '#hperson-province-select-ofw','#hperson-city-select-ofw', '#hperson-country-select-ofw' , '#hperson-office-phone-no-ofw' , '#hperson-mobile-no-ofw']
    let all_input_arr = input_arr.concat(all_non_req_input_arr); 
    let zero_inputs = 0;
    let data;
    $('#same-as-perma-btn').on('click' , function(event){
        // console.log(document.querySelector('#hperson-province-select-pa').value)

        document.querySelector('#hperson-house-no-ca').value = document.querySelector('#hperson-house-no-pa').value
        document.querySelector('#hperson-street-block-ca').value = document.querySelector('#hperson-street-block-pa').value
        document.querySelector('#hperson-region-select-ca').value = document.querySelector('#hperson-region-select-pa').value
        // document.querySelector('#hperson-province-select-ca').value = $('#hperson-province-select-pa').find(':selected').text()
        // document.querySelector('#hperson-city-select-ca').value = $('#hperson-city-select-pa').find(':selected').text()


        let province_element_ca = document.createElement('option')
        province_element_ca.value = $('#hperson-province-select-pa').val()
        province_element_ca.text =  $('#hperson-province-select-pa').find(':selected').text()
        document.querySelector('#hperson-province-select-ca').appendChild(province_element_ca);
        document.querySelector('#hperson-province-select-ca').value = province_element_ca.value

        let city_element_ca = document.createElement('option')
        city_element_ca.value = $('#hperson-city-select-pa').val()
        city_element_ca.text =  $('#hperson-city-select-pa').find(':selected').text()
        document.querySelector('#hperson-city-select-ca').appendChild(city_element_ca);
        document.querySelector('#hperson-city-select-ca').value = city_element_ca.value

        let brgy_element_ca = document.createElement('option')
        brgy_element_ca.value = $('#hperson-brgy-select-pa').val()
        brgy_element_ca.text =  $('#hperson-brgy-select-pa').find(':selected').text()
        document.querySelector('#hperson-brgy-select-ca').appendChild(brgy_element_ca);
        document.querySelector('#hperson-brgy-select-ca').value = brgy_element_ca.value
        

        // create option element for the city select input
        // let city_element = document.createElement('option')
        // city_element.value = response[i].pat_municipality;
        // city_element.text =  response[i].pat_municipality;
        // document.querySelector('#hperson-city-select-ca').appendChild(city_element);
        // document.querySelector('#hperson-city-select-ca').value = city_element.value

        // // create option element for the barangay select input
        // let brgy_element = document.createElement('option')
        // brgy_element.value = response[i].pat_barangay;
        // brgy_element.text =  response[i].pat_barangay;
        // document.querySelector('#hperson-brgy-select-ca').appendChild(brgy_element);
        // document.querySelector('#hperson-brgy-select-ca').value = brgy_element.value


        
        document.querySelector('#hperson-home-phone-no-ca').value = document.querySelector('#hperson-home-phone-no-pa').value
        document.querySelector('#hperson-mobile-no-ca').value = document.querySelector('#hperson-mobile-no-pa').value
        document.querySelector('#hperson-email-ca').value = document.querySelector('#hperson-email-pa').value
    })
    

    let age_value = 0
    $('#hperson-birthday').on('input' , function(event){
        //converting of birthdate
        const timestamp = Date.parse( $('#hperson-birthday').val());
        const date = new Date(timestamp)
        let year = date.getFullYear()
        let month = date.getMonth() + 1
        month = month <= 9 ? "0" + month.toString() : month
        let day = (date.getDate() < 10) ? "0" + date.getDate().toString() : date.getDate().toString()
        // console.log(year.toString() + "-" + month.toString() + "-" + day.toString())

        //calculating the age based on day of birth
        const dateOfBirth = year.toString() + "-" + month.toString() + "-" + day.toString()
        const age = calculateAge(dateOfBirth);
        age_value = age
        document.querySelector('#hperson-age').value = age_value

        // document.querySelector('#hperson-gender').value = response[i].patsex
    })


    $('#add-patform-btn-id').on('click' , function(event){
        event.preventDefault();

        if($('#add-patform-btn-id').text() == 'Add'){
            console.log($('#add-patform-btn-id').text())
            zero_inputs = 0;
            // check if the required inputs have values , if no, border color = red.
            for(let i = 0; i < input_arr.length; i++){
                // checkInputFields($(input_arr[i]))
                if($(input_arr[i]).val() === ""){
                    $(input_arr[i]).removeClass('border-2 border-[#bfbfbf]')
                    $(input_arr[i]).addClass('border-2 border-red-600')
                    zero_inputs += 1
                }
            }
            // console.log("zero_inputs - " + zero_inputs)
            // zero_inputs = 0;

            const currentDateTime = new Date();
            const year = currentDateTime.getFullYear();
            const month = currentDateTime.getMonth() + 1; // Month is zero-based, so add 1 to get the correct month.
            const day = currentDateTime.getDate();
            const hours = currentDateTime.getHours();
            const minutes = currentDateTime.getMinutes();
            const seconds = currentDateTime.getSeconds();
            let created_at = (`${year}-${month}-${day} ${hours}:${minutes}:${seconds}`)
            console.log(created_at);

            // zero_inputs = 0;
            
            if(zero_inputs >= 1){
                // alert('Please fill out the required fields.')
                // $('#myModal_pat_reg').modal('show');
                const myModal = new bootstrap.Modal(document.getElementById('myModal_pat_reg'));
                myModal.show();
            }else{

                data = {
                    //PERSONAL INFORMATIONS
                    //initial idea is to fetch the last patient hpatcode from the database whenever the patient registration form clicked
                    //16
                    // hpercode : (Math.floor(Math.random() * 1000) + 1).toString(),
                    hpatcode : $('#hpatcode-input').val(),
                    patlast : $('#hperson-last-name').val(), // accepts null = no
                    patfirst : $('#hperson-first-name').val(), //accepts null = no
                    patmiddle : $('#hperson-middle-name').val(), // accepts null = yes
                    patsuffix : ($('#hperson-ext-name').val()) ? $('#hperson-ext-name').val() : "N/A",
                    pat_bdate : $('#hperson-birthday').val(), // accepts null = yes
                    pat_age : $('#hperson-age').val(), // accepts null = yes
                    patsex : $('#hperson-gender').val(), // accepts null = no
                    patcstat : $('#hperson-civil-status').val(), //accepts null = yes
                    relcode : $('#hperson-religion').val(), // accepts null = yes
                    
                    pat_occupation :($('#hperson-occupation').val()) ? $('#hperson-occupation').val() : "N/A",
                    natcode : $('#hperson-nationality').val(), // accepts null = yes
                    pat_passport_no : ($('#hperson-passport-no').val()) ? $('#hperson-passport-no').val() : "N/A",
                    //SESSION the hospital code upon logging.
                    hospital_code : $('#hpatcode-input').val(),
                    phicnum : $('#hperson-phic').val(), // accepts null = yes
        
                    //PERMANENT ADDRESS
                    //9
                    pat_bldg_pa : $('#hperson-house-no-pa').val(), // accepts null = yes
                    hperson_street_block_pa: $('#hperson-street-block-pa').val(), // accepts null = yes
                    pat_region_pa : $('#hperson-region-select-pa').val(), // accepts null = no
                    pat_province_pa : $('#hperson-province-select-pa').val(), // accepts null = no
                    pat_municipality_pa : $('#hperson-city-select-pa').val(), // accepts null = no
                    pat_barangay_pa : $('#hperson-brgy-select-pa').val(), // accepts null = no
                    pat_email_pa :($('#hperson-email-pa').val()) ? $('#hperson-email-pa').val() : "N/A",
                    pat_homephone_no_pa : parseInt(($('#hperson-home-phone-no-pa').val())) ? $('#hperson-home-phone-no-pa').val() : 0,
                    pat_mobile_no_pa : $('#hperson-mobile-no-pa').val(), // accepts null = no
        
                    //CURRENT ADDRESS
                    //9
                    pat_bldg_ca : $('#hperson-house-no-ca').val(),
                    hperson_street_block_ca: $('#hperson-street-block-ca').val(),
                    pat_region_ca : $('#hperson-region-select-ca').val(),
                    pat_province_ca : $('#hperson-province-select-ca').val(),
                    pat_municipality_ca : $('#hperson-city-select-ca').val(),
                    pat_barangay_ca : $('#hperson-brgy-select-ca').val(),
                    pat_email_ca :($('#hperson-email-ca').val()),
                    pat_homephone_no_ca : parseInt(($('#hperson-home-phone-no-ca').val())) ? $('#hperson-home-phone-no-ca').val() : 0,
                    pat_mobile_no_ca : $('#hperson-mobile-no-ca').val(), // accepts null = no
        
                    // CURRENT WORKPLACE ADDRESS
                    //9
                    pat_bldg_cwa : $('#hperson-house-no-cwa').val() ? $('#hperson-house-no-cwa').val() : "N/A",
                    hperson_street_block_pa_cwa: $('#hperson-street-block-cwa').val() ? $('#hperson-street-block-cwa').val() : "N/A",
                    pat_region_cwa : $('#hperson-region-select-cwa').val() ? $('#hperson-region-select-cwa').val() : "N/A",
                    pat_province_cwa : $('#hperson-province-select-cwa').val() ? $('#hperson-province-select-cwa').val() : "N/A",
                    pat_municipality_cwa : $('#hperson-city-select-cwa').val() ? $('#hperson-city-select-cwa').val() : "N/A",
                    pat_barangay_cwa : $('#hperson-brgy-select-cwa').val() ? $('#hperson-brgy-select-cwa').val() : "N/A",
                    pat_namework_place : $('#hperson-workplace-cwa').val() ? $('#hperson-workplace-cwa').val() : "N/A",
                    pat_landline_no : parseInt($('#hperson-ll-mb-no-cwa').val()) ? $('#hperson-ll-mb-no-cwa').val() : "N/A",
                    pat_email_cwa : $('#hperson-email-cwa').val() ? $('#hperson-email-cwa').val() : "N/A",
        
        
                    // FOR OFW ONLY
                    // 10
                    pat_emp_name : $('#hperson-emp-name-ofw').val() ? $('#hperson-emp-name-ofw').val() : "N/A",
                    pat_occupation_ofw: $('#hperson-occupation-ofw').val() ? $('#hperson-occupation-ofw').val() : "N/A",
                    pat_place_work : $('#hperson-place-work-ofw').val()? $('#hperson-place-work-ofw').val() : "N/A",
                    pat_bldg_ofw : $('#hperson-house-no-ofw').val() ? $('#hperson-house-no-ofw').val() : "N/A",
                    hperson_street_block_ofw : $('#hperson-street-ofw').val() ? $('#hperson-street-ofw').val() : "N/A",
                    pat_region_ofw : $('#hperson-region-select-ofw').val() ? $('#hperson-region-select-ofw').val() : "N/A",
                    pat_province_ofw : $('#hperson-province-select-ofw').val() ? $('#hperson-province-select-ofw').val() : "N/A",
                    pat_city_ofw : $('#hperson-city-select-ofw').val() ? $('#hperson-city-select-ofw').val() : "N/A",
                    pat_country_ofw : $('#hperson-country-select-ofw').val() ? $('#hperson-country-select-ofw').val() : "N/A",
                    pat_office_mobile_no_ofw : parseInt($('#hperson-office-phone-no-ofw').val()) ? $('#hperson-office-phone-no-ofw').val() : 0,
                    pat_mobile_no_ofw : parseInt($('#hperson-mobile-no-ofw').val()) ? $('#hperson-mobile-no-ofw').val() : 0,

                    created_at : created_at,
                }   

             
                for (var key in data) {
                    if (data.hasOwnProperty(key)) {
                        console.log(key + " -> " + data[key] + " -> " + typeof data[key]);
                    }
                }

                

                for(let i = 0; i < all_non_req_input_arr.length; i++){
                    // console.log($('#hperson-home-phone-no-ca').val())
                    if($(all_non_req_input_arr[i]).val() === ""){
                        $(all_non_req_input_arr[i]).val("N/A")
                    }
                }

                // console.log(all_input_arr)

                for(var i = 0; i < all_input_arr.length; i++){
                    // if($(all_non_req_input_arr[i]).val() === ""){
                    //     $(all_non_req_input_arr[i]).val("N/A")
                    // }
                    // $(all_input_arr[i]).addClass('pointer-events-none bg-[#cccccc]')
                }
                console.log(data)
                $.ajax({
                    url: './php/add_patient_form.php',
                    method: "POST",
                    data:data,
                    success: function(response){
                        $('#modal-title').text('Warning')
                        $('#modal-icon').addClass('fa-triangle-exclamation')
                        $('#modal-icon').removeClass('fa-circle-check')
                        $('#modal-body').text('Are you sure with the information?')
                        $('#ok-modal-btn').text('No')

                        $('#yes-modal-btn').text('Register');
                        $('#yes-modal-btn').removeClass('hidden')

                        const myModal = new bootstrap.Modal(document.getElementById('myModal_pat_reg'));
                        myModal.show();
                        
                        console.log(response)
                    }
                })
            }
        }else if($('#add-patform-btn-id').text() == 'Refer'){
            $('#modal-title').text('Warning')
            $('#modal-icon').addClass('fa-triangle-exclamation')
            $('#modal-icon').removeClass('fa-circle-check')
            $('#modal-body').text('Are you sure with the information?')
            $('#ok-modal-btn').text('No')

            $('#yes-modal-btn').text('Confirm');
            $('#yes-modal-btn').removeClass('hidden')

            const myModal = new bootstrap.Modal(document.getElementById('myModal_pat_reg'));
            myModal.show();
            // $('#myModal').modal('show');

            // $('#add-patform-btn-id').addClass('hidden')
            // $('#clear-patform-btn-id').addClass('hidden')
        }
        // console.log(document.querySelector('#hperson-email-ca').value)
        // console.log('>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> ' + $('#hperson-occupation').val())
    })

    $('#clear-patform-btn-id').on('click' , function(event){
        if($('#clear-patform-btn-id').text() == "Cancel"){
            $('#modal-title').text('Warning')
            $('#modal-icon').addClass('fa-triangle-exclamation')
            $('#modal-icon').removeClass('fa-circle-check')
            $('#modal-body').text('Are you sure you want to cancel the registration?')
            $('#ok-modal-btn').text('No')

            $('#yes-modal-btn').text('Yes');
            $('#yes-modal-btn').removeClass('hidden')

            const myModal = new bootstrap.Modal(document.getElementById('myModal_pat_reg'));
            myModal.show();
            // $('#myModal').modal('show');

            for(let i = 0; i < all_input_arr.length; i++){
                $(all_input_arr[i]).addClass('pointer-events-none bg-[#cccccc]')
            }
        }
        else{
            for(let i = 0; i < all_input_arr.length; i++){
                $(all_input_arr[i]).val('')
            }
        }
        
    })

    $('#yes-modal-btn').on('click' , function(event){
        // console.log($('#yes-modal-btn').val())
        if($('#yes-modal-btn').text() == 'Yes'){
        
            $('#er-patform-btn-id').addClass('hidden')
            $('#ob-patform-btn-id').addClass('hidden')
            $('#opd-patform-btn-id').addClass('hidden')
            $('#pcr-patform-btn-id').addClass('hidden')

            $('#er-patform-btn-id').removeClass('bg-[#526c7a]')
            $('#er-patform-btn-id').addClass('bg-mainColor')

            $('#ob-patform-btn-id').removeClass('bg-[#526c7a]')
            $('#ob-patform-btn-id').addClass('bg-mainColor')

            $('#opd-patform-btn-id').removeClass('bg-[#526c7a]')
            $('#opd-patform-btn-id').addClass('bg-mainColor')

            $('#pcr-patform-btn-id').removeClass('bg-[#526c7a]')
            $('#pcr-patform-btn-id').addClass('bg-mainColor')

            $('#er-patform-btn-id').removeClass('pointer-events-none opacity-20')
            $('#opd-patform-btn-id').removeClass('pointer-events-none opacity-20')
            $('#ob-patform-btn-id').removeClass('pointer-events-none opacity-20')
            $('#pcr-patform-btn-id').removeClass('pointer-events-none opacity-20')

            $('#check-if-registered-btn').removeClass('hidden')
            $('#privacy-reminder-div').addClass('hidden')
            $('#yes-modal-btn').addClass('hidden')
            $('#ok-modal-btn').text('Ok')
            $('#clear-patform-btn-id').text('Clear')

            $('#add-patform-btn-id').text('Add')
            $('#add-patform-btn-id').addClass('bg-cyan-600 hover:bg-cyan-700')
            $('#add-patform-btn-id').removeClass('bg-green-600 hover:bg-green-700')
            $('#add-patform-btn-id').removeClass('pointer-events-none opacity-20')

            $("#classification-dropdown").addClass('hidden')
            $('#add-clear-btn-div').removeClass('mt-[70%]')

            for(let i = 0; i < all_input_arr.length; i++){
                $(all_input_arr[i]).removeClass('pointer-events-none bg-[#cccccc]')
                $(all_input_arr[i]).val('')
            }

            for(let i = 0; i < input_arr.length; i++){
                
                $(input_arr[i]).removeClass('border-2 border-red-600')
                $(input_arr[i]).addClass('border-2 border-[#bfbfbf]')
            }

        }else if($('#yes-modal-btn').text() == 'Register'){
            // console.log("here")
            setTimeout(function() {
                $('#modal-title').text('Successed')
                $('#modal-icon').removeClass('fa-triangle-exclamation')
                $('#modal-icon').addClass('fa-circle-check')
                $('#modal-body').text('Registered Successfully')

                $('#yes-modal-btn').addClass('hidden')
                $('#ok-modal-btn').text('Ok')
                const myModal = new bootstrap.Modal(document.getElementById('myModal_pat_reg'));
                myModal.show();
                // $('#myModal').modal('show');
            }, 500);
            
        }
        else if($('#yes-modal-btn').text() == 'Confirm'){
            loadContent('php/opd_referral_form.php?type=' + $('#tertiary-case').val() + "&code=" + $('#hpercode-input').val())
        }
    })

    $('#ok-modal-btn').on('click' , function(event){
        // console.log($('#ok-modal-btn').text(),$('#clear-patform-btn-id').text())
        if($('#ok-modal-btn').text() == 'No' && $('#clear-patform-btn-id').text() == "Cancel"){
            $('#add-patform-btn-id').removeClass('hidden')
            $('#clear-patform-btn-id').removeClass('hidden')
        }
        else if($('#ok-modal-btn').text() == 'OK' && $('#clear-patform-btn-id').text() == "Cancel"){

            $('#add-patform-btn-id').text('Refer')
            for(let i = 0; i < input_arr.length; i++){
                $(input_arr[i]).removeClass('border-2 border-red-600')
                $(input_arr[i]).addClass('border-2 border-[#bfbfbf]')
            }
        }
    })

    $('#er-patform-btn-id').on('click' , function(event){
        $('#add-patform-btn-id').removeClass('pointer-events-none opacity-20')
        $('#er-patform-btn-id').removeClass('bg-[#526c7a]')
        $('#er-patform-btn-id').addClass('bg-mainColor')
        $('#er-patform-btn-id').removeClass('hover:bg-mainColor')

        $('#add-patform-btn-id').text('Refer')
        $('#tertiary-case').val('ER')

        $('#ob-patform-btn-id').addClass('bg-[#526c7a]')
        $('#opd-patform-btn-id').addClass('bg-[#526c7a]')
        $('#pcr-patform-btn-id').addClass('bg-[#526c7a]')

        $('#ob-patform-btn-id').removeClass('bg-mainColor')
        $('#opd-patform-btn-id').removeClass('bg-mainColor')
        $('#pcr-patform-btn-id').removeClass('bg-mainColor')
    })
    $('#ob-patform-btn-id').on('click' , function(event){
        $('#add-patform-btn-id').removeClass('pointer-events-none opacity-20')
        $('#ob-patform-btn-id').removeClass('bg-[#526c7a]')
        $('#ob-patform-btn-id').addClass('bg-mainColor')
        $('#ob-patform-btn-id').removeClass('hover:bg-mainColor')

        $('#add-patform-btn-id').text('Refer')
        $('#tertiary-case').val('OB')

        $('#er-patform-btn-id').addClass('bg-[#526c7a]')
        $('#opd-patform-btn-id').addClass('bg-[#526c7a]')
        $('#pcr-patform-btn-id').addClass('bg-[#526c7a]')

        $('#er-patform-btn-id').removeClass('bg-mainColor')
        $('#opd-patform-btn-id').removeClass('bg-mainColor')
        $('#pcr-patform-btn-id').removeClass('bg-mainColor')
    })
    $('#opd-patform-btn-id').on('click' , function(event){
        $('#add-patform-btn-id').removeClass('pointer-events-none opacity-20')
        $('#opd-patform-btn-id').removeClass('bg-[#526c7a]')
        $('#opd-patform-btn-id').addClass('bg-mainColor')
        $('#opd-patform-btn-id').removeClass('hover:bg-mainColor')

        $('#add-patform-btn-id').text('Refer')
        $('#tertiary-case').val('OPD')

        $('#ob-patform-btn-id').addClass('bg-[#526c7a]')
        $('#er-patform-btn-id').addClass('bg-[#526c7a]')
        $('#pcr-patform-btn-id').addClass('bg-[#526c7a]')

        $('#ob-patform-btn-id').removeClass('bg-mainColor')
        $('#er-patform-btn-id').removeClass('bg-mainColor')
        $('#pcr-patform-btn-id').removeClass('bg-mainColor')
    })
    $('#pcr-patform-btn-id').on('click' , function(event){
        $('#add-patform-btn-id').removeClass('pointer-events-none opacity-20')
        $('#pcr-patform-btn-id').removeClass('bg-[#526c7a]')
        $('#pcr-patform-btn-id').addClass('bg-mainColor')
        $('#pcr-patform-btn-id').removeClass('hover:bg-mainColor')

        $('#add-patform-btn-id').text('Refer')
        $('#tertiary-case').val('PCR')

        $('#ob-patform-btn-id').addClass('bg-[#526c7a]')
        $('#opd-patform-btn-id').addClass('bg-[#526c7a]')
        $('#er-patform-btn-id').addClass('bg-[#526c7a]')

        $('#ob-patform-btn-id').removeClass('bg-mainColor')
        $('#opd-patform-btn-id').removeClass('bg-mainColor')
        $('#er-patform-btn-id').removeClass('bg-mainColor')
    })

    let classification_dd_counter = true
    $('#classification-dropdown').on('click' , function(event){
        if(classification_dd_counter){
            $('#add-clear-btn-div').removeClass('mt-10')
            $('#add-clear-btn-div').addClass('mt-[70%]')

            classification_dd_counter = false
        }else{
            $('#add-clear-btn-div').addClass('mt-10')
            $('#add-clear-btn-div').removeClass('mt-[70%]')

            classification_dd_counter = true
        }
    })

    // Use jQuery to handle the change event
    $("#classification-dropdown").change(function() {
        // Get the selected value using val()
        var selectedValue = $(this).val();
  
        // Display the selected value
        console.log("Selected Value: " + selectedValue);
        let chosen_case = ""
        switch(selectedValue){
            case 'er' : chosen_case = "ER"; break;
            case 'ob' : chosen_case = "OB"; break;
            case 'opd' : chosen_case = "OPD"; break;
            case 'toxicology' : chosen_case = "Toxicology"; break;
            // case 'er' : chosen_case = "ER";
        }
        console.log(chosen_case)
        $('#add-patform-btn-id').removeClass('pointer-events-none opacity-20')
        $('#add-patform-btn-id').text('Refer')
        $('#tertiary-case').val(chosen_case)
      });

})



