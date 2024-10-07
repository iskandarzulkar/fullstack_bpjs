
<!DOCTYPE html>
<html>
<head>
    <title>PDF Report</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <style type="text/css">
		table tr td,
		table tr th{
			font-size: 9pt;
		}
	</style>
</head>
<body>
    <h1>Data Report</h1>
    <table  class='table table-bordered'>
        <thead>
            <tr>
                <td>No</td>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <!-- Add more columns as needed -->
            </tr>
        </thead>
        <tbody>
            @php
                $no=1;
            @endphp
            @foreach($data as $record)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $record['id'] }}</td>
                    <td>{{ $record['name'] }}</td>
                    <td>{{ $record['email'] }}</td>
                    <!-- Add more columns as needed -->
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
