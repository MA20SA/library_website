<?php
$conn = mysqli_connect('localhost','root','','library');

// when $conn = false(there's error) this block happend --> !F = T
if(!$conn){
    // die() To prevent script execution
    die("there's error in connection with db: " . mysqli_connect_error());
}

?>