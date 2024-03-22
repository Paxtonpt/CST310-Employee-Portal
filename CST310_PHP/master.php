<?php
	error_reporting(E_ALL ^ E_NOTICE);
	ini_set('session.use_only_cookies','1');
	session_start();
	require_once('protected/config.php');
	if( isset($_SESSION['username'])) {
		echo "Welcome: " . $_SESSION['username'];		
	}
	
		# Class for tblUser
		/*
			Connolly, R., & Hoar, R. (2018).Fundamentals of web development(2nd ed.). Pearson.
			13.2.1 Defining Classes, page 594.
		*/
		class tblUser {
			public $id;
			public $email;
			public $pass;
			public $firstName;
			public $lastName;
			public $address;
			public $phone;
			public $salary;
			public $ssn;
			//public $group_id;

			public function clearValues(){
				$this->id = null;
				$this->email = null;
				$this->pass = null;
				$this->firstName = null;
				$this->lastName = null;
				$this->address = null;
				$this->phone = null;
				$this->salary = null;
				$this->ssn = null;
			}
		}
		
		class MySQLCall{
			# will execute SQL queries against the database
			public function executeSelectQuery($con, $sql){
				# perform the select query
				$result = $con->query($sql);
				
				# create bootstrap table
				$table = '<table class="table table-hover"><thead><tr class="bg-primary">';
				$table = $table . '<th class="text-center" scope="col">EMAIL</th>';
				$table = $table . '<th scope="col">FIRST</th>';
				$table = $table . '<th scope="col">LAST</th>';
				$table = $table . '<th scope="col">ADDRESS</th>';
				$table = $table . '<th scope="col">PHONE</th>';
				$table = $table . '<th scope="col">SALARY</th>';
				$table = $table . '<th scope="col">SSN</th>';
				$table = $table . '</tr></thead><tbody>';
				
				#loop through query results by row
				while($row = $result->fetch()){
					$table = $table . '<tr><th class="text-center" scope="row">' . $row['email'] . '</th>';
					$table = $table . "<td>" . $row['firstName'] . "</td>";
					$table = $table . "<td>" . $row['lastName'] . "</td>";
					$table = $table . "<td>" . $row['address'] . "</td>";
					$table = $table . "<td>" . $row['phone'] . "</td>";
					$table = $table . "<td>" . $row['salary'] . "</td>";
					$table = $table . "<td>" . $row['ssn'] . "</td></tr>";
				}
				
				# complete the bootstrap table
				$table = $table . '</tbody>';	
				
				return $table;
			}
			
			# will insert data to the databases, update data in the database, or delete data from the database
			public function executeQuery($con, $sql){
				# Capture the first word in the SQL string
				$firstString = explode(' ',trim($sql))[0]; 
				/*
					codaddict [SCREEN NAME] (2010), 
					How to get the first word of a sentence in PHP?. 
					stackoverflow.com. 
					Retrieved from https://stackoverflow.com/questions/2476789/how-to-get-the-first-word-of-a-sentence-in-php				
				*/
				
				# Execute SQL
				$count = $con->exec($sql);
				
				# returns a PDOStatement object
				return "<p>" . $firstString . " " . $count . " rows</p>";
			}

			public function fetchUsers($con, $sql){
				# perform the select query
				$result = $con->query($sql);
				
				#create an empty array
				$users = array();
				
				#loop through query results and append users to the array
				while($user = $result->fetchObject('tblUser')){
					$users[] = $user;
				}
				
				return $users;
			}

			public function fetchUserIDs($con, $sql){
				# perform the select query
				$result = $con->query($sql);
				
				#create an empty array
				$userIDs = array();
				
				#loop through query results and append users to the array
				while($user = $result->fetchObject('tblUser')){
					$userIDs[] = $user->user_id;
				}
				
				return $userIDs;
			}						
		}

		function fetchUserId($con, $sql){
			# perform the select Query
			$result = $con->query($sql);
			return $result;
		}
		
		/*
			geeksforgeeks. (2021). How to select and upload multiple files with HTML and PHP, using HTTP POST? Retreived from https://www.geeksforgeeks.org/how-to-select-and-upload-multiple-files-with-html-and-php-using-http-post/
		*/		
		function uploadFiles(
			$files, 
			$upload_dir = 'uploads'.DIRECTORY_SEPARATOR, //default to upload directory
			$allowed_types = array('jpg', 'png', 'jpeg', 'gif'), //default is picture files only
			$maxsize = 2 * 1024 * 1024) //default 2MB max size
		{
		    if (is_array($files['name'])){
			    # Multi-file upload
			    // Checks if user sent an empty form
			    if(!empty(array_filter($files['name']))) {
			        // Loop through each file in files[] array
			        foreach ($files['tmp_name'] as $key => $value) {
			            $file_tmpname = $files['tmp_name'][$key];
			            $file_name = $files['name'][$key];
			            $file_size = $files['size'][$key];
			            $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
			 
			            // Set upload file path
			            $filepath = $upload_dir.$file_name;
			 
			            // Check file type is allowed or not
			            if(in_array(strtolower($file_ext), $allowed_types)) {
			 
			                // Verify file size - 2MB max
			                if ($file_size > $maxsize)        
			                    echo "Error: File size is larger than the allowed limit.";
			 
			                // If file with name already exist then append time in
			                // front of name of the file to avoid overwriting of file
			                if(file_exists($filepath)) {
			                    $filepath = $upload_dir.time().$file_name;
			                     
			                    if( move_uploaded_file($file_tmpname, $filepath)) {
			                        echo "{$file_name} successfully uploaded <br />";
			                    }
			                    else {                    
			                        echo "Error uploading {$file_name} <br />";
			                    }
			                }
			                else {
			                 
			                    if( move_uploaded_file($file_tmpname, $filepath)) {
			                        echo "{$file_name} successfully uploaded <br />";
			                    }
			                    else {                    
			                        echo "Error uploading {$file_name} <br />";
			                    }
			                }
			            }
			            else {
			                 
			                // If file extension not valid
			                echo "Error uploading {$file_name} ";
			                echo "({$file_ext} file type is not allowed)<br / >";
			            }
			    	}
		        }
		    }
		    else{
	    		# Single file upload
	            $file_tmpname = $files['tmp_name'];
	            $file_name = $files['name'];
	            $file_size = $files['size'];
	            $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
	 
	            // Set upload file path
	            $filepath = $upload_dir.$file_name;
	 
	            // Check file type is allowed or not
	            if(in_array(strtolower($file_ext), $allowed_types)) {
	 
	                // Verify file size - 2MB max
	                if ($file_size > $maxsize)        
	                    echo "Error: File size is larger than the allowed limit.";
	 
	                // If file with name already exist then append time in
	                // front of name of the file to avoid overwriting of file
	                if(file_exists($filepath)) {
	                    $filepath = $upload_dir.time().$file_name;
	                     
	                    if( move_uploaded_file($file_tmpname, $filepath)) {
	                        echo "{$file_name} successfully uploaded <br />";
	                    }
	                    else {                    
	                        echo "Error uploading {$file_name} <br />";
	                    }
	                }
	                else {
	                 
	                    if( move_uploaded_file($file_tmpname, $filepath)) {
	                        echo "{$file_name} successfully uploaded <br />";
	                    }
	                    else {                    
	                        echo "Error uploading {$file_name} <br />";
	                    }
	                }
                }
	            else {
	                // If file extension not valid
	                echo "Error uploading {$file_name} ";
	                echo "({$file_ext} file type is not allowed)<br / >";
	            }	                
		    }
		}
		
		function hasElevatedPermissions($username){
			#create an empty array
			$roles = array();

			# set up connection string reading from config.php
			$connString = "mysql:host=" . DBHOST . ";dbname=" . DBNAME;
			$pdo = new PDO($connString, DBUSER, DBPASS);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			# prepare the SQL call to find the user based on user input
			$sql = "SELECT r.role_name FROM roles as r
					JOIN user_role as ur ON ur.role_id = r.role_id
					JOIN tbluser as tb ON tb.user_id = ur.user_id
					WHERE tb.email='" . $username . "'";
			$result = $pdo->query($sql);
			#loop through query results and append users to the array

			#loop through query results and append users to the array
			while($user = $result->fetchObject()){
				$roles[] = $user->role_name;
			}

			return $roles;	
		}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>		
