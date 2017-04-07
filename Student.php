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
				'gender'   => '',
				'address'  => '',
				'phone'    => '',
];


if(array_key_exists('action', $_GET) && $_GET['action'] == 'edit')
{
	$id = mysql_real_escape_string($_GET['id']);

	if(!empty($_POST))
	{
		$name     =   mysql_real_escape_string($_POST['name']);
		$gender   =   mysql_real_escape_string($_POST['gender']);
		$address  =   mysql_real_escape_string($_POST['address']);
		$phone    =   mysql_real_escape_string($_POST['phone']);

		mysql_query('UPDATE `student` set `name` = "' . $name . '", `gender` = "' . $gender . '", `address` = "' . $address . '", `phone` = "' . $phone . '" WHERE `id`= ' . $id);

		header('Location: http://localhost/Attandence/Student.php');
	}

	$rsa = mysql_query('SELECT * FROM `student` WHERE `id`='  . $id);
	$form_row = mysql_fetch_assoc($rsa);
}

if(array_key_exists('action', $_GET) && $_GET['action'] == 'delete')
{
	$id = mysql_real_escape_string($_GET['id']);

	mysql_query('DELETE FROM `student` WHERE `id` = ' . $id) or die (mysql_error());

	header('Location: http://localhost/Attandence/Student.php');
}


if(!empty($_POST) && !array_key_exists('action', $_GET))
{
	$id     = mysql_real_escape_string($_POST['id']);
	$name   = mysql_real_escape_string($_POST['name']);
	$gender = mysql_real_escape_string($_POST['gender']);
	$address= mysql_real_escape_string($_POST['address']);
	$phone  = mysql_real_escape_string($_POST['phone']);

	if(empty($id))
	{
		$id = 'Null';
	}

	else
	{
		$id = '"' . $id . '"';
	}

	$sql = 'INSERT INTO `student` VALUES(' . $id .  ', "' . $name . '", "' . $gender . '", "' . $address . '", "' . $phone . '")';
	mysql_query($sql) or die(mysql_error());
	
	header('Location: http://localhost/Attandence/Student.php');
}

$sql = 'SELECT * FROM `student`';
$rsa = mysql_query($sql);

$grid = [];

while($row = mysql_fetch_assoc($rsa))
{
	$grid[] = $row;
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

				<tr >
						<td >Gender</td>
						<td>
							<select name = "gender">  
								<option value = "" > -- SELECT GENDER -- </option>
								<option value = "Male"<?php echo $form_row['gender']=='Male' ? ' Selected=""' : ''; ?>>    Male </option>
								<option value = "Female"<?php echo $form_row['gender']=='Female' ? ' Selected=""' : ''; ?>> Female </option>
							</select>
						</td>
				</tr>

				<tr>
						<td>Address</td>
						<td><textarea name="address"><?php echo $form_row['address']; ?></textarea></td>
				</tr>
					
				<tr>
						<td>Phone</td>
						<td><input type="text" name="phone"   value="<?php echo $form_row['phone']; ?>"></td>
				</tr>

				<tr>
						<td><input type="submit" value = "<?php echo empty($form_row['id']) ? 'Submit' : 'Update'; ?>" ></td>
				</tr>
		</table>
	</form>

	<table border = "" width="500" align="center">
		<tr>
			<th>Id</th>
			<th>Name</th>
			<th>Gender</th>
			<th>Address</th>
			<th>Phone</th>
			<th>Actions</th>
		</tr>

		<?php foreach ($grid as $row) {
			
			echo '<tr>' . "\r\n";
			echo '<td>' . $row['id']      . '</td>' . "\r\n";
			echo '<td>' . $row['name']    . '</td>' . "\r\n";
			echo '<td>' . $row['gender']  . '</td>' . "\r\n";
			echo '<td>' . $row['address'] . '</td>' . "\r\n";
			echo '<td>' . $row['phone']   . '</td>' . "\r\n";
			echo  ' <td><a href="?action=edit&id=' . $row['id'] . '"> Edit </a><a href= "?action=delete&id=' . $row['id'] . '"> Delete </a> </td> ' . "\r\n";
			echo '</tr>' . "\r\n";

		} ?>

	</table>

</body>
</html>