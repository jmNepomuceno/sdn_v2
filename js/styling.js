const main_logo = document.querySelector('.main-logo')

const main_div = document.querySelector('.main-div')
const modal_div = document.querySelector('.modal-div')

const sdn_div = document.querySelector(".sdn-div");
const tms_div = document.querySelector(".tms-div");
const create_acc_div = document.querySelector(".create-acc-div");

const tms_lock_icon = document.querySelector('.tms-lock-icon')
const tms_text = document.querySelector('.tms-text')

const sdn_lock_icon = document.querySelector('.sdn-lock-icon')
const sdn_text = document.querySelector('.sdn-text')

const cc_lock_icon = document.querySelector('.cc-lock-icon')
const cc_text = document.querySelector('.cc-text')

const hover_img = document.querySelector(".hover-img");

const ask_account_sdn_h3 = document.querySelector(".ask-account-sdn-h3");
const sdn_login_btn = document.querySelector(".sdn-login-btn");

const ask_account_tms_h3 = document.querySelector(".ask-account-tms-h3");
const tms_login_btn = document.querySelector(".tms-login-btn");

const ask_account_cc_h3 = document.querySelector(".ask-account-cc-h3");
const cc_login_btn = document.querySelector(".cc-login-btn");

// main modal close button
const main_modal_close_btn = document.querySelector('.modal-div-close-btn')

//telemedicine sub modal div
const tms_sub_modal = document.querySelector(".tms-choose-modal-div");
//telemedicine close button variable
const tms_close_btn = document.querySelector(".tms-btn-close");
//telemedicine modal div variable
const tms_modal_div = document.querySelector(".tms-modal-div");
//telemedicine login modal div
const tms_login_modal_div = document.querySelector('.tm-login-modal-div')
//telemedicine login
const tms_login_modal_btn = document.querySelector('.tms-login-btn')
//telemedicine close login btn
const tms_login_close_btn = document.querySelector('.tm-login-btn-close')
// tms register password inputfield
const tm_register_password_btn = document.querySelector('#tms-password')
// tms register confirm password inputfield
const tm_register_cf_password_btn = document.querySelector('#tms-confirm-password')
// tms register do not match h1
const tm_match_h1 = document.querySelector('#tms-match-h1')
// tms mobile number
const tms_mobile_no = document.querySelector('#tms-mobile-no')

main_logo.addEventListener('click' , function(){
    location.reload()
})

//999999999999
//handle max input numbers for input type number mobile no.
$("#tms-mobile-no").on("input",function(){
    var maxLength = 11;
    var inputValue = tms_mobile_no.value;
    
    if (inputValue.length < maxLength) {
        tms_mobile_no.className = "w-[95%] h-[40%] border-2 border-red-600 rounded-lg outline-none p-2"
    } else if (inputValue.length >= maxLength) {
        tms_mobile_no.value = inputValue.slice(0, maxLength);
        tms_mobile_no.className = "w-[95%] h-[40%] border-2 border-green-500 rounded-lg outline-none p-2"
    } 
})

// handle password and confirm password matching
$("#tms-confirm-password").keyup(function(){
    // console.log($('#tms-confirm-password').val() , "," , tm_register_password_btn.value
    if($('#tms-confirm-password').val() !== tm_register_password_btn.value){
        tm_register_cf_password_btn.className = "uppercase w-[95%] h-[40%] border-2 border-red-600 rounded-lg outline-none p-2"
        tm_register_password_btn.className = "uppercase w-[95%] h-[40%] border-2 border-red-600 rounded-lg outline-none p-2"
        tm_match_h1.className = "text-red-600 block"
    }else{
        tm_register_cf_password_btn.className = "uppercase w-[95%] h-[40%] border-2 border-green-500 rounded-lg outline-none p-2"
        tm_register_password_btn.className = "uppercase w-[95%] h-[40%] border-2 border-green-500 rounded-lg outline-none p-2"
        tm_match_h1.className = "text-red-600 hidden"
    }
});
// handle password and confirm password matching
$("#tms-password").keyup(function(){
    // console.log($('#tms-confirm-password').val() , "," , tm_register_password_btn.value)
    if($('#tms-confirm-password').val() !== tm_register_password_btn.value){
        tm_register_cf_password_btn.className = "uppercase w-[95%] h-[40%] border-2 border-red-600 rounded-lg outline-none p-2"
        tm_register_password_btn.className = "uppercase w-[95%] h-[40%] border-2 border-red-600 rounded-lg outline-none p-2"
        tm_match_h1.className = "text-red-600 block"
    }else{
        tm_register_cf_password_btn.className = "uppercase w-[95%] h-[40%] border-2 border-green-500 rounded-lg outline-none p-2"
        tm_register_password_btn.className = "uppercase w-[95%] h-[40%] border-2 border-green-500 rounded-lg outline-none p-2"
        tm_match_h1.className = "text-red-600 hidden"
    }
});

