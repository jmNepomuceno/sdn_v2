function calculateAge(dateOfBirth) {
    // Create a Date object for the current date
    const currentDate = new Date();

    // Parse the date of birth string into a Date object
    const dob = new Date(dateOfBirth);

    // Calculate the time difference in milliseconds
    const timeDiff = currentDate - dob;

    // Calculate the age in years
    const age = Math.floor(timeDiff / (1000 * 60 * 60 * 24 * 365.25));

    return age;
}

$(document).ready(function(){
    let if_clicked_same_perma = false;

    $('#search-patient-btn').on('click' , function(event){
        event.preventDefault();

        const search_lname = document.querySelector('#search-lname').value
        const search_fname = document.querySelector('#search-fname').value
        const search_mname = document.querySelector('#search-mname').value

        const data = {
            search_lname: search_lname,
            search_fname: search_fname,
            search_mname: search_mname
        }

        // console.log(data)

        // // console.log(data.otp_number, " type of " , typeof(data.otp_number))
        // // console.log(total)

        const search_query_result = document.querySelector('#search-result-div')
        while (search_query_result.hasChildNodes()) {
            search_query_result.removeChild(search_query_result.firstChild);
            }


        let lname_has_value = search_lname != ""
        let fname_has_value = search_fname != ""
        let mname_has_value = search_mname != ""

        // console.log(lname_has_value, fname_has_value, mname_has_value)
        const container = document.createElement('h1')
        container.className = 'mt-2'
        // container.textContent = "Please input two(2) or more characters."
        document.querySelector('#search-result-div').appendChild(container)

        
        if(lname_has_value && search_lname.length < 2){
            container.textContent = "Please input two(2) or more characters."
        }else if(fname_has_value && search_fname.length < 2){
            container.textContent = "Please input two(2) or more characters."
        }else if(mname_has_value && search_mname.length < 2){
            container.textContent = "Please input two(2) or more characters."
        }else{
            $.ajax({
                url: '../php/search_name.php',
                method: "POST",
                data:data,
                success: function(response){
                    // response = JSON.parse(response);
                    // console.log(response)
                    let input_arr = ['#hperson-last-name' , '#hperson-first-name' , '#hperson-middle-name' , ' #hperson-birthday' , '#hperson-gender' , '#hperson-civil-status' , 
                                    '#hperson-religion' , '#hperson-nationality' , '#hperson-phic' , '#hperson-hospital-no' , '#hperson-house-no-pa' , '#hperson-street-block-pa' , '#hperson-region-select-pa' ,
                                    '#hperson-region-select-pa' , '#hperson-province-select-pa' , '#hperson-city-select-pa' , '#hperson-brgy-select-pa' , '#hperson-mobile-no-pa',
                                    '#hperson-house-no-ca' , '#hperson-street-block-ca' , '#hperson-region-select-ca' , '#hperson-province-select-ca' , 
                                    '#hperson-city-select-ca' , '#hperson-brgy-select-ca' , '#hperson-mobile-no-ca']

                    let all_non_req_input_arr = ['#hperson-ext-name' , '#hperson-age', '#hperson-occupation' , '#hperson-passport-no' , '#hperson-home-phone-no-pa' , '#hperson-email-pa',
                                                '#hperson-email-ca' , '#hperson-home-phone-no-ca' , '#hperson-house-no-cwa' , '#hperson-street-block-cwa' , '#hperson-region-select-cwa' , 
                                                '#hperson-province-select-cwa','#hperson-city-select-cwa', '#hperson-brgy-select-cwa' , '#hperson-workplace-cwa', '#hperson-ll-mb-no-cwa' , '#hperson-email-cwa',
                                                '#hperson-emp-name-ofw','#hperson-occupation-ofw','#hperson-place-work-ofw','#hperson-house-no-ofw','#hperson-street-ofw','#hperson-region-select-ofw',
                                                '#hperson-province-select-ofw','#hperson-city-select-ofw', '#hperson-country-select-ofw' , '#hperson-office-phone-no-ofw' , '#hperson-mobile-no-ofw']
                    let all_input_arr = input_arr.concat(all_non_req_input_arr); 
                    // console.log(all_input_arr)
                    
                    // SEARCH QUERY RESULT
                    const search_query_result = document.querySelector('#search-result-div')
                    while (search_query_result.hasChildNodes()) {
                        search_query_result.removeChild(search_query_result.firstChild);
                      }
                      
                    // split the string to see if theres a duplicate name
                    response = response.split('&')
    
                    for(let i = 0; i < response.length - 1; i++){
                        response[i] = JSON.parse(response[i])
    
                    }
                    // console.log(response == "No User Found")
                    if(response == "No User Found" || response == ""){
                        const container = document.createElement('h1')
                        container.className = 'mt-2'
                        container.textContent = 'No User Found'
                        document.querySelector('#search-result-div').appendChild(container)
                    }else{
                        console.log(response)
                        for(let i = 0; i < response.length - 1; i++){
                            const container = document.createElement('div')
                            container.className = (i % 2 == 0) ? `w-full h-[80px] flex flex-col justify-center items-center border-b border-black cursor-pointer hover:bg-[#85b2f9] patient-code-${i}` : 
                            `w-full h-[80px] flex flex-col justify-center items-center border-b border-black cursor-pointer hover:bg-[#85b2f9] bg-[#e6e6e6] patient-code-${i}`
                            
                            const second = document.createElement('div')
                            second.className = 'w-full h-[40%] flex flex-row justify-between items-center'
    
                            const h1 = document.createElement('h1')
                            h1.innerHTML = "Patient ID: " + response[i]['hpercode']
                            // h1.className = `hover:underline patient-code-${i}`
    
    
                            const third = document.createElement('div')
                            third.className = 'w-[40%] h-full flex flex-row justify-around items-center'
    
                            const h1_second = document.createElement('h1')
                            h1_second.innerHTML = "Birthdate: " + response[i]['pat_bdate']
                            h1_second.className = "w-full mr-4"
    
                            const container_second = document.createElement('div')
                            container_second.className = 'w-full h-[40%] flex flex-row justify-between items-center'
    
                            const h3 = document.createElement('h3')
                            // h3.className = `uppercase ml-2 hover:underline patient-name-${i}`
                            h3.className = "font-bold underline"
                            h3.innerHTML = response[i]['pat_last_name'] + ", " + response[i]['pat_first_name'] + " " + response[i]['pat_middle_name']
                            // h3.id = "patient-name-" + i

                            // console.log(response[i].status)
                            const stat = document.createElement('h3')
                            stat.className = 'mr-5'
                            stat.innerHTML = (response[i].status) ? "Status: Referred-" + response[i].status : "Status: Not yet referred";
                            
                            container.appendChild(second)
                            container.appendChild(container_second)
    
                            container_second.appendChild(h3)
                            container_second.appendChild(stat)
    
                            second.appendChild(h1)
                            second.appendChild(third)
    
                            third.appendChild(h1_second)
    
                            document.querySelector('#search-result-div').appendChild(container)
                        }
                    }
    
    
                    // WHEN CLICKED THE NAME OF THE PATIENT
                    // hperson-last-name
                    //response.length
    
                    // $('.search-result-div').on('click' , function(event){
                    //     console.log(event.target.id )
                    // })

                    
                    for(let i = 0; i < response.length - 1; i++){
                        document.querySelector(".patient-code-" + i).addEventListener('click', function(e){
                            // document.querySelector('#hperson-province-select-pa').innerHTML
                            var parentElement = document.querySelector('#hperson-province-select-pa');
    
                            while (parentElement.firstChild) {
                                parentElement.removeChild(parentElement.firstChild);
                            }
                            
                            //Personal Information
                            $('#hpercode-input').val(response[i].hpercode)
                            document.querySelector('#hperson-last-name').value = response[i].pat_last_name
                            document.querySelector('#hperson-first-name').value = response[i].pat_first_name
                            document.querySelector('#hperson-middle-name').value = response[i].pat_middle_name
                            document.querySelector('#hperson-ext-name').value = response[i].pat_suffix_name
    
                            //converting of birthdate
                            const timestamp = Date.parse(response[i].pat_bdate);
                            const date = new Date(timestamp)
                            let year = date.getFullYear()
                            let month = date.getMonth() + 1
                            month = month <= 9 ? "0" + month.toString() : month
                            let day = (date.getDate() < 10) ? "0" + date.getDate().toString() : date.getDate().toString()
                            document.querySelector('#hperson-birthday').value = year.toString() + "-" + month.toString() + "-" + day.toString()
                            // console.log(year.toString() + "-" + month.toString() + "-" + day.toString())
    
                            //calculating the age based on day of birth
                            const dateOfBirth = year.toString() + "-" + month.toString() + "-" + day.toString()
                            const age = calculateAge(dateOfBirth);
                            document.querySelector('#hperson-age').value = age
    
                            document.querySelector('#hperson-gender').value = response[i].patsex
    
    
                            let cstat = ""
                            switch(response[0].patcstat){
                                case "1": cstat = "Single";break;
                                case "2": cstat = "Married";break;
                                case "3": cstat = "Divorced";break;
                                case "4": cstat = "Widowed";break;
                                default: break;
                            }
                            document.querySelector('#hperson-civil-status').value = response[i].patcstat
                            
                            document.querySelector('#hperson-religion').value = response[i].relcode
                            
                            document.querySelector('#hperson-occupation').value = (response[i].pat_occupation) ? response[i].pat_occupation : "N/A"
                            document.querySelector('#hperson-nationality').value = (response[i].natcode) ? response[i].natcode : "N/A"
                            document.querySelector('#hperson-passport-no').value = (response[i].pat_passport_no) ? response[i].pat_passport_no : "N/A"
    
    
                             //Others
                            
                            document.querySelector('#hperson-hospital-no').value = parseInt((response[i].hospital_code)) ? parseInt(response[i].hospital_code) : 0
                            document.querySelector('#hperson-phic').value = (response[i].phicnum) ? response[i].phicnum : "N/A"
                            // document.querySelector('#hperson-nationality').value = (response[0].natcode) ? response[0].natcode : "N/A"
    
    
                            // PERMANENT ADDRESS
                            document.querySelector('#hperson-house-no-pa').value = response[i].pat_bldg
                            document.querySelector('#hperson-street-block-pa').value = response[i].pat_street_block
    
    
                            document.querySelector('#hperson-region-select-pa').value = response[i].pat_region
    
                            // create option element for the province select input
                            let province_element = document.createElement('option')
                            province_element.value = response[i].pat_province;
                            province_element.text =  response[i].pat_province;
                            document.querySelector('#hperson-province-select-pa').appendChild(province_element);
                            document.querySelector('#hperson-province-select-pa').value = province_element.value
                            
    
                            // create option element for the city select input
                            let city_element = document.createElement('option')
                            city_element.value = response[i].pat_municipality;
                            city_element.text =  response[i].pat_municipality;
                            document.querySelector('#hperson-city-select-pa').appendChild(city_element);
                            document.querySelector('#hperson-city-select-pa').value = city_element.value
    
                             // create option element for the barangay select input
                             let brgy_element = document.createElement('option')
                             brgy_element.value = response[i].pat_barangay;
                             brgy_element.text =  response[i].pat_barangay;
                             document.querySelector('#hperson-brgy-select-pa').appendChild(brgy_element);
                             document.querySelector('#hperson-brgy-select-pa').value = brgy_element.value
    
                            // document.querySelector('#hperson-house-no-pa').value = response[i].pat_bldg
                            // document.querySelector('#hperson-house-no-pa').value = response[i].pat_bldg
                            // document.querySelector('#hperson-house-no-pa').value = response[i].pat_bldg
    
    
                            document.querySelector('#hperson-home-phone-no-pa').value = response[i].pat_homephone_no
                            document.querySelector('#hperson-mobile-no-pa').value = response[i].pat_mobile_no
                            document.querySelector('#hperson-email-pa').value = response[i].pat_email
    
    
                            // CURRENT ADDRESS
                            document.querySelector('#hperson-house-no-ca').value = response[i].pat_curr_bldg
                            document.querySelector('#hperson-street-block-ca').value = response[i].pat_curr_street
    
    
                            // document.querySelector('#hperson-house-no-pa').value = response[i].pat_bldg
                            // document.querySelector('#hperson-house-no-pa').value = response[i].pat_bldg
                            // document.querySelector('#hperson-house-no-pa').value = response[i].pat_bldg
                            // document.querySelector('#hperson-house-no-pa').value = response[i].pat_bldg
    
                            document.querySelector('#hperson-region-select-ca').value = response[i].pat_curr_region
    
                            // create option element for the province select input
                            let province_element_ca = document.createElement('option')
                            province_element_ca.value = response[i].pat_curr_province;
                            province_element_ca.text =  response[i].pat_curr_province;
                            document.querySelector('#hperson-province-select-ca').appendChild(province_element_ca);
                            document.querySelector('#hperson-province-select-ca').value = province_element_ca.value
                            
    
                            // create option element for the city select input
                            let city_element_ca = document.createElement('option')
                            city_element_ca.value = response[i].pat_curr_municipality;
                            city_element_ca.text =  response[i].pat_curr_municipality;
                            document.querySelector('#hperson-city-select-ca').appendChild(city_element_ca);
                            document.querySelector('#hperson-city-select-ca').value = city_element_ca.value
    
                            // create option element for the barangay select input
                            let brgy_element_ca = document.createElement('option')
                            brgy_element_ca.value = response[i].pat_curr_barangay;
                            brgy_element_ca.text =  response[i].pat_curr_barangay;
                            document.querySelector('#hperson-brgy-select-ca').appendChild(brgy_element_ca);
                            document.querySelector('#hperson-brgy-select-ca').value = brgy_element_ca.value
    
    
                            document.querySelector('#hperson-home-phone-no-ca').value = response[i].pat_curr_homephone_no
                            document.querySelector('#hperson-mobile-no-ca').value = response[i].pat_curr_mobile_no
                            document.querySelector('#hperson-email-ca').value = response[i].pat_email_ca
    
                             
                            // CURRENT WORKPLACE ADDRESS
                            document.querySelector('#hperson-house-no-cwa').value = response[i].pat_work_bldg
                            document.querySelector('#hperson-street-block-cwa').value = response[i].pat_work_street
    
    
                            document.querySelector('#hperson-region-select-cwa').value = response[i].pat_work_region
    
                            // create option element for the province select input
                            let province_element_cwa = document.createElement('option')
                            province_element_cwa.value = response[i].pat_work_province;
                            province_element_cwa.text =  response[i].pat_work_province;
                            document.querySelector('#hperson-province-select-cwa').appendChild(province_element_cwa);
                            document.querySelector('#hperson-province-select-cwa').value = province_element_cwa.value
                            
    
                            // create option element for the city select input
                            let city_element_cwa = document.createElement('option')
                            city_element_cwa.value = response[i].pat_work_municipality;
                            city_element_cwa.text =  response[i].pat_work_municipality;
                            document.querySelector('#hperson-city-select-cwa').appendChild(city_element_cwa);
                            document.querySelector('#hperson-city-select-cwa').value = city_element_cwa.value
    
                            // create option element for the barangay select input
                            let brgy_element_cwa = document.createElement('option')
                            brgy_element_cwa.value = response[i].pat_work_barangay;
                            brgy_element_cwa.text =  response[i].pat_work_barangay;
                            document.querySelector('#hperson-brgy-select-cwa').appendChild(brgy_element_cwa);
                            document.querySelector('#hperson-brgy-select-cwa').value = brgy_element_cwa.value
    
    
                            document.querySelector('#hperson-workplace-cwa').value = response[i].pat_namework_place
                            document.querySelector('#hperson-ll-mb-no-cwa').value = response[i].pat_work_landline_no
                            document.querySelector('#hperson-email-cwa').value = response[i].pat_work_email_add
    
                            // OFW
                            document.querySelector('#hperson-emp-name-ofw').value = response[i].ofw_employers_name
                            document.querySelector('#hperson-occupation-ofw').value = response[i].ofw_occupation
                            document.querySelector('#hperson-place-work-ofw').value = response[i].ofw_place_of_work
                            document.querySelector('#hperson-house-no-ofw').value = response[i].ofw_bldg
                            document.querySelector('#hperson-street-ofw').value = response[i].ofw_street
    
                            document.querySelector('#hperson-region-select-ofw').value = response[i].ofw_region
                            document.querySelector('#hperson-province-select-ofw').value = response[i].ofw_province
                            document.querySelector('#hperson-city-select-ofw').value = response[i].ofw_municipality
                            document.querySelector('#hperson-country-select-ofw').value = response[i].ofw_country
    
                            document.querySelector('#hperson-office-phone-no-ofw').value = response[i].ofw_office_phone_no
                            document.querySelector('#hperson-mobile-no-ofw').value = response[i].ofw_mobile_phone_no

                
                            for(let j = 0; j < all_input_arr.length; j++){
                                $(all_input_arr[j]).addClass('pointer-events-none bg-[#cccccc]')
                                // document.querySelector(all_input_arr[i]).classList.add('pointer-events-none bg-[#cccccc]')
                                // console.log(all_input_arr[j])
                            }
                            
                            if(response[i].status === ''){
                                $('#er-patform-btn-id').removeClass('hidden')
                                $('#ob-patform-btn-id').removeClass('hidden')
                                $('#opd-patform-btn-id').removeClass('hidden')
                                $('#pcr-patform-btn-id').removeClass('hidden')

                                $('#er-patform-btn-id').removeClass('opacity-30 pointer-events-none')
                                $('#ob-patform-btn-id').removeClass('opacity-30 pointer-events-none')
                                $('#opd-patform-btn-id').removeClass('opacity-30 pointer-events-none')
                                $('#pcr-patform-btn-id').removeClass('opacity-30 pointer-events-none')
                            }else{
                                $('#add-patform-btn-id').removeClass('bg-cyan-600 hover:bg-cyan-700')
                                $('#add-patform-btn-id').addClass('bg-green-600 hover:bg-green-700')
                                $('#add-patform-btn-id').addClass('pointer-events-none opacity-20')
                            }

                            

                            // $('#add-patform-btn-id').removeClass('bg-cyan-600 hover:bg-cyan-700')
                            // $('#add-patform-btn-id').addClass('bg-green-600 hover:bg-green-700')
                            // $('#add-patform-btn-id').addClass('pointer-events-none opacity-20')

                            $('#clear-patform-btn-id').text('Cancel')
                            // $('#check-if-registered-btn').addClass('hidden')

                            $('#privacy-reminder-div').removeClass('hidden')
                            $("#classification-dropdown").removeClass('hidden')
                            $('#add-clear-btn-div').addClass('mt-10')
                        })
                    }
    
                    // for(let i = 0; i < response.length - 1; i++){
                    //     document.querySelector(".patient-name-" + i).addEventListener('click', function(e){
                    //         console.log(response[i])
    
                    //     })
                    // }
                }
            })
        }
        

    })
})