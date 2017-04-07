<?php 

	$conf = ["db" =>
					[
						"server"   =>  "localhost",
						"database" =>   "attendance",
						"user"     =>   "root",
						"password" =>   "123456"
					]				

	];

@mysql_connect($conf["db"]["server"], $conf["db"]["user"],$conf["db"]["password"]) or die (mysql_error());
mysql_select_db($conf["db"]["database"]) or die (mysql_error()) ;

$form_row = [
				'id'       => '',
				'name'     => '',
				'department'     => '',
];

if(array_key_exists('action', $_GET) && $_GET['action'] == 'edit')
{
	$id = mysql_real_escape_string($_GET['id']);

	if(!empty($_POST))
	{
		$name     =   mysql_real_escape_string($_POST['name']);

		mysql_query('UPDATE `semester` set `name` = "' . $name . '" WHERE `id`= ' . $id);

		header('Location: http://localhost/Attandence/Semester.php');
	}

	$rsa = mysql_query('SELECT * FROM `semester` WHERE `id`='  . $id);
	$form_row = mysql_fetch_assoc($rsa);
}

if(array_key_exists('action', $_GET) && $_GET['action'] == 'delete')
{
	$id = mysql_real_escape_string($_GET['id']);

	mysql_query('DELETE FROM `semester` WHERE `id` = ' . $id) or die (mysql_error());

	header('Location: http://localhost/Attandence/Semester.php');
}


if(!empty($_POST) && !array_key_exists('action', $_GET))
{
	$id     = mysql_real_escape_string($_POST['id']);
	$name   = mysql_real_escape_string($_POST['name']);
	$department_id   = mysql_real_escape_string($_POST['department']);

	if(empty($id))
	{
		$id = 'Null';
	}
	else
	{
		$id = '"' . $id . '"';
	}

	$sql = 'INSERT INTO `semester` VALUES(' . $id .  ', "' . $name . '", "' . $department_id . '")';
	mysql_query($sql) or die(mysql_error());
	
	header('Location: http://localhost/Attandence/Semester.php');
}

$sql = 'SELECT `semester`.`id`, `semester`.`name`, `department`.`name` as `department` FROM `semester`, `department` WHERE `semester`.`department_id` = `department`.`id`';
$rsa = mysql_query($sql);

$grid = [];

while($row = mysql_fetch_assoc($rsa))
{
	$grid[] = $row;
}


$sql = 'SELECT * FROM `department`';
$rsa = mysql_query($sql);

$departments = [];

while($row = mysql_fetch_assoc($rsa))
{
	$departments[] = $row;
}


 ?><!DOCTYPE html>
<html>
<head>
	<title>Attendance System</title>
	<link rel="stylesheet" type="text/css" href="style.css">

</head>
<body>

<?php include "navigation.php"; ?>
		
	<form action="" method= "post">
		<table>

				<tr>
						<td>Id</td>
						<td><input type="text" name="id" value="<?php  echo $form_row['id']; ?>"></td>
				</tr>

				<tr>
						<td>Name</td>
						<td><input type="text" name="name" value="<?php echo htmlentities($form_row['name']); ?>"></td>
				</tr>

				<tr>
						<td>Department</td>
						<td>
							<select name="department">
							<option value="-1"> -- SELECT DEPARTMENT -- </option>
								<?php
								foreach ($departments as $department)
								{
									$id = $department['id'];
									$name = $department['name'];

									echo '<option value="' . $id . '">' . $name . '</option>';
								}
								?>
							</select>
						</td>
				</tr>

				<tr>
						<td><input type="submit" value = "<?php echo empty($form_row['id']) ? 'Submit' : 'Update'; ?>" ></td>
				</tr>
		</table>
	</form>

	<table width="500" align="center">
		<tr>
			<th>Id</th>
			<th>Name</th>
			<th>Department</th>
			<th>Actions</th>
		</tr>

		<?php foreach ($grid as $row) {
			
			echo '<tr>' . "\r\n";
			echo '<td>' . $row['id']      . '</td>' . "\r\n";
			echo '<td>' . $row['name']    . '</td>' . "\r\n";
			echo '<td>' . $row['department']    . '</td>' . "\r\n";
			echo  ' <td><a href="?action=edit&id=' . $row['id'] . '"> Edit </a> | <a href= "?action=delete&id=' . $row['id'] . '"> Delete </a> </td> ' . "\r\n";
			echo '</tr>' . "\r\n";

		} ?>

	</table>

</body>
</html>