//SDN close button variable
const sdn_close_btn = document.querySelector(".sdn-btn-close");
//SDN sub modal div
const sdn_sub_modal = document.querySelector(".sdn-choose-modal-div");

//sdn modal main div
const sdn_modal_div = document.querySelector('.sdn-modal-div')
//sdn sub modal main div
const sdn_sub_modal_div = document.querySelector('.sdn-sub-modal-div')
// sdn registration nav button
const sdn_registration_nav_btn = document.querySelector('.sdn-registration-nav-btn')
// sdn authorization nav button
const sdn_authorization_nav_btn = document.querySelector('.sdn-authorization-nav-btn')

// sdn registarion modal div
const sdn_registration_modal_div = document.querySelector('.sdn-registration-modal-div')
// sdn autorization modal div
const sdn_authorization_modal_div = document.querySelector('.sdn-authorization-modal-div')

//sdn register button
const sdn_register_button = document.querySelector('.sdn-register-btn')

//sdn login modal div
const sdn_login_modal_div = document.querySelector('.sdn-login-modal-div')
//sdn login
const sdn_login_modal_btn = document.querySelector('.sdn-login-btn')
//sdn close login btn
const sdn_login_close_btn = document.querySelector('.sdn-login-btn-close')
// sdn main login btn 
const sdn_main_login_btn = document.querySelector('.sdn-login-main-btn')

//OTP value indexes
const otp_val_index_1 = document.querySelector('#otp-input-1')
const otp_val_index_2 = document.querySelector('#otp-input-2')
const otp_val_index_3 = document.querySelector('#otp-input-3')
const otp_val_index_4 = document.querySelector('#otp-input-4')
const otp_val_index_5 = document.querySelector('#otp-input-5')
const otp_val_index_6 = document.querySelector('#otp-input-6')

//handle the appropriate mobile number syntax in SDN registration form
const sdn_hospital_mobile_no = document.querySelector('#sdn-hospital-mobile-no')
const sdn_hospital_director_mobile_no = document.querySelector('#sdn-hospital-director-mobile-no')
const sdn_point_person_mobile_no = document.querySelector('#sdn-point-person-mobile-no')
const sdn_hospital_landline_no = document.querySelector('#sdn-landline-no')

//handle the appropriate email address input value field
const sdn_email_adress = document.querySelector('#sdn-email-address')
const sdn_zip_code = document.querySelector('#sdn-zip-code')

// text only for last, first and middle name in sdn authorization form
const sdn_autho_last_name = document.querySelector('#sdn-autho-last-name-id')

const mobileNumValue = (inputID) => {
    // Remove any non-numeric characters
    let value = inputID.value.replace(/[^0-9]/g, '');
    // Add dashes at specific positions
    if (value.length >= 4) {
        value = value.slice(0, 4) + '-' + value.slice(4);
      }
      if (value.length >= 9) {
        value = value.slice(0, 9) + '-' + value.slice(9);
      }
      if (value.length > 13) {
        value = value.slice(0, 13);
      }
      inputID.value = value;
}

sdn_autho_last_name.addEventListener('input', function(e){
    const regex = /^[a-zA-Z\s]*$/;

    if (!regex.test(e.target.value)) {
      // If a non-text character is entered, remove it from the input.
      e.target.value = e.target.value.replace(/[^a-zA-Z\s]/g, "");
    }
})

