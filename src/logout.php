<?php
/** 
* Author: Albert Jozsa-Kiraly
* This PHP script is executed when the user clicks on the Logout link on the Welcome page
* after being logged in. Here, the user session is destroyed and the user is redirected
* to the Home page.
*/

session_start();

session_destroy();

header('Location: home.html');
exit();
?>