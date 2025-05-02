<?php
session_start();
include('includes/db.php');

// Retrieve POST data
$name = $_POST["name"];
$title = $_POST["title"];
$publishdate = $_POST["publishdate"];
$quantityavailable = $_POST["quantityavailable"];
$authorId = $_POST["authorId"];
$publisherId = $_POST["publisherId"];
$image = $_FILES["image"];

// Initialize error messages
$errorsOfAddBook = array("name" => "", "title" => "", "publishdate" => "", "quantityavailable" => "", "author" => "", "publisher" => "", "image" => "");

// Validation
if ($name === "" || strlen($name) > 25) {
    $errorsOfAddBook["name"] = "Name is required and should be less than 15 characters!";
}

if ($title === "" || strlen($title) > 20) {
    $errorsOfAddBook["title"] = "Title is required and should be less than 20 characters!";
}

if ($publishdate === "") {
    $errorsOfAddBook["publishdate"] = "Bublish Date is required!";
}

if ($quantityavailable === "" || (int)$quantityavailable < 0) {
    $errorsOfAddBook["quantityavailable"] = "Quantity Available is required and should be positive!";
}

if (empty($authorId)) {
    $errorsOfAddBook["author"] = "Author Name is required!";
}

if (empty($publisherId)) {
    $errorsOfAddBook["publisher"] = "Publisher Name is required!";
}

if (!isset($image) || $image['error'] !== UPLOAD_ERR_OK) {
    $errorsOfAddBook["image"] = "Image is required!";
}


// If there are validation errors, redirect back with enterd data and errors (stored in session)
if (array_filter($errorsOfAddBook)) {
    $_SESSION['errorsOfAddBook'] = $errorsOfAddBook; // array
    $_SESSION['oldBookData'] = array(
        "name" => $name,
        "title" => $title,
        "publishdate" => $publishdate,
        "quantityavailable" => $quantityavailable,
        "authorId" => $authorId,
        "publisherId" => $publisherId
    );    
    header("Location: AddBook.php");
    exit();
}

// If no errors, proceed to insert data into the database
$queryBook = "INSERT INTO `books`(`name`, `title`, `publish_date`, `quantity_available`, `author_id`, `publisher_id`)
          VALUES (?,?,?,?,?,?)";

// Prepare and bind parameters to prevent SQL injection
$stmtBook = mysqli_prepare($conn, $queryBook);
mysqli_stmt_bind_param($stmtBook, "sssiii", $name, $title, $publishdate, $quantityavailable, $authorId, $publisherId);


$queryImage = "INSERT INTO `books_images`(`book_id`, `path`, `size`) VALUES (?,?,?);";

$stmtImage = mysqli_prepare($conn, $queryImage);


// Execute the query and handle success or failure
if (mysqli_stmt_execute($stmtBook)) {

    // Get id of last book added
    $idOfLastAddedBookQuery = "SELECT `book_id` as lastBookID FROM `books` order by `book_id` DESC LIMIT 1;";
    $resultOfIdOfLastAddedBookQuery = mysqli_query($conn, $idOfLastAddedBookQuery);
    $row = mysqli_fetch_assoc($resultOfIdOfLastAddedBookQuery);
    $lastBookId = $row["lastBookID"];

    $imagePath = 'images/' . basename($image['name']);
    move_uploaded_file($image['tmp_name'], $imagePath);
    $imageSize = $image['size'];

    mysqli_stmt_bind_param($stmtImage, "isi", $lastBookId, $imagePath, $imageSize);

    if (!mysqli_stmt_execute($stmtImage)) {
        setcookie("messageOfFailed", "An error occurred while adding the Book image: " . mysqli_error($conn), time() + 15);
        header("Location: AddBook.php");
        exit;
    }
    setcookie("messageOfSuccess", "The Book has been added.", time() + 15);
} else {
    setcookie("messageOfFailed", "An error occurred while adding the Book: " . mysqli_error($conn), time() + 15);
}

// Close the statement and connection
mysqli_stmt_close($stmtBook);
mysqli_stmt_close($stmtImage);
mysqli_close($conn);

// Redirect to the AddAuthor page
header("Location: AddBook.php");
exit();
