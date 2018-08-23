<?php
/** 
* Author: Albert Jozsa-Kiraly
* This PHP script is executed when the user clicks on the Send new password button 
* on the Forgotten password page. The script gets the user's e-mail address, validates it,
* and checks if it is present in the database. If so, it generates a new random password,
* e-mails it to the user's e-mail address, and updates the previous password's hashed verison
* with the new password's hashed version in the database.
*/

include('connect.php');
$d = mysqli_connect($host, $user, $password, $db);

if(!empty($_POST['email'])) {

	$email = $_POST['email'];

	// Escape quotes and remove malicious HTML tags from the entered data.
	$email = mysqli_real_escape_string($d, $email);
	$email = strip_tags($email);

	// This regular expression matches a valid e-mail address.
	$emailRegex = "/^[a-zA-Z0-9_\.]+@[a-zA-Z0-9_]+\.[a-zA-Z0-9_\.]+$/";

	// This stylesheet is needed to style the generated page.
	echo '<link rel="stylesheet" href="style.css"/>';

	// Check if the entered e-mail address matches the regular expression.
	if(preg_match($emailRegex, $email)) {
		
		// Check if the e-mail address is in the database.
		$sql_check_email = "select * from users where email = '$email';";
		$result = mysqli_query($d, $sql_check_email);

		if(!$result) {
			echo "Error: ".mysql_error;
		} 
		else {
			$row = mysqli_fetch_row($result);
			
			// If the e-mail address was not found in the database, notify the user.
			if(!$row) {
				echo "E-mail address not found in the database.";
			}
			/* If the e-mail address was found in the database, generate a new password.
			Send the password to the user in an e-mail.	Overwrite the password entry in 
			the database with the new password's hashed version. */
			else {
				// The new password will be between 8 and 15 characters long. The length is randomly generated.					
				$newPassword = generateRandomPassword(mt_rand(8, 15));
				
				$firstName = $row[0];
				$lastName =  $row[1];				
				
				$message = "Hello, $firstName $lastName! Your new password for your account is: $newPassword";
				
				// Wordwrap is used if the line is longer than 70 characters
				$message = wordwrap($message, 70);
				
				// Send the e-mail
				mail($email, "New password", $message);
				
				/* One-way hashing of the password.
				The default hashing algorithm is used. */
				$newPassword = password_hash($newPassword, PASSWORD_DEFAULT);
				
				// Update the user's previous password with the new password in the users table.
				$sql_update = "update users set password = '$newPassword' where email = '$email';";
				$res = mysqli_query($d, $sql_update);
				
				if(!$res) {
					echo "Error: ".mysql_error;
				} 
				else {					
					echo "<p>A new password is sent to your e-mail address.</p>";					
					echo '<a href="home.html">Back to home page</a>';
				}	
			}	
		}
	}
	else {
		echo "The e-mail is not in the valid format";
	}
}
else {
	echo "Values are missing.";
}

/**
* This function takes in the desired password length as a parameter and generates
* a random password of this length consisting of numbers from 0 to 9, and lowercase (a-z) and 
* uppercase (A-Z) letters. Characters are appended to the random password string continuously 
* until the length is reached. Finally, the generated password is returned by the function.
*/
function generateRandomPassword($passwordLength) {
	$validCharacters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$randomPassword = '';
	for($i = 0; $i < $passwordLength; $i++) {
		$randomPassword .= $validCharacters[mt_rand(0, strlen($validCharacters) - 1)];
	}
	return $randomPassword;
}
?>