<?php
	error_reporting(E_ALL ^ E_NOTICE);

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title> Home Page </title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<body>


<?php require '../master.php';?>

	<div class="container text-center">
	<h1>Welcome to the Home page</h1>
	</div>

<?php 

	// add 1 day to the current time for expiry time
	$expiryTime = time()+60*60*24;
	// create a persistent cookie
	$name = "Logout";
	$value =  "0";
	setcookie($name, $value, $expiryTime);
	echo $name . " | " . $value . " | " . $expiryTime;
	echo "<br/>";

	if( !isset($_COOKIE['Logout'])){
		// no valid cookie found
	}
	else{
		echo "the user name retreived from the cookie is: ";
		echo $_COOKIE['Logout'];
		if ($_COOKIE['Logout'] == 0){
			unset($_SESSION['username']);
			header("Refresh:0, url=/EmployeePortal/Login/");
			exit;
		}
	}		

?>	
	
<?php require_once '../footer.php';?>
</body>
</html>