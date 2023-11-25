<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
define('DB_SERVER', 'localhost');
define('DB_NAME', 'sparu');

define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');

/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Check connection
if($link === false){
	die("ERROR: Não foi possível conectar. " . mysqli_connect_error());
}
?>