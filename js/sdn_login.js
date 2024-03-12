$(document).ready(function(){
    $('#sdn-login-main-btn').on('click' , function(event){
        event.preventDefault();

        const sdn_username = document.querySelector('#sdn-username').value
        const sdn_password = document.querySelector('#sdn-password').value

        const data = {
            sdn_username: sdn_username,
            sdn_password: sdn_password
        }

        $.ajax({
            url: './php/sdn_login.php',
            method: "POST",
            data:data,
            success: function(response){
                // console.log(response)

                //using query parameters
                // window.location.href = response + "?user=" + encodeURIComponent("admin")

                // using session storage
                // sessionStorage.setItem("user", "admin");
                window.location.href = response
            }
        })
    })
})