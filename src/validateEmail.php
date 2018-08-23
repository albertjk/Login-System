<?php
/** 
* Author: Albert Jozsa-Kiraly
* This PHP script gets the e-mail address from the $_REQUEST associative array,
* validates it, and checks if it is found on the users table in the database. 
*/

include('connect.php');
$d = mysqli_connect($host, $user, $password, $db);

if(!empty($_REQUEST['email'])) { 

	$email = $_REQUEST['email'];

	// Escape quotes and remove malicious HTML tags from the entered data.
	$email = mysqli_real_escape_string($d, $email);
	$email = strip_tags($email);

	// This regular expression matches a valid e-mail address.
	$emailRegex = "/^[a-zA-Z0-9_\.]+@[a-zA-Z0-9_]+\.[a-zA-Z0-9_\.]+$/";

	// Check whether the e-mail address matches the regular expression.
	if(preg_match($emailRegex, $email)) {		
		
		// If the e-mail address was valid,	check if it is already tied to an account in the database. 
		$sql_check_email = "select email from users where email = '$email'";
		$result = mysqli_query($d, $sql_check_email);
		
		if(!$result) {
			echo "Error: ".mysql_error;
		} 
		else {
			$row = mysqli_fetch_row($result);
			
			/* If the e-mail address was not found in the database, let the user know 
			that it is valid and can be used for their account creation. */
			if(!$row) {
				echo "The e-mail address is valid and can be used";
			} 
			// Otherwise, let the user know that the entered e-mail address was already used.
			else {
				echo "This e-mail address was already used";
			}	
		}
	} 
	else if(preg_match($emailRegex, $email) == 0){		
		echo "The e-mail address is not in the valid format";
	}
}
else {
	echo 'Text field is empty';
}
?>