const initialPieChartLoad = () =>{

const add = document.querySelectorAll('.add');
const totals_elements =document.querySelectorAll('.sumCell');
let totals_array = [];
// const totals2 =document.getElementById('sumCell2');

let total = 0;
// let total2 = 0;

let sum_index = 0


for(let i = 0; i < add.length; i++){ 

  total += parseInt(add[i].textContent); 
  // console.log(i + 1)

  if((i + 1) % 9 === 0 ){
    totals_elements[sum_index].textContent = total; 
    sum_index += 1
    total = 0
  }

  // console.log(totals_elements[0].textContent);
  // console.log(totals_elements[i].textContent);
  // totals_value += parseInt(totals_elements[0].textContent);
}

for(let i = 0; i < totals_elements.length; i++){ 
  totals_array.push(totals_elements[i].textContent)
}

//FOr Primary
// Get references to all rows in the table.
var rows = document.querySelectorAll('table tr');

var totalSum = 0;

// Specify an array of column indices you want to sum (e.g., columns 0 and 2).
var columnIndicesToSum = [1 , 4, 7];

// console.log(columnIndicesToSum);
// Iterate through the rows and accumulate the values of the specified columns.
rows.forEach(function(row) {
    // Get references to the cells in the current row.
    var cells = row.querySelectorAll('td');

    var rowSum = 0;

    // Iterate through the specified columns and accumulate their values.
    columnIndicesToSum.forEach(function(columnIndex) {
        // Check if the specified column exists in the current row.
        if (columnIndex < cells.length) {
            // Extract the value from the specified column.
            var cellValue = parseInt(cells[columnIndex].textContent, 10);

            // Check if the value is a valid number before adding.
            if (!isNaN(cellValue)) {
                rowSum += cellValue;
            }
        }
    });

    // Add the rowSum to the sum of the first column.
    totalSum += rowSum;
});


//For Secondary
// Get references to all rows in the table.
var rows2 = document.querySelectorAll('table tr');

var totalSum2 = 0;

// Specify an array of column indices you want to sum (e.g., columns 0 and 2).
var columnIndicesToSum2 = [2 , 5, 8];

// console.log(columnIndicesToSum2);
// Iterate through the rows and accumulate the values of the specified columns.
rows2.forEach(function(row) {
    // Get references to the cells in the current row.
    var cells = row.querySelectorAll('td');

    var rowSum2 = 0;

    // Iterate through the specified columns and accumulate their values.
    columnIndicesToSum2.forEach(function(columnIndex2) {
        // Check if the specified column exists in the current row.
        if (columnIndex2 < cells.length) {
            // Extract the value from the specified column.
            var cellValue2 = parseInt(cells[columnIndex2].textContent, 10);

            // Check if the value is a valid number before adding.
            if (!isNaN(cellValue2)) {
                rowSum2 += cellValue2;
            }
        }
    });

    // Add the rowSum to the sum of the first column.
    totalSum2 += rowSum2;
});



//For Tertiary
// Get references to all rows in the table.
var rows3 = document.querySelectorAll('table tr');

var totalSum3 = 0;

// Specify an array of column indices you want to sum (e.g., columns 0 and 2).
var columnIndicesToSum3 = [3 , 6, 9];

// console.log(columnIndicesToSum3);
// Iterate through the rows and accumulate the values of the specified columns.
rows3.forEach(function(row) {
    // Get references to the cells in the current row.
    var cells = row.querySelectorAll('td');

    var rowSum3 = 0;

    // Iterate through the specified columns and accumulate their values.
    columnIndicesToSum3.forEach(function(columnIndex3) {
        // Check if the specified column exists in the current row.
        if (columnIndex3 < cells.length) {
            // Extract the value from the specified column.
            var cellValue3 = parseInt(cells[columnIndex3].textContent, 10);

            // Check if the value is a valid number before adding.
            if (!isNaN(cellValue3)) {
                rowSum3 += cellValue3;
            }
        }
    });

    // Add the rowSum to the sum of the first column.
    totalSum3 += rowSum3;
});



//For Tertiary
// Get references to all rows in the table.
var heads = document.querySelectorAll('table tr');

var totalSum4 = 0;

// Specify an array of column indices you want to sum (e.g., columns 0 and 2).
var columnIndicesToSum4 = [1 , 2, 3];

// console.log(columnIndicesToSum4);
// Iterate through the rows and accumulate the values of the specified columns.
heads.forEach(function(row) {
    // Get references to the cells in the current row.
    var cells = row.querySelectorAll('td');

    var rowSum4 = 0;

    // Iterate through the specified columns and accumulate their values.
    columnIndicesToSum4.forEach(function(columnIndex4) {
        // Check if the specified column exists in the current row.
        if (columnIndex4 < cells.length) {
            // Extract the value from the specified column.
            var cellValue4 = parseInt(cells[columnIndex4].textContent, 10);

            // Check if the value is a valid number before adding.
            if (!isNaN(cellValue4)) {
                rowSum4 += cellValue4;
            }
        }
    });

    // Add the rowSum to the sum of the first column.
    totalSum4 += rowSum4;
});



//For Tertiary
// Get references to all rows in the table.
var heads2 = document.querySelectorAll('table tr');

var totalSum5 = 0;

// Specify an array of column indices you want to sum (e.g., columns 0 and 2).
var columnIndicesToSum5 = [4 , 5, 6];

// console.log(columnIndicesToSum5);
// Iterate through the rows and accumulate the values of the specified columns.
heads2.forEach(function(row) {
    // Get references to the cells in the current row.
    var cells = row.querySelectorAll('td');

    var rowSum5 = 0;

    // Iterate through the specified columns and accumulate their values.
    columnIndicesToSum5.forEach(function(columnIndex5) {
        // Check if the specified column exists in the current row.
        if (columnIndex5 < cells.length) {
            // Extract the value from the specified column.
            var cellValue5 = parseInt(cells[columnIndex5].textContent, 10);

            // Check if the value is a valid number before adding.
            if (!isNaN(cellValue5)) {
                rowSum5 += cellValue5;
            }
        }
    });

    // Add the rowSum to the sum of the first column.
    totalSum5 += rowSum5;
});


//For Tertiary
// Get references to all rows in the table.
var heads3 = document.querySelectorAll('table tr');

var totalSum6 = 0;

// Specify an array of column indices you want to sum (e.g., columns 0 and 2).
var columnIndicesToSum6 = [7 , 8, 9];

// console.log(columnIndicesToSum6);
// Iterate through the rows and accumulate the values of the specified columns.
heads3.forEach(function(row) {
    // Get references to the cells in the current row.
    var cells = row.querySelectorAll('td');

    var rowSum6 = 0;

    // Iterate through the specified columns and accumulate their values.
    columnIndicesToSum6.forEach(function(columnIndex6) {
        // Check if the specified column exists in the current row.
        if (columnIndex6 < cells.length) {
            // Extract the value from the specified column.
            var cellValue6 = parseInt(cells[columnIndex6].textContent, 10);

            // Check if the value is a valid number before adding.
            if (!isNaN(cellValue6)) {
                rowSum6 += cellValue6;
            }
        }
    });

    // Add the rowSum to the sum of the first column.
    totalSum6 += rowSum6;
});




const data = {
    labels: ["Primary - " +totalSum, "Secondary - " +totalSum2, "Tertiary - " + totalSum3, ],
    datasets: [
      { 
        data: [totalSum, totalSum2, totalSum3], 
        backgroundColor: [
          "#FF6B6B",
          "#68D391",
          "#4E67E1",
       
        ]
      }

      
    ]
  };



  // Get the canvas element and create the chart
  const ctx = document.getElementById("myPieChart").getContext("2d");
  const myPieChart = new Chart(ctx, {
    type: 'pie',
    data: data,
    options: {
        responsive: true,
        legend: {
          display: false, // Disable default legend
        },
        plugins: {
          legend: {
            display: true,
            position: "right",
            labels: {
                usePointStyle: true,
              generateLabels: function (chart) {
                return chart.data.labels
                  .map(function (label, i) {
                    if (chart.data.datasets[0].data[i] !== 0) {
                      return {
                        text: label,
                        pointStyle: "circle",
                        fillStyle: chart.data.datasets[0].backgroundColor[i],
                        strokeStyle: "transparent",
                        lineWidth: 0,
                        hidden: false,
                        index: i,
                        lineCap: "round",
                        lineDash: [0],
                        lineDashOffset: 0,
                        lineJoin: "round",
                        radius: 5, // Adjust the circle size as needed
                        formatter: (value, context) => {
                          // Display the data value on the segment
                          return value;
                        },
                      };
                    }
                  })
                  .filter(Boolean); // Filter out undefined items (labels with data 0)
              }
            }
          }
        }
      }
  });

  const data2 = {
    labels: ["ER - " +totalSum4, "OB - " +totalSum5, "OPD - " + totalSum6, ],
    datasets: [
      {
        data: [totalSum4, totalSum5, totalSum6],
        backgroundColor: [
          "#FF6B6B",
          "#68D391",
          "#4E67E1"
        ]
      }
    ]
  };

  const ctx2 = document.getElementById("myPieChart2").getContext("2d");
  const myPieChart2 = new Chart(ctx2, {
    type: 'pie',
    data: data2,
    options: {
        responsive: true,
        legend: {
          display: false, // Disable default legend
        },
        plugins: {
          legend: {
            display: true,
            position: "right",
            labels: {
                usePointStyle: true,
              generateLabels: function (chart) {
                return chart.data.labels
                  .map(function (label, i) {
                    if (chart.data.datasets[0].data[i] !== 0) {
                      return {
                        text: label,
                        pointStyle: "circle",
                        fillStyle: chart.data.datasets[0].backgroundColor[i],
                        strokeStyle: "transparent",
                        lineWidth: 0,
                        hidden: false,
                        index: i,
                        lineCap: "round",
                        lineDash: [0],
                        lineDashOffset: 0,
                        lineJoin: "round",
                        radius: 20, // Adjust the circle size as needed
                      };
                    }
                  })
                  .filter(Boolean); // Filter out undefined items (labels with data 0)
              }
            }
          }
        }
      }
  });


  let temp_data3 = []
  for(let i = 0; i < document.querySelectorAll('.referred-by-class').length; i++){
    temp_data3.push(document.querySelectorAll('.referred-by-class')[i].value)
  }

  let temp_datasets = []
  for(let i = 0; i < document.querySelectorAll('.sumCell').length; i++){
    // console.log(document.querySelectorAll('.sumCell')[i])
    // temp_data3.push(document.querySelectorAll('.sumCell')[i].value)
    temp_datasets.push(document.querySelectorAll('.sumCell')[i].textContent)
  }
  // rasi
  const data3 = {
    labels: temp_data3,
    datasets: [
      {
        data: temp_datasets,
        backgroundColor: [
          "#FF6B6B",
          "#68D391",
          "#4E67E1",
          "#cc0099",
          "#00ff99",
          "#660033",
          "#003300",
          "#ff3300",
          "#000099",
         
        ] 
      }
    ]
  };

  const ctx3 = document.getElementById("myPieChart3").getContext("2d");
  const myPieChart3 = new Chart(ctx3, {
    type: 'pie',
    data: data3,
    options: {
        responsive: true, 
        legend: {
          display: false, // Disable default legend
        },
        plugins: {
          legend: {
            display: true,
            position: "right",
            labels: {
                usePointStyle: true,
              generateLabels: function (chart) {
                return chart.data.labels
                  .map(function (label, i) {
                    if (chart.data.datasets[0].data[i] !== 0) {
                      return {
                        text: label,
                        pointStyle: "circle",
                        fillStyle: chart.data.datasets[0].backgroundColor[i],
                        strokeStyle: "transparent",
                        lineWidth: 0,
                        hidden: false,
                        index: i,
                        lineCap: "round",
                        lineDash: [0],
                        lineDashOffset: 0,
                        lineJoin: "round",
                        radius:0, // Adjust the circle size as needed
                        radiusPadding: 5,
                      };
                    }
                  })
                  .filter(Boolean); // Filter out undefined items (labels with data 0)
              }
            }
          }
        }
      }
  });
}