$("#sdn-landline-no").on("input", function(){
    // console.log(sdn_hospital_landline_no.value)
    // Remove any non-numeric characters
    let value = sdn_hospital_landline_no.value.replace(/[^0-9]/g, '');
    // Add dashes at specific positions
    if (value.length >= 3) {
        value = value.slice(0, 3) + '-' + value.slice(3);
    }
    if (value.length > 8) {
        value = value.slice(0, 8);
    }
    sdn_hospital_landline_no.value = value;
})

$("#sdn-zip-code").on("input", function(){
    // Remove any non-numeric characters
    
    let value = sdn_zip_code.value.replace(/[^0-9]/g, '');
    // Add dashes at specific positions
    if (value.length > 4) {
        value = value.slice(0, 4);
    }
    sdn_zip_code.value = value;
})

//0919-6044820
$("#sdn-email-address").on("input", function(){
    let emailInput= sdn_email_adress;
    let emailValue = sdn_email_adress.value;
    let validRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;

    // Check if the input contains the required pattern
    if (!emailValue.match(validRegex)) {
        // console.log('asdf')

        sdn_email_adress.classList.remove('border-cyan-500')
        sdn_email_adress.classList.add('border-red-600')
        emailInput.focus(); // Return focus to the input field
    }else{
        // console.log('here')

        sdn_email_adress.classList.remove('border-red-600')
        sdn_email_adress.classList.add('border-cyan-500')
    }
    
    return true; // Allow form submission if the input is valid
})

$("#sdn-hospital-mobile-no").on("input", () => mobileNumValue(sdn_hospital_mobile_no))
$("#sdn-hospital-director-mobile-no").on("input", () => mobileNumValue(sdn_hospital_director_mobile_no))
$("#sdn-point-person-mobile-no").on("input", () => mobileNumValue(sdn_point_person_mobile_no))



sdn_div.addEventListener('mouseover', function(){
    ask_account_sdn_h3.style.display = "flex"
    sdn_login_btn.style.display = "flex"

    sdn_lock_icon.className = "sdn-lock-icon fa-solid fa-lock text-white text-3xl"
    sdn_text.className = "sdn-text text-white text-2xl"

    hover_img.src = './assets/main_imgs/sdn_img.jpg'
}, false)

sdn_div.addEventListener('mouseout', function(){
    ask_account_sdn_h3.style.display = "none"
    sdn_login_btn.style.display = "none"

    sdn_lock_icon.className = "sdn-lock-icon fa-solid fa-lock text-white"
    sdn_text.className = "sdn-text text-white text-lg"
}, false)



//handle the one input only for OTP number
$("#otp-input-1").on("input",function(){
    var maxLength = 1;
    var inputValue = document.querySelector('#otp-input-1').value
    document.querySelector('#otp-input-2').focus()

    if (inputValue.length > maxLength) {
        document.querySelector('#otp-input-1').value = inputValue.slice(0, maxLength);

    } 
})

$("#otp-input-2").on("input",function(){
    var maxLength = 1;
    var inputValue = document.querySelector('#otp-input-2').value
    document.querySelector('#otp-input-3').focus()

    console.log(event.keyCode, event.charCode) 

    if (inputValue.length > maxLength) {
        document.querySelector('#otp-input-2').value = inputValue.slice(0, maxLength);
    } 
})

$("#otp-input-2").on("keydown",function(){
    if( event.keyCode == 8 || event.charCode == 46 ){
        document.querySelector('#otp-input-2').value = ""
    }
})

$("#otp-input-3").on("input",function(){
    var maxLength = 1;
    var inputValue = document.querySelector('#otp-input-3').value
    document.querySelector('#otp-input-4').focus()

    if (inputValue.length > maxLength) {
        document.querySelector('#otp-input-3').value = inputValue.slice(0, maxLength);
    } 
})

$("#otp-input-3").on("keydown",function(){
    if( event.keyCode == 8 || event.charCode == 46 ){
        document.querySelector('#otp-input-3').value = ""
    }
})

