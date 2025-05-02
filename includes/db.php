<?php

$host = 'sql12.freesqldatabase.com'; // Replace with your actual host
$username = 'sql12776427';             // Replace with your database username
$password = 'Please wait';         // Replace with your password
$database = 'sql12776427';         // Replace with your database name

// $conn = mysqli_connect('localhost','root','','library');

$conn = mysqli_connect($host, $username, $password, $database);

// when $conn = false(there's error) this block happend --> !F = T
if(!$conn){
    // die() To prevent script execution
    die("there's error in connection with db: " . mysqli_connect_error());
}

?>