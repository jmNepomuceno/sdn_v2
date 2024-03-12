let userIsActive = true;
let inactivityTimer;

    function handleUserActivity() {
        userIsActive = true;
        // Additional code to handle user activity if needed
        console.log('active')
    }

    function handleUserInactivity() {
        console.log('inactive')
        userIsActive = false;
        // Additional code to handle user inactivity if needed
        // $.ajax({
        //     url: 'php/fetch_interval.php',
        //     method: "POST",
        //     data : {
        //         from_where : 'incoming'
        //     },
        //     success: function(response) {
        //         global_stopwatch_all = []
        //         global_hpercode_all = []

        //         populateTbody(response)

        //         const pencil_elements = document.querySelectorAll('.pencil-btn');
        //             pencil_elements.forEach(function(element, index) {
        //             element.addEventListener('click', function() {
        //                 console.log('den')
        //                 ajax_method(index)
        //             });
        //         });
        //     }
        // });
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