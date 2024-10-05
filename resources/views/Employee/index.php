
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List 200.000.000 Display Data</title>

    <link href="assets/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- <link href='assets/css/bootstrap.css' rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> -->
    <script src='assets/js/bootstrap.bundle.min.js' integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
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
                    <td><?=$no;?></div>
                    <td>Loading...</div>
                    <td>Loading...</div>
                    <td>Loading...</div>
                    <td>Loading...</div>
                    <td>Loading...</div>
                </tr>
            </tbody>
        </table>

        <!-- <div id="loading" style="display:none;">Loading...</div> -->

    </div>


    <script>
        <?= $no   = 1; ?>
        let page  = 1;
        let no    = 1;
        function loadData() {
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
                            <?= $no++ ?>
                            $('#data-body').append('<tr>' +
                            '<td>' + $no++ + '</td>' + 
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