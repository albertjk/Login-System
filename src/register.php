<?php
/** 
* Author: Albert Jozsa-Kiraly
* This PHP script is executed when the user clicks on the Register button on the Register page. The script gets 
* the user's first name, last name, e-mail address, and password, validates them, and checks if the e-mail is 
* present in the database. If it is not, the user data are added to the database table. Then, the user is asked 
* to log in. If an error occurred or something was incorrect, a message is returned.
*/

include('connect.php');
$d = mysqli_connect($host, $user, $password, $db);

if(!empty($_POST['firstName']) && !empty($_POST['lastName']) && !empty($_POST['email']) && !empty($_POST['password'])) {

	$firstName= $_POST['firstName'];
	$lastName = $_POST['lastName'];
	$email = $_POST['email'];
	$password = $_POST['password'];

	// Escape quotes and remove malicious HTML tags from the entered data.
	$firstName = mysqli_real_escape_string($d, $firstName);
	$lastName = mysqli_real_escape_string($d, $lastName);
	$email = mysqli_real_escape_string($d, $email);
	$password = mysqli_real_escape_string($d, $password);
	
	$firstName = strip_tags($firstName);
	$lastName = strip_tags($lastName);
	$email = strip_tags($email);
	$password = strip_tags($password);

	// This regular expression matches a valid name (maximum 40 characters long). A name cannot begin with whitespace.
	$nameRegex = "/^(?=.{1,40}$)[a-zA-Z]+(?:[-'\s][a-zA-Z]+)*$/";

	// This regular expression matches a valid e-mail address.
	$emailRegex = "/^[a-zA-Z0-9_\.]+@[a-zA-Z0-9_]+\.[a-zA-Z0-9_\.]+$/";

	/* The password must be at least 8 characters long, including at least one number and one letter.
	It can only contain letters and numbers. This regular expression matches a valid password. */
	$passwordRegex = "/(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}/";

	// This stylesheet is needed to style the generated page.
	echo '<link rel="stylesheet" href="style.css"/>';

	/* Check if the names, the e-mail, and the password match their corresponding regular expressions.
	The password must be at least 8 characters long. */
	if(preg_match($nameRegex, $firstName) && preg_match($nameRegex, $lastName) && preg_match($emailRegex, $email) && preg_match($passwordRegex, $password) && strlen($password) >= 8) {

		// Check if the account is already in the database by searching for the entered e-mail address.
		$sql_check_email = "select email from users where email = '$email'";
		$res = mysqli_query($d, $sql_check_email);

		if(!$res) {
			echo "Error: ".mysql_error;
		} 
		else {
			$row = mysqli_fetch_row($res);

			// If the e-mail was not found in the database, add the user to the database table.
			if(!$row) {

				/* One-way hashing of the password.
				Use the default hashing algorithm. */
				$password = password_hash($password, PASSWORD_DEFAULT);

				// SQL to add the new user's data to the users table in the database
				$sql_add = "insert into users(firstName, lastName, email, password) values (\"".$firstName."\",\"".
				$lastName."\", \"".$email."\", \"".$password."\");";
				$result = mysqli_query($d, $sql_add);

				if(!$result) {
					echo "Error: ".mysql_error;
				} 
				else {
					echo "<h2>Welcome among the users, $firstName!</h2>";
					echo "Your data is stored safely!<br>";					
					echo "<h2>Please log in to continue</h2>";
					echo '<a href="login.html">Login</a>';
				}
			}
			// If the e-mail was found in the database, notify the user, and do not add the user to the database.
			else {
				$found_email = $row[0];
				echo "The email $found_email is already in use. Please use a different one.";
			}
		}
	}
	else if(preg_match($emailRegex, $email) == 0) {
		echo "The e-mail is not in the valid format.<br>";
	}
	else {
		echo "The text fields contain invalid data.";
	}	
} 
else {
	echo "Values are missing.";
}
?>