<?php
/** 
* Author: Albert Jozsa-Kiraly
* This PHP script is executed when the user is logged in. A user session 
* is started, the user is welcomed, and is provided with a link to log out.
* If the user was not logged in already, they are provdied with a link to do so.
*/

session_start();

// This stylesheet is needed to style the generated page
echo '<link rel="stylesheet" href="style.css"/>';

if(!empty($_SESSION)) {		
	
	echo '<h2>Successful login!</h2>';
	echo '<h2>Welcome, '.$_SESSION['firstName'].'!</h2>';				
	echo '<a href="logout.php">Logout</a>';
} 
else {
	echo '<p>Please log in first<p>';
	echo '<a href="login.html">Login</a>';
}
?>