$("#otp-input-4").on("input",function(){
    var maxLength = 1;
    var inputValue = document.querySelector('#otp-input-4').value
    document.querySelector('#otp-input-5').focus()

    if (inputValue.length > maxLength) {
        document.querySelector('#otp-input-4').value = inputValue.slice(0, maxLength);
    } 
})

$("#otp-input-4").on("keydown",function(){
    if( event.keyCode == 8 || event.charCode == 46 ){
        document.querySelector('#otp-input-4').value = ""
    }
})

$("#otp-input-5").on("input",function(){
    var maxLength = 1;
    var inputValue = document.querySelector('#otp-input-5').value
    document.querySelector('#otp-input-6').focus()

    if (inputValue.length > maxLength) {
        document.querySelector('#otp-input-5').value = inputValue.slice(0, maxLength);
    } 
})

$("#otp-input-5").on("keydown",function(){
    if( event.keyCode == 8 || event.charCode == 46 ){
        document.querySelector('#otp-input-5').value = ""
    }
})

$("#otp-input-6").on("input",function(){
    var maxLength = 1;
    var inputValue = document.querySelector('#otp-input-6').value
    if (inputValue.length > maxLength) {
        document.querySelector('#otp-input-6').value = inputValue.slice(0, maxLength);
    } 
})

$("#otp-input-6").on("keydown",function(){
    if( (event.keyCode == 8 || event.charCode == 46) && document.querySelector('#otp-input-6').value == "" ){
        document.querySelector('#otp-input-5').value = ""
    }

    if( (event.keyCode == 8 || event.charCode == 46) && document.querySelector('#otp-input-6').value != "" ){
        document.querySelector('#otp-input-6').value = ""
    }
})


create_acc_div.addEventListener('mouseover', function(){
    ask_account_cc_h3.style.display = "flex"
    cc_login_btn.style.display = "flex"

    cc_lock_icon.className = "cc-lock-icon fa-solid fa-user text-white text-3xl"
    cc_text.className = "cc-text text-white text-3xl"

    hover_img.src = './assets/main_imgs/create_acc.jpg'

}, false)

create_acc_div.addEventListener('mouseout', function(){
    ask_account_cc_h3.style.display = "none"
    cc_login_btn.style.display = "none"

    cc_lock_icon.className = "cc-lock-icon fa-solid fa-lock text-white"
    cc_text.className = "cc-text text-white text-xl"
}, false)

cc_login_btn.addEventListener('click', () =>{
    console.log('here')
    main_div.style.filter = "blur(8px)"
    modal_div.style.display = 'flex'
    modal_div.style.zIndex = '10' // z-10
    main_div.style.zIndex = '0'
}, false)

// close main modal
main_modal_close_btn.addEventListener('click', () =>{
    modal_div.style.display = 'none'
    main_div.style.filter = "blur(0)"
    modal_div.style.zIndex = '0'
    main_div.style.zIndex = '10'
}, false)

// open the telemedicine modal create account
tms_sub_modal.addEventListener('click', () =>{
    modal_div.style.display = 'none'
    modal_div.style.zIndex = '0'

    tms_modal_div.style.display = 'flex'
    tms_modal_div.style.zIndex = '10'
})

// telemedicine create account close button
tms_close_btn.addEventListener('click', () => {
    tms_modal_div.style.display = 'none'
    tms_modal_div.style.zIndex = '0'

    main_div.style.filter = "blur(0)"
    modal_div.style.zIndex = '0'
    main_div.style.zIndex = '10'
})

// open the SDN modal create account
sdn_sub_modal.addEventListener('click', () =>{
    modal_div.style.display = 'none'
    modal_div.style.zIndex = '0'

    // sdn_modal_div.style.display = 'flex'
    // sdn_modal_div.style.zIndex = '10'

    sdn_modal_div.classList.remove('hidden')
    sdn_modal_div.classList.add('absolute')
})

// SDN create account close button
sdn_close_btn.addEventListener('click', () => {
    // sdn_modal_div.style.display = 'none'

    sdn_modal_div.classList.add('hidden')
    sdn_modal_div.classList.remove('absolute')
    sdn_modal_div.style.zIndex = '0'

    main_div.style.filter = "blur(0)"
    modal_div.style.zIndex = '0'
    main_div.style.zIndex = '10'
})