initialPieChartLoad();
 
  
const date = document.getElementById('date');
const currentDate = new Date();
const hours = currentDate.getHours();
const minutes = currentDate.getMinutes();
const ampm = hours >= 12 ? 'PM' : 'AM';
const formattedHours = (hours % 12) || 12; // Convert 0 to 12
const formattedMinutes = minutes.toString().padStart(2, '0');

const formattedDate = `${currentDate.getFullYear()}-${(currentDate.getMonth() + 1).toString().padStart(2, '0')}-${currentDate.getDate().toString().padStart(2, '0')} ${formattedHours}:${formattedMinutes}:${currentDate.getSeconds().toString().padStart(2, '0')} ${ampm}`;

// console.log(formattedDate);

const originalDate = formattedDate;
const parsedDate = new Date(originalDate);

const options = {
  year: 'numeric',
  month: 'long',
  day: 'numeric',
  hour: 'numeric',
  minute: 'numeric',
  second: 'numeric',
  hour12: true,
};

const formattedDate_word = parsedDate.toLocaleString('en-US', options);

date.textContent = "As of " + formattedDate_word;
 
  const month = document.getElementById('month');
  const currentmonth = new Date();
  const months = [
    "January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December"
  ];
  const monthName = months[currentDate.getMonth()];
  
  // console.log(`Current month: ${monthName}`);

  month.textContent = monthName + " " + currentmonth.getFullYear();




  button1 = document.getElementById('filter-date-btn');

   // Replace 'yourTableId' with the actual ID of your table

  // Function to add a new row with cells based on the content of the previous row
  function addRowBasedOnPreviousCells() {


    var table = document.getElementById('tablet');
      // Create a new row
      var newRow = table.insertRow();
  
      // Get the index of the last row
      var rowIndex = table.rows.length - 1;
  
      // Iterate over the cells of the previous row
      var previousRow = table.rows[rowIndex - 1]; // Get the previous row
      for (var i = 0; i < previousRow.cells.length; i++) {
          // Create a new cell for each cell in the previous row
          var newCell = newRow.insertCell();                                                                                                                                                                                                                                                                                                                                                                                                                                       
      }
  }
  
  button1.addEventListener('click',addRowBasedOnPreviousCells);


