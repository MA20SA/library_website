<?php
session_start();
include('includes/db.php');

// Retrieve POST data
$name = $_POST["name"];
$birthdate = $_POST["birthdate"];
$age = $_POST["age"];
$nationality = $_POST["nationality"];

// Initialize error messages
$errorsOfAddAuthor = array("name" => "", "birthdate" => "", "age" => "", "nationality" => "");

// Validation
if ($name === "" || strlen($name) > 15) {
    $errorsOfAddAuthor["name"] = "Name is required and should be less than 15 characters!";
}

if ($birthdate === "") {
    $errorsOfAddAuthor["birthdate"] = "Birthdate is required!";
}

if ($age === "" || (int)$age < 18) {
    $errorsOfAddAuthor["age"] = "Age is required and should be at least 18!";
}

if ($nationality === "" || strlen($nationality) > 25) {
    $errorsOfAddAuthor["nationality"] = "Nationality is required and should be less than 25 characters!";
}

// If there are validation errors, redirect back with enterd data and errors (stored in session)
if (array_filter($errorsOfAddAuthor)) {
    $_SESSION['errorsOfAddAuthor'] = $errorsOfAddAuthor; // array
    $_SESSION['oldAuthorData'] = array(
        "name" => $name,
        "birthdate" => $birthdate,
        "age" => $age,
        "nationality" => $nationality
    );
    header("Location: AddAuthor.php");
    exit();
}

// If no errors, proceed to insert data into the database
$query = "INSERT INTO authors(`name`, `birthdate`, `age`, `nationality`) VALUES (?, ?, ?, ?)";

// Prepare and bind parameters to prevent SQL injection
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ssis", $name, $birthdate, $age, $nationality); // 's' for string, 'i' for integer

// Execute the query and handle success or failure
if (mysqli_stmt_execute($stmt)) {
    setcookie("messageOfSuccess", "The author has been added.", time() + 15);
} else {
    setcookie("messageOfFailed", "An error occurred while adding the author: " . mysqli_error($conn), time() + 15);
}

// Close the statement and connection
mysqli_stmt_close($stmt);
mysqli_close($conn);

// Redirect to the AddAuthor page
header("Location: AddAuthor.php");
exit();
?>