</head>
<body>
<div class="jumbotron">
	<div class="container text-center">
		<h1>My Website</h1>
		
	</div>
</div>
<nav class="navbar navbar-default">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
		</div>
		<div class="collapse navbar-collapse" id="myNavbar">
			<ul class="nav navbar-nav">
				<li class="active"><a href="/EmployeePortal/Home/"><span class="glyphicon glyphicon-home"></span> Home</a></li>
				<li><a href="#"><span class="glyphicon glyphicon-exclamation-sign"></span> AboutUs</a></li>
				<?php 
					if( isset($_SESSION['username']))
					{
						if( in_array("employee", hasElevatedPermissions($_SESSION['username']))){
							echo '<li><a href="/EmployeePortal/ContactUs/"><span class="glyphicon glyphicon-earphone"></span> Employee-Page</a></li>';
						}						
					}
				?>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<?php
					//ini_set('session.use_only_cookies','1');
					//session_start();
					
					if( isset($_SESSION['username']))
					{
						if( in_array("administrator", hasElevatedPermissions($_SESSION['username']))){
							echo '<li><a href="/EmployeePortal/profile.php"><span class="glyphicon glyphicon-cog"></span> Admin</a></li>';
						}
						echo '<li><a href="/EmployeePortal/profile.php"><span class="glyphicon glyphicon-briefcase"></span> Profile</a></li>';
						echo '<li><a href="/EmployeePortal/index.php?Logout=1"><span class="glyphicon glyphicon-off"></span> Logout</a></li>';
					}
					else
					{
						echo '<li><a href="/EmployeePortal/Login/"><span class="glyphicon glyphicon-user"></span> Login</a></li>';
						echo '<li><a href="/EmployeePortal/Registration/"><span class="glyphicon glyphicon-pencil"></span> Registration</a></li>';
					}
				?>
			</ul>
		</div>
	</div>
</nav>
</body>
</html>