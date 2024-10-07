
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List 200.000.000 Display Data</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        *{
            margin:0;
            padding:0;
        }
        .container{
            margin-top:10px;
        }
        .row{
            margin : 5px;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <a href="/export-pdf" class="btn btn-primary" target="_blank">CETAK PDF</a>
                <button type="button" class="btn btn-success" onClick="sampleGenerate()">Exm Merge</button>
                <!-- <a href="/generate-pdf" class="btn btn-primary" target="_blank">CETAK PDF 2</a> -->
            </div>
            <div class="col-md-3">
                <button type="button" class="btn btn-success" onClick="faker()">Faker Data</button>
            </div>
        </div>
        <div class="row">
            <div id="progress" class="mt-3" style="display: none;">
                <div class="progress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;" id="progress-bar"></div>
                </div>
                <span id="progress-text"></span>
            </div>
        </div>
        <div class="row">
            <table class="table table-bordered ">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">First Name</th>
                        <th scope="col">Last Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Address</th>
                        <th scope="col">Department</th>
                    </tr>
                </thead>
                <tbody id="data-body">
        
                </tbody>
                <tbody id="loading" style="display:none;">
                    <tr>
                        <td></td>
                        <td>Loading...</td>
                        <td>Loading...</td>
                        <td>Loading...</td>
                        <td>Loading...</td>
                        <td>Loading...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script>
        function faker()
        {
            $('#progress').show();
            $('#progress-bar').css('width', '0%');
            $('#progress-text').text('');

            let progressInterval = setInterval(function() {
                let currentWidth = parseInt($('#progress-bar').css('width')) / parseInt($('#progress').css('width')) * 100;
                if (currentWidth < 90) {
                    $('#progress-bar').css('width', (currentWidth + 10) + '%');
                } else {
                    clearInterval(progressInterval);
                }
            }, 100);

            $.ajax({
                url: '/create',
                method: 'get',
                data: $(this).serialize(),
                success: function(res) {
                    clearInterval(progressInterval);
                    $('#progress-bar').css('width', '100%');
                    $('#progress-text').text('Data FAKER added successfully!, with time : ' + res.time +' second');
                },
                error: function(xhr) {
                    clearInterval(progressInterval);
                    $('#progress-bar').css('width', '100%');
                    $('#progress-text').text('Error adding data.');
                },
                complete: function() {
                    setTimeout(function() {
                        $('#progress-bar').css('width', '0%');
                        $('#progress-text').text('');
                        $('#progress').hide();
                    }, 15000); 
                }
            });
                
        }

        function sampleGenerate()
        {
            $.ajax({
                url: '/generate-pdf',
                type: 'GET',
                success: function(response) {
                    console.log(response.message);
                    if (response.download_url) {
                        // Optionally, show a link to download the merged PDF
                        alert('PDFs Merged Successfully. Download the merged PDF.');
                    }
                },
                error: function(error) {
                    console.log('Error occurred:', error);
                }
            });

            pollProgressTest();
        }

        function pollProgressTest()
        {
            $('#progress').show();
            $('#progress-bar').css('width', '0%');
            $('#progress-text').text('');

            var interval = setInterval(function() {
                $.ajax({
                    url: '/progress-test',
                    type: 'GET',
                    success: function(data) {
                        console.log('Progress:', data.progress + '%');
                   
                        $('#progress-bar').css('width', data.progress + '%');
                        $('#progress-text').text('Progress: ' + data.progress + '%');

                        if (data.progress === 100) {
                            $('#progress').hide();
                            $('#progress-bar').css('width', '0%');
                            $('#progress-text').text('');
                            clearInterval(interval);
                        }
                    },
                    error: function(error) {
                        $('#progress').hide();
                        $('#progress-bar').css('width', '0%');
                        $('#progress-text').text('');
                        console.log('Error occurred while fetching progress:', error);
                    }
                });
            }, 10000);
        }


        let page  = 1;
        let no    = 1;
        function loadData(page) {
            $.ajax({
                url: '/data/load?page=' + page,
                type: 'GET',
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(data) {
                    console.log(data);
                    $('#loading').hide();
                    if (data.data.length > 0) {
                        data.data.forEach(function(item) {
                            $('#data-body').append('<tr>' +
                            '<td>' + no++ + '</td>' + 
                            '<td>' + item.firstname + '</td>' + 
                            '<td>' + item.lastname + '</td>' + 
                            '<td>' + item.email + '</td>' + 
                            '<td>' + item.address + '</td>' + 
                            '<td>' + item.name + '</td>' + 
                            '</tr>'); 
                        });
                        page++;
                    } else {
                        $('#loading').html('No more data'); 
                    }
                }
            });
        }

        loadData(page); 

        $(window).scroll(function() {
            if ($(window).scrollTop() + $(window).height() >= $(document).height()) {
                loadData();
            }
        });

        $(document).ready(function() {
            loadData(); 
        });
    </script>

</body>
</html>