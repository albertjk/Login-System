# Login System

## Description

This project dealt with the creation of a website that allows users to register and login to the site.
I created this login system in April 2018.

The Home page gives users the opportunity to log in with existing credentials or register to
create a new account. Login credentials involve an e-mail address (which serves as a username)
and a password. When a user registers, they are prompted to enter an e-mail address and choose their 
own password, which must consist of at least 8 characters, including at least one 
number and one letter. Other characters are not allowed.
The inputs are validated in real time. If an input is valid, the HTML is modified using JavaScript, 
so the input text box and the bullet points underneath describing input requirements are coloured green 
as the user finishes typing the input. Otherwise, the text box and the bullet points become red indicating
an error. 

Before a new user joins, the system checks if their e-mail address is already in use (i.e. it is in the database) and, if
it is, the system will not allow them to create a new account. Furthermore, the system checks that the
entered e-mail address is in a valid format. A person only has to provide a name, an e-mail and 
a password to join. The password is validated at both the client and the server and users are 
warned if they enter an email address which is already in use before they submit the form.

Additionally, users are offered a facility that sends them a new password if they have forgotten theirs
if they enter their e-mail address they registered with. After a user is logged in, the system
allows them to view a Welcome page which is only accessible following a recent login. This page
asks users who are not already logged in to do so. 

All user data are stored in a MySQL database. Passwords are encrypted. Data format validation is done
both at the client and at the server side. JavaScript is used at the client side and PHP on the server. 
AJAX is used to check whether an e-mail address is already in the database.

Technologies used: HTML, CSS, JavaScript, AJAX, PHP, MySQL

Additionally, the repository contains a project report which describes two use 
cases of the website, the system design, and the architecture. I reviewed my code in summer 2018 and removed some unnecessary comments.