<?php

// $conn = mysqli_connect('localhost','root','','library');

$servername = "sql12.freesqldatabase.com"; 
$username = "sql12777177";  
$password = "DxMyqIPFU7";   
$dbname = "sql12777177";    

$conn = mysqli_connect($servername, $username, $password, $dbname);

// when $conn = false(there's error) this block happend --> !F = T
if(!$conn){
    // die() To prevent script execution
    die("there's error in connection with db: " . mysqli_connect_error());
}

?>