// telemedicine login user
sdn_login_modal_btn.addEventListener('click', () =>{
    // console.log("here")
    sdn_login_modal_div.style.display = "flex"
    sdn_login_modal_div.style.zIndex = "10"

    main_div.style.filter = "blur(8px)"
    main_div.style.zIndex = "0"
})

sdn_login_close_btn.addEventListener('click', () =>{
    sdn_login_modal_div.style.display = "none"
    sdn_login_modal_div.style.zIndex = "0"

    main_div.style.filter = "blur(0)"
    main_div.style.zIndex = "10"
})

// navigate to REGISTRATION and AUTOHRIZATION in SDN CREATE ACCOUNT
sdn_authorization_nav_btn.addEventListener('click', () =>{
    sdn_authorization_nav_btn.className = "sdn-registration-nav-btn w-2/5 sm:w-1/4 h-3/4 rounded-t-lg ml-2 bg-cyan-500 flex flex-col justify-center items-center text-lg sm:text-2xl text-white cursor-pointer"
    sdn_registration_nav_btn.className = "sdn-registration-nav-btn w-2/5 sm:w-1/4 h-3/4 rounded-t-lg ml-2 bg-mainColor flex flex-col justify-center items-center text-lg sm:text-2xl text-white cursor-pointer"
    
    sdn_registration_modal_div.style.display = 'none'
    sdn_authorization_modal_div.style.display = 'flex'
    // sdn_sub_modal_div.className = "sdn-sub-modal-div flex flex-col justify-start items-center w-11/12 sm:w-2/5 h-2/4 bg-teleCreateAccColor"

    main_div.style.filter = "blur(8px)"

})

sdn_registration_nav_btn.addEventListener('click', () =>{
    sdn_registration_nav_btn.className = "sdn-registration-nav-btn w-2/5 sm:w-1/4 h-3/4 rounded-t-lg ml-2 bg-cyan-500 flex flex-col justify-center items-center text-lg sm:text-2xl text-white cursor-pointer"
    sdn_authorization_nav_btn.className = "sdn-registration-nav-btn w-2/5 sm:w-1/4 h-3/4 rounded-t-lg ml-2 bg-mainColor flex flex-col justify-center items-center text-lg sm:text-2xl text-white cursor-pointer"
    
    sdn_registration_modal_div.style.display = 'flex'
    sdn_authorization_modal_div.style.display = 'none'
    // sdn_sub_modal_div.className = "sdn-sub-modal-div flex flex-col justify-start items-center w-11/12 sm:w-2/5 h-5/6 bg-teleCreateAccColor"
})

let userIsActive = true;
    function handleUserActivity() {
        userIsActive = true;
        // console.log('asdf')
        // Additional code to handle user activity if needed
        // console.log('active')
    }

    function handleUserInactivity() {
        userIsActive = false;
        // console.log('fdsa')
    }

    // Attach event listeners
    document.addEventListener('mousemove', handleUserActivity);

    // Set up a timer to check user inactivity periodically
    const inactivityInterval = 2000; // Execute every 5 seconds (adjust as needed)

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

// check if all the input in registration form is filled
const checkValidity = (ev) =>{
    let all_filled = true;
    for(let i = 0; i < document.querySelectorAll('.reg_inputs').length; i++){
        if(ev.target.value === "" || ev.target.value === null || ev.target.value === undefined || ev.target.value === NaN){
            all_filled = false;
        }
    }

    if(all_filled){
        document.getElementById('sdn-register-btn').className = 'sdn-register-btn bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-4 h-full rounded w-2/4 sm:w-1/4'
    }else{
        document.getElementById('sdn-register-btn').className = 'sdn-register-btn bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-4 h-full rounded w-2/4 sm:w-1/4 pointer-events-none opacity-10'
    }
}

for(let i = 0; i < document.querySelectorAll('.reg_inputs').length; i++){
    document.querySelectorAll('.reg_inputs')[i].addEventListener('input' , () => checkValidity(event))
}