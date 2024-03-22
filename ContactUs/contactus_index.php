<?php
	error_reporting(E_ALL ^ E_NOTICE);
	
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title> Contact Us Page </title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">-->
	<link rel="stylesheet" href="https://bootswatch.com/3/united/bootstrap.min.css">	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>	
</head>
<body>
<?php include '../master.php';?>
<!--config.php is needed for SQL calls-->
<?php require_once('../protected/config.php');?>

	<div class="container text-center">
	<h1>Welcome to the Contact Us page</h1>
	
	<br/>
	<?php 
		try{
			# set up connection string reading from config.php
			$connString = "mysql:host=" . DBHOST . ";dbname=" . DBNAME;
			$pdo = new PDO($connString, DBUSER, DBPASS);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	
			
			# instantiate the new class
			$my = new MySQLCall();
			
			# execute the select query
			echo $my->executeSelectQuery($pdo, "SELECT * FROM tbluser");
			
			# execute insert record query
			/*
			$insertSQL = 'insert into tbluser(id,email,firstName, lastName, address, phone, salary, group_id)'; 
			$insertSQL = $insertSQL . 'VALUES (613, "paxtonpt@home.edu", "Paxton", "Porenta", "17500 25th Ave NE, Seattle, WA 98271", "29063233433, 20.00, 0)';
			echo $my->executeQuery($pdo, $insertSQL);
			*/
			
			# execute the class object query
			/*
			$users = $my->fetchUsers($pdo, "SELECT * FROM tbluser");
			foreach ($users as $user){
				echo "Value is $user->email <br/>";
			}
			*/
	

			# close connection
			$pdo = null;		
		}
		catch (PDOException $e){
			die($e->getMessage());
		}		
	?>
	</div>
	
	
	
<?php include '../footer.php';?>
</body>
</html>