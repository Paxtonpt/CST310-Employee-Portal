<?php
	error_reporting(E_ALL ^ E_NOTICE);
	// Check if form was submitted
		
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title> Profile Page </title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">-->
	<link rel="stylesheet" href="https://bootswatch.com/3/united/bootstrap.min.css">	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<body>
	<?php include 'master.php';?>
	<?php require_once('protected/config.php');?>
	<?php include 'Model/model.php';?>

	<?php
		try{
			# set up connection string reading from config.php
			$connString = "mysql:host=" . DBHOST . ";dbname=" . DBNAME;
			$pdo = new PDO($connString, DBUSER, DBPASS);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			# set up SQL query
			$sql = "SELECT * FROM tbluser WHERE email = '" . $_SESSION['username'] . "'";
			$result = $pdo->query($sql);

			# fetch a record into an object of type Employee
			while ($row = $result->fetch() ){
				# call employee class
				$e = new Employee($row);
			}
		}
		catch (PDOException $e){
			die($e->getMessage());
		}
	?>	
	<div class="container">
		<div class="row">
			<div class="form-group col-md-3">
			    <form action="profile.php" method="POST" enctype="multipart/form-data">
			        <h2>Upload Files</h2>
					<p>
			            Select files to upload:
						<!-- Single File Upload -->
			            <!--<input type='file' name='file1' id='file1'>-->

			            <!-- Multi-File Upload -->
			            <input type="file" name="files[]" multiple>
			            <br/>
			            <input type="submit" name="submit" value="Upload" >
			        </p>
			    </form>
		    </div>
			<div class="form-group col-md-9">
				<h2>Welcome to the Profile page</h2>
				<br/>
				<form>
					<div class="form-group row">
						<label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
						<div class="col-sm-10">
							<input type="email" readonly class="form-control" id="inputEmail" placeholder="Email" value="<?php echo $e->email;?>">
						</div>
					</div>
					<div class="form-group row">
						<label for="inputPassword" class="col-sm-2 col-form-label">Password</label>
						<div class="col-sm-10">
							<input type="text" readonly class="form-control" id="inputPassword" placeholder="Password" value="<?php echo $e->pass;?>">
						</div>
					</div>
					<div class="form-group row">
						<label for="inputFirstName" class="col-sm-2 col-form-label">First Name</label>
						<div class="col-sm-4">
							<input type="text" readonly class="form-control" id="inputFirstName" placeholder="First Name" value="<?php echo $e->firstName;?>">
						</div>
						<label for="inputLastName" class="col-sm-2 col-form-label">Last Name</label>
						<div class="col-sm-4">
							<input type="text" readonly class="form-control" id="inputLastName" placeholder="Last Name" value="<?php echo $e->lastName;?>">
						</div>						
					</div>
					<div class="form-group row">
						<label for="inputAddress" class="col-sm-2 col-form-label">Address</label>
						<div class="col-sm-10">
							<input type="text" readonly class="form-control" id="inputAddress" placeholder="Address" value="<?php echo $e->address;?>">
						</div>
					</div>		
					<div class="form-group row">
						<label for="inputPhone" class="col-sm-2 col-form-label">Phone</label>
						<div class="col-sm-3">
							<input type="text" readonly class="form-control" id="inputPhone" placeholder="Phone" value="<?php echo $e->phone;?>">
						</div>
						<label for="inputSalary" class="col-sm-1 col-form-label">Salary</label>
						<div class="col-sm-2">
							<input type="text" readonly class="form-control" id="inputSalary" placeholder="Salary" value="<?php echo $e->salary;?>">
						</div>						
						<label for="inputSSN" class="col-sm-1 col-form-label">SSN</label>
						<div class="col-sm-3">
							<input type="text" readonly class="form-control" id="inputSSN" placeholder="SSN" value="<?php echo $e->ssn;?>">
						</div>												
					</div>								
				</form>	
			</div>
		</div>
		<div class="row">
			<div class="form-group col-md-3">
				<h2>Upload Results</h2>
				<?php
					if(isset($_POST['submit'])) {
						
						/* Single File Upload */
						//$files = $_FILES['file1'];
						
						/* Multi-File Upload */
						$files = $_FILES['files'];

						uploadFiles($files);
						
					}
				    else {
				        echo "No files selected.";
				    }
				?>
			</div>		
		</div>
		<?php include 'footer.php';?>	    	
    </div>
</body>
</html>