// ************************************************************************** 
//  ME ME ME ME ME ME ME
$(document).ready(function(){
  $('#total-processed-refer').text($('#total-processed-refer-inp').val())
  
  const playAudio = () =>{
    let audio = document.getElementById("notif-sound")
    audio.muted = false;
    audio.play().catch(function(error){
        'Error playing audio: ' , error
    })
  }

  $('#notif-div').on('click' , function(event){
    if ($('#notif-sub-div').hasClass('hidden')) {
      $('#notif-sub-div').removeClass('hidden');
  } else {
      $('#notif-sub-div').addClass('hidden');
  }
})

$('#notif-sub-div').on('click' , function(event){
    if($('#notif-span').val() === 0){
        $('#notif-circle').addClass('hidden')
        document.getElementById("notif-sound").pause();
        document.getElementById("notif-sound").currentTime = 0;
    }else{
        window.location.href = "http://192.168.42.222:8035/main.php?loadContent=php/incoming_form.php"

        // window.location.pathname = "/newpage.html";
        current_page = "incoming_page"
        $('#current-page-input').val(current_page)
        $('#notif-sub-div').addClass('hidden')
    }
})

  $('#history-log-btn').on('click' , function(event){
    event.preventDefault();
    console.log('here')
    window.location.href = "../php/history_log.php";
  })

  const loadContent = (url) => {
    $.ajax({
        url:url,
        success: function(response){
            // console.log(response)
            $('#container').html(response);
        }
    })
  }

  
  function fetchMySQLData() {
    $.ajax({
      url: '../php/fetch_interval.php',
      method: "POST",
      data : {
          from_where : 'bell'
      },
      success: function(response) {
          response = JSON.parse(response);  
          // console.log(response);
          // console.log('pot')

          $('#notif-span').text(response.length);
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
          
          fetch_timer = setTimeout(fetchMySQLData, 5000);
      }
  });
  }

  fetchMySQLData(); 

    $('#side-bar-mobile-btn').on('click' , function(event){
      document.querySelector('#side-bar-div').classList.toggle('hidden');
    })

  $('#logout-btn').on('click' , function(event){
    event.preventDefault(); // Prevent the default behavior (navigating to the link)
    console.log('den')

    $('#modal-title-main').text('Warning')
    // $('#modal-body').text('Are you sure you want to logout?')
    $('#ok-modal-btn-main').text('No')

    $('#yes-modal-btn-main').text('Yes');
    $('#yes-modal-btn-main').removeClass('hidden')

    $('#myModal-main').modal('show');
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

    $.ajax({
        url: '../php/save_process_time.php',
        data : {  
            what: 'save',
            date : final_date,
            sub_what: 'logout'
        },
        method: "POST",
        success: function(response) {
            // response = JSON.parse(response);  
            console.log(response , " here")
            window.location.href = "http://192.168.42.222:8035/index.php" 
        }
    });
})

  $('#nav-account-div').on('click' , function(event){
    event.preventDefault();
    document.querySelector('#nav-drop-account-div').classList.toggle('hidden');
  })

  $('#dashboard-incoming-btn').on('click' , function(event){
    event.preventDefault();
    window.location.href = "../php/dashboard_incoming.php";
  })

  $('#dashboard-outgoing-btn').on('click' , function(event){
      event.preventDefault();
      window.location.href = "../php/dashboard_outgoing.php";
  })

  $('#sdn-title-h1').on('click' , function(event){
    event.preventDefault();
    window.location.href = "../main.php";
  })

  $('#incoming-sub-div-id').on('click' , function(event){
    event.preventDefault();
    window.location.href = "../main.php";
  })

  $('#filter-date-btn').on('click' , function(event){
    event.preventDefault();
    console.log('here')

    const data = {
      from_date : $('#from-date-inp').val(),
      to_date : $('#to-date-inp').val(),
      where : 'incoming'
    }

    console.log(data)
    
    $.ajax({
      url: '../php/filter_date_incoming.php',
      method: "POST",
      data : data,
      success: function(response) { 
        response = JSON.parse(response);
        console.log(response)

        $('#total-processed-refer').text(response.totalReferrals)
        $('#average-reception-id').text(response.averageDuration_reception)
        $('#average-approve-id').text(response.averageDuration_approval)
        $('#average-total-id').text(response.averageDuration_total)
        $('#fastest-id').text(response.fastest_response_final)
        $('#slowest-id').text(response.slowest_response_final)
      }
    });


    // populate table
    $.ajax({
      url: '../php/filter_date_table_incoming.php',
      method: "POST",
      data : data,
      success: function(response) {
        console.log(response)
        document.getElementById('tbody-class').innerHTML = response

        let chart1 = Chart.getChart('myPieChart');
        if (chart1) {
            chart1.destroy();
        }

        // Destroy chart with ID 'myPieChart2' if it exists
        let chart2 = Chart.getChart('myPieChart2');
        if (chart2) {
            chart2.destroy();
        }

        // Destroy chart with ID 'myPieChart3' if it exists
        let chart3 = Chart.getChart('myPieChart3');
        if (chart3) {
            chart3.destroy();
        }

        initialPieChartLoad();

      }
    });

  })

  // Get the timer element
  let recep_time = document.getElementById('average-reception-id').textContent
  let approve_time = document.getElementById('average-approve-id').textContent
  let total_time = document.getElementById('average-total-id').textContent
  let fastest_time = document.getElementById('fastest-id').textContent
  let slowest_time = document.getElementById('slowest-id').textContent

  // Get the initial time in seconds
  var initialTime = getTimeInSeconds('00:00:01');

  // Set the initial time
  setTimer(initialTime);

  // Start the timer
  setInterval(function() {
      // Increment the time by 1 second
      initialTime++;

      // Update the timer display
      setTimer(initialTime , "reception");
      setTimer(initialTime , "approve");
      setTimer(initialTime , "total");
      setTimer(initialTime , "fastest");
      setTimer(initialTime , "slowest");
  }, 5);

  // Function to convert HH:MM:SS format to seconds
  function getTimeInSeconds(timeString) {
      var timeArray = timeString.split(':');
      return parseInt(timeArray[0]) * 3600 + parseInt(timeArray[1]) * 60 + parseInt(timeArray[2]);
  }

  // Function to set the timer display
  function setTimer(seconds, elem) {
      // let real_time = getTimeInSeconds('00:01:38')
      let real_time;  
      // = getTimeInSeconds('00:05:31')
      switch(elem){
        case 'reception': real_time = getTimeInSeconds(recep_time); break;
        case 'approve': real_time = getTimeInSeconds(approve_time); break; 
        case 'total': real_time = getTimeInSeconds(total_time); break;
        case 'fastest': real_time = getTimeInSeconds(fastest_time); break;
        case 'slowest': real_time = getTimeInSeconds(slowest_time); break;
      }


      if(real_time >= seconds){
        var hours = Math.floor(seconds / 3600);
        var minutes = Math.floor((seconds % 3600) / 60);
        var remainingSeconds = seconds % 60;

        // Format the time as HH:MM:SS
        var formattedTime = pad(hours) + ':' + pad(minutes) + ':' + pad(remainingSeconds);
        
        // Update the timer element content
        // document.getElementById('average-reception-id').textContent = formattedTime;
        switch(elem){
          case 'reception': document.getElementById('average-reception-id').textContent = formattedTime;; break;
          case 'approve':document.getElementById('average-approve-id').textContent = formattedTime;; break;
          case 'total': document.getElementById('average-total-id').textContent = formattedTime; break;
          case 'fastest': document.getElementById('fastest-id').textContent = formattedTime;; break;
          case 'slowest': document.getElementById('slowest-id').textContent = formattedTime;; break;
        }
      }else{
        clearInterval()
      }

  }

  // Function to pad single-digit numbers with a leading zero
  function pad(number) {
      return (number < 10) ? '0' + number : number;
  }

})


