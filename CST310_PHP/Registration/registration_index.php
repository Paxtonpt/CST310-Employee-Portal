<?php
	error_reporting(E_ALL ^ E_NOTICE);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title> Registration Page </title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">-->
	<link rel="stylesheet" href="https://bootswatch.com/3/united/bootstrap.min.css">	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>	
	<script type="text/javascript" src="../Scripts/CST310.js"></script>
	<script>
		$(document).ready(function(){
			// Password Validator from CST310.js
			confirmPassword ("inputEmail", "inputConfirmEmail", "Emails");
			confirmPassword ("inputPassword", "confirmPassword", "Passwords");

			// Initial submit button handler
			if($('#inputConfirmEmail').val().length > 0 && $('#inputFirstName').val().length > 0){
				$('#submitButton').prop("disabled",false);					
			}
			else{
				$('#submitButton').prop("disabled",true);					
			}

			// Submit button handler on key press (function)
			function submitBtnCheck(firstEle, secondEle){
				$(firstEle).on('keydown keyup change', function(){
					var char1Len = $(this).val().length;
					var char2Len = $(secondEle).val().length;
					
					if(char1Len > 0 && char2Len > 0){
						$('#submitButton').prop("disabled",false);
					}
					else{
						$('#submitButton').prop("disabled",true);	
					}
				});
			}

			// Submit button handler on key press
			submitBtnCheck('#inputFirstName', '#inputConfirmEmail');
			submitBtnCheck('#inputConfirmEmail', '#inputFirstName');
		})		
	</script>

</head>
<body>

