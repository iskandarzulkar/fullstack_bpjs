<!DOCTYPE html>
<html>
<head>
	<title>PDF BIG DATA</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
	<style type="text/css">
		table tr td,
		table tr th{
			font-size: 9pt;
		}
	</style>
	<center>
		<h5>PDF Data 200.000.000</h4>
	</center>
 
	<table class='table table-bordered'>
		<thead>
			<tr>
				<th>No</th>
				<th>ID</th>
				<th>Firstname</th>
				<th>Lastname</th>
				<th>Email</th>
				<th>Address</th>
				<th>Department</th>
			</tr>
		</thead>
		<tbody>
            <?php 
                $i= 1;
                
                foreach ($data as $emp) {
            ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= $emp->id ?></td>
                    <td><?= $emp->firstname ?></td>
                    <td><?= $emp->lastname ?></td>
                    <td><?= $emp->email ?></td>
                    <td><?= $emp->address ?></td>
                    <td><?= $emp->name ?></td>
                </tr>
            <?php
                }
            ?>
		</tbody>
	</table>
 
</body>
</html>