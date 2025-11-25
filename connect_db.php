<?php
# Connects the user to the SQL database
# Connect  on 'localhost'.
$link = mysqli_connect('localhost','root','','websitedb'); 

if (!$link) { 
	# Throw error if link cannot be established
	die('Could not connect to MySQL: ' . mysqli_error()); 
}
#echo '<a style="color:tomato">Connection OK</a>';  
?> 