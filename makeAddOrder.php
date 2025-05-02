<?php
session_start();
include('includes/db.php');

$studentUnivId = $_POST["studentId"]; // Original Value

$studentId = null; // Student ID

$bookId = $_POST["bookId"];
$orderDate = $_POST["orderDate"];
$returnDate = $_POST["returnDate"];


$queryBookName = "SELECT name FROM `books` WHERE `book_id` = '$bookId';";
$resultBookName = mysqli_query($conn, $queryBookName);
$rowBookName = mysqli_fetch_assoc($resultBookName);
if ($rowBookName) {
    $bookName = $rowBookName['name'];
}

$errorsOfAddOrder = array("studentId" => "", "orderDate" => "", "returnDate" => "");

// Validation
if ($studentUnivId === "" || !ctype_digit($studentUnivId)) {
    $errorsOfAddOrder["studentId"] = "Student University Id is required and should be Number!";
} else {
    // Check if university ID exists in DB
    $queryStudentId = "SELECT `student_id` FROM `students` WHERE `university_number` = '$studentUnivId'";
    $resultStudentId = mysqli_query($conn, $queryStudentId);

    if ($resultStudentId && mysqli_num_rows($resultStudentId) === 1) {
        $studentId = mysqli_fetch_assoc($resultStudentId)['student_id'];
    } else {
        $errorsOfAddOrder["studentId"] = "Student University Id invalid!";
    }
}

if ($orderDate === "") {
    $errorsOfAddOrder["orderDate"] = "Order Date is required!";
}

if ($returnDate === "") {
    $errorsOfAddOrder["returnDate"] = "Return Date is required!";
}

if ($orderDate !== "" && $returnDate !== "") {
    if (strtotime($orderDate) > strtotime($returnDate)) {
        $errorsOfAddOrder["returnDate"] = "Order Date should be before Return Date!";
    }
}

// If there are validation errors, redirect back with enterd data and errors (stored in session)
if (array_filter($errorsOfAddOrder)) {
    $_SESSION['errorsOfAddOrder'] = $errorsOfAddOrder; // array
    $_SESSION['oldOrderData'] = array(
        "studentId" => $studentUnivId,
        "bookId" => $bookId,
        "orderDate" => $orderDate,
        "returnDate" => $returnDate
    );
    header("Location: AddOrder.php?bookid=" . $bookId . "&bookname=" . $bookName);
    exit();
}

// بعد التحقق، حولهم إلى أرقام
$studentId = (int)$studentId;
$bookId = (int)$bookId;

// If no errors, proceed to insert data into the database
$query = "INSERT INTO `orders`(`student_id`, `book_id`, `order_date`, `return_date`) VALUES (?,?,?,?)";

// Prepare and bind parameters to prevent SQL injection
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "iiss", $studentId, $bookId, $orderDate, $returnDate); // 's' for string, 'i' for integer


$queryNumOfBook = "SELECT `quantity_available` FROM `books` WHERE `book_id` = $bookId ";
$resultOfNumOfBook = mysqli_query($conn, $queryNumOfBook);
$rowOfNumOfBook = mysqli_fetch_assoc($resultOfNumOfBook);

if ($rowOfNumOfBook && $rowOfNumOfBook['quantity_available'] > 0) {
    // Execute the query and handle success or failure ==> add order
    if (mysqli_stmt_execute($stmt)) {
        $newNum = $rowOfNumOfBook['quantity_available'] - 1;
        $newQuantity  = "UPDATE `books` SET `quantity_available`=$newNum WHERE `book_id` = $bookId";
        $resultOfnewQuantity  = mysqli_query($conn, $newQuantity);

        if ($resultOfnewQuantity) {
            setcookie("messageOfSuccess", "The order has been added and quantity updated.", time() + 15);
        } else {
            setcookie("messageOfFailed", "Order added, but failed to update book quantity.", time() + 15);
        }
    } else {
        setcookie("messageOfFailed", "An error occurred while adding the order: " . mysqli_error($conn), time() + 15);
    }
} else {
    setcookie("messageOfFailed", "The book is out of stock.", time() + 15);
}

// Close the statement and connection
mysqli_stmt_close($stmt);
mysqli_close($conn);

// Redirect to the AddAuthor page
header("Location: AddOrder.php?bookid=" . $bookId . "&bookname=" . $bookName);
exit();
