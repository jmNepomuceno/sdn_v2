

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timer Example</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
</head>
<body>
    
    <table id="example" style="width: 100%; border:1px solid blue">
        <thead>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </thead>
    </table>
    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
    $(document).ready(function() {
        var data = [
            // Your data array here
        ];

        $.ajax({
            url: "./select.php",
            method: 'GET',
            dataType: 'JSON',
            success: function(data){
                console.log(data)
                $('#example').DataTable({
                    data: data,
                    columns: [
                        {
                            title: 'Reference No.',
                            render: function(data, type, row) {
                                return row.reference_num + '--' + row.index;
                            }
                        },
                        { data: 'pat_full_name', title: "Patient's Name" },
                        { data: 'type', title: 'Type' },
                        { data: 'referred_by', title: 'Agency' },
                        {
                            title: 'Agency',
                            render: function(data, type, row) {
                                return (
                                    'Referred by: ' + row.referred_by + '<br>' +
                                    'Landline: ' + row.landline_no + '<br>' +
                                    'Mobile: ' + row.mobile_no
                                );
                            }
                        },
                        { data: 'date_time', title: 'Date/Time' },
                        { data: 'stopwatch', title: 'Response Time' },
                        { data: 'status', title: 'Status' },
                        
                    ],
                    pageLength: 5,
                    responsive: true,
                    processing: true,
                    createdRow: function(row, data, dataIndex) {
                        if (data.style_tr) {
                            $(row).attr('style', data.style_tr);
                        }
                    }
                });
            }
        })
    });
    </script>
</body>
</html>