<?php include '../master.php';?>
<!--config.php is needed for SQL calls-->
<?php require_once('../protected/config.php');?>

	<div class="container text-center">
		<h1>Welcome to the Registration page</h1>
		<?php 
			// page variables
			$emailErr = "";
			$firstNameErr = "";
			$lastNameErr = "";
			$passErr = "";
			$addressErr = "";
			$salaryErr = "";
			$ssnErr = "";
			$phoneErr = "";


			// global function to retrieve input element value
			function test_input($data) {
			  $data = trim($data);
			  $data = stripslashes($data);
			  $data = htmlspecialchars($data);
			  return $data;
			}

			// Insert class object into database
			try{
				# set up connection string reading from config.php
				$connString = "mysql:host=" . DBHOST . ";dbname=" . DBNAME;
				$pdo = new PDO($connString, DBUSER, DBPASS);
				$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				
				# instantiate the new class
				$my = new MySQLCall();
				
				# server side field validator function
				function serverSideFieldValidation($eleName, $errVar, $errText){
					if (!empty($_POST[$eleName])){
						return test_input($_POST[$eleName]);
					}
					else{
						$errVar = '<small style="color: red;">' . $errText . ' is required</small>';
					}					
				}							

				# validating required fields
				$firstName = serverSideFieldValidation("firstName", $firstNameErr, 'First Name');
				$lastName = serverSideFieldValidation("lastName", $lastNameErr, 'Last Name');
				$email = serverSideFieldValidation("confirm_email", $emailErr, 'Email');
				$pass = serverSideFieldValidation("confirm_password", $passErr, 'Password');
				$address = serverSideFieldValidation("address", $addressErr, 'Address');
				$salary = serverSideFieldValidation("salary", $salaryErr, 'Salary');
				$ssn = serverSideFieldValidation("ssn", $ssnErr, 'SSN');


				// phone input form retrieval
				$phoneVar = "";
				if (!empty($_POST["phone"])) {
					if (strlen(test_input($_POST["phone"])) != 10){
						$phoneErr = '<small style="color: red;">Phone Number must be 10 digits</small>';
					}
					else{
						$phoneVar = test_input($_POST["phone"]);
					}
				}

				# check required fields have a value before sql insert
				if( isset($firstName) && isset($email)){

					# create empty arrays to capture insert column keys and column values for sql insert
					$keys = array();
					$values = array();

					# add elements to array
					$props = array("email"=>$email, "firstName"=>$firstName, "lastName"=>(string)$lastName, "pass"=>$pass, "address"=>$address, "salary"=>$salary, "ssn"=>$ssn, "phone"=>$phoneVar);

					# loop through the elements and assign keys and values to the arry for items that have data
					foreach($props as $key => $val){
						if(isset($val)){
							$keys[] = $key; 
							switch($key){
								case "email":
									$values[] = '"' . $val . '"';	
									break;
								case "firstName":
									$values[] = '"' . $val . '"';	
									break;									
								case "lastName":
									$values[] = '"' . $val . '"';	
									break;											
								case "pass":
									$values[] = 'MD5("' . $val . '")';	
									break;	
								case "address":
									$values[] = '"' . $val . '"';	
									break;
								case "ssn":
									$values[] = '"' . $val . '"';	
									break;																										
								case "phone":
									$values[] = '"' . $val . '"';	
									break;											
								default:
									$values[] = $val;
							}
						}
					}
					# build SQL statement
					$sql = "insert into tbluser (" . join(",", $keys) . ") values (" . join(",", $values) . ');';
					echo $sql;

					# execute the insert query
					echo $my-> executeQuery($pdo, $sql);
				}

				# close the connection
				$pdo = null;
			}
			catch (PDOException $e){
				die($e->getMessage());
			}			
		?>
	</div>

	<form method="post" action="index.php">
		<div class="form-group col-md-12">
			<label class="control-label" for="inputEmail">Email Address</label><sup style="color:red;">*</sup>
			<input type="email" class="form-control" id="inputEmail" placeholder="name@example.com" name="email" value="<?php echo $email;?>">
		    <div class="control-group">
		    	<?php echo $emailErr;?>
		    </div>	
		</div>
		<div class="form-group col-md-6">
			<label for="inputFirstName">First Name</label><sup style="color:red;">*</sup>
			<input type="text" class="form-control" id="inputFirstName" placeholder="John" name="firstName" value="<?php echo $firstName;?>">
		    <div class="control-group">
		    	<?php echo $firstNameErr;?>
		    </div>				
		</div>		
		<div class="form-group col-md-6">
			<label for="inputFirstName">Last Name</label>
			<input type="text" class="form-control" id="inputLastName" placeholder="Doe" name="lastName" value="<?php echo $lastName;?>">
		</div>		
		<div class="form-group col-md-12">
			<label class="control-label" for="inputConfirmEmail">Confirm Email Address</label><sup style="color:red;">*</sup>
			<input type="email" class="form-control" id="inputConfirmEmail" placeholder="name@example.com" name="confirm_email" value="<?php echo $email;?>">
		    <div class="control-group">
		    	<?php echo $emailErr;?>
		    </div>	
		</div>		
		<div class="form-group col-md-6">
			<label for="inputPassword">Password</label>
			<input type="password" class="form-control" id="inputPassword" placeholder="Password" name="password" >
		</div>		
		<div class="form-group col-md-6">
			<label for="confirmPassword">Confirm Password</label>
			<input type="password" class="form-control" id="confirmPassword" placeholder="Password" name="confirm_password" >
		</div>
		<div class="form-group col-md-12">
			<label for="inputAddress">Address</label>
			<input type="text" class="form-control" id="inputAddress" placeholder="123 Sesame Street, New York, NY 12345" name="address" value="<?php echo $address;?>">
		</div>			
		<div class="form-group col-md-4">
			<label for="inputPhone">Phone Number</label>
			<input type="tel" class="form-control" id="inputPhone" placeholder="Just 10 Numbers" name="phone" pattern="[0-9]{10}" value="<?php echo $phoneVar;?>">
		    <div class="control-group">
		    	<?php echo $phoneErr;?>
		    </div>	
		</div>				
		<div class="form-group col-md-4">
			<label for="inputSalary">Salary</label>
			<input type="number" class="form-control" id="inputSalary" placeholder="###.##" name="salary" step=".01" pattern="^\\$?(([1-9](\\d*|\\d{0,2}(,\\d{3})*))|0)(\\.\\d{1,2})?$" value="<?php echo $salary;?>">
		</div>				
		<div class="form-group col-md-4">
			<label for="inputSSN">SSN</label>
			<input type="tel" class="form-control" id="inputSSN" placeholder="Just 9 Numbers" name="ssn" pattern="[0-9]{9}" value="<?php echo $ssn;?>">
		</div>								
		<div class="form-group col-md-12">					
	  		<button type="submit" class="btn btn-primary" id="submitButton">Submit</button>
	  	</div>
	</form>	
	
	
<?php require_once '../footer.php';?>
</body>
</html>