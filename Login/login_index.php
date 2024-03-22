<?php
	error_reporting(E_ALL ^ E_NOTICE);

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title> Login Page </title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<body>


<?php include '../master.php';?>
	<div class="container text-center">
		<h1>Welcome to the Login page</h1>
	</div>
	
	<div class="container">
		<form role="form" method="post" action="/EmployeePortal/Login/index.php">
			<div class="form-group">
				<label for="email">Email Address</label>
				<input type="email" class="form-control" name="uname" id="username">
			</div>
			<div class="form-group">
				<label for="pwd">Password</label>
				<input type="password" class="form-control" name="pwd" id="pwd">
			</div>
			<button type="submit" class="btn btn-primary">Submit</button>
		</form>	

		<?php 
			# config.php is needed for SQL calls
			require_once('../protected/config.php');

			if (isset($_POST['uname']) && isset($_POST['pwd'])){
				try{
					# set up connection string reading from config.php
					$connString = "mysql:host=" . DBHOST . ";dbname=" . DBNAME;
					$pdo = new PDO($connString, DBUSER, DBPASS);
					$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

					# prepare the SQL call to find the user based on user input
					$sql = "SELECT email FROM tbluser WHERE email='" . $_POST['uname'] . "' and pass= MD5('" . $_POST['pwd'] . "')";
					$result = fetchUserId($pdo, $sql);

					# if there's a record...
					if ($result->rowCount() == 1){
						// add 1 day to the current time for expiry time
						$expiryTime = time()+60*60*24;

						// create a persistent cookie
						$name = "email";
						$value =  $_POST['uname'];
						setcookie($name, $value, $expiryTime);

						if( !isset($_COOKIE['email'])){
							// no valid cookie found
						}
						else{
							# success! user is in the database!
							echo "the user name retreived from the cookie is: ";
							echo $_COOKIE['email'];

							# set the superglobal veriable for the website
							$_SESSION['username'] = $_COOKIE['email'];

							# refresh the browser can redirect user to the profile page
							header("Refresh:0, url=../profile.php");
						}				
					}
					else{
						echo "<br/> Email does not exists! Please register!";
					}
				}
				catch (PDOException $e){
					die($e->getMessage());
				}						
			}	
		?>		
	</div>

<?php require_once '../footer.php';?>
</body>
</html>