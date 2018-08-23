<?php
/** 
* Author: Albert Jozsa-Kiraly
* This PHP script is executed when the user clicks on the Login button 
* on the Login page. The script gets the user's e-mail address and password, validates them,
* and checks if the e-mail is present in the database. If so, it verifies the password, and 
* redirects the user to the Welcome page. If something was incorrect, a message is returned.
*/

session_start();

include('connect.php');
$d = mysqli_connect($host, $user, $password, $db);

if(!empty($_POST['email']) && !empty($_POST['password'])) {

	$email = $_POST['email'];
	$password = $_POST['password'];

	// Escape quotes and remove malicious HTML tags from the entered data.
	$email = mysqli_real_escape_string($d, $email);
	$password = mysqli_real_escape_string($d, $password);
	$email = strip_tags($email);
	$password = strip_tags($password);

	// This regular expression matches a valid e-mail address.
	$emailRegex = "/^[a-zA-Z0-9_\.]+@[a-zA-Z0-9_]+\.[a-zA-Z0-9_\.]+$/";

	/* The password must be at least 8 characters long, including at least one number and one letter.
	It can only contain letters and numbers. This regular expression matches a valid password. */
	$passwordRegex = "/(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}/";

	/* Check if the e-mail and the password match their corresponding regular expressions. 
	The password must be at least 8 characters long. */  
	if(preg_match($emailRegex, $email) && preg_match($passwordRegex, $password) && strlen($password) >= 8) {

		// Check if the account is already in the database by searching for the entered e-mail address.
		$sql_check_email = "select * from users where email = '$email';";
		$result = mysqli_query($d, $sql_check_email);

		if(!$result) {
			echo "Error: ".mysql_error;
		} 
		else {
			$row = mysqli_fetch_row($result);

			// If the e-mail address was not found in the database, notify the user that they are not logged in.
			if(!$row) {
				echo "E-mail address not found in the database.";
			}
			/* If the e-mail address was found in the database, verify the password.
			Hash it, and check if it matches the user's password in the database. */
			else {
				// If the passwords match, the user is logged in.
				if(password_verify($password, $row[3])) {
					
					/* Set the session variables. The first one is the e-mail address because it is unique to each user.
					The second is the first name, because it will be printed on the screen when the user has successfully logged in. */
					$_SESSION['email'] = $email;
					$_SESSION['firstName'] = $row[0];
					
					// Redirect to the generated welcome page
					header('Location: welcome.php');
					exit();
				}
				else {
					echo "Invalid password. You are not logged in.";
				}
			}
		}
	}
	// If the entered data in the log in form were not valid, let the user know.
	else {
		if(preg_match($emailRegex, $email) == 0) {
			echo "The e-mail is not in the valid format.<br>";
		}
		if(preg_match($passwordRegex, $password) == 0) {
			echo "The password is not valid. It can only contain letters and numbers.<br>";
		}
		if(strlen($password) < 8) {
			echo "The password is too short. It must be at least 8 characters long.";
		}
	}
}
else {
	echo "Values are missing.";
}
?>