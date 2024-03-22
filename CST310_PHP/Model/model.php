<?php
	class Employee{
		public $email;
		public $pass;
		public $firstName;
		public $lastName;
		public $address;
		public $phone;
		public $salary;
		public $ssn;

		function __construct($record){
			$this->email = $record['email'];
			$this->pass = $record['pass'];
			$this->firstName = $record['firstName'];
			$this->lastName = $record['lastName'];
			$this->address = $record['address'];
			$this->phone = $record['phone'];
			$this->salary = $record['salary'];
			$this->ssn = $record['ssn'];
		}

	}
?>