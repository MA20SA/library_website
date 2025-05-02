<?php
include 'includes/header.php';

$bookId = isset($_GET['bookid']) ? $_GET['bookid'] : null;
$bookName = isset($_GET['bookname']) ? $_GET['bookname'] : null;

session_start();

// Display success or failure message if available
if (isset($_COOKIE['messageOfSuccess'])) {
  echo '<div style="background-color: #d4edda; color: #155724; padding: 10px; margin: 15px; border: 1px solid #c3e6cb; border-radius: 5px;">';
  echo $_COOKIE['messageOfSuccess'];
  echo '</div>';
  unset($_COOKIE['messageOfSuccess']); // Clear the message after showing it
}

if (isset($_COOKIE['messageOfFailed'])) {
  echo '<div style="background-color: #f8d7da; color: #721c24; padding: 10px; margin: 15px; border: 1px solid #f5c6cb; border-radius: 5px;">';
  echo $_COOKIE['messageOfFailed'];
  echo '</div>';
  unset($_COOKIE['messageOfFailed']); // Clear the message after showing it
}

// Get errors
$errorsOfAddOrder = isset($_SESSION['errorsOfAddOrder']) ? $_SESSION['errorsOfAddOrder'] : array("studentId" => "", "orderDate" => "" , "returnDate"=>"");

// Get previous input values
$oldOrderData = isset($_SESSION['oldOrderData']) ? $_SESSION['oldOrderData'] : array("studentId" => "", "bookId" => "", "orderDate" => "" , "returnDate"=>"");

// After getting, clear the session
unset($_SESSION['errorsOfAddOrder']);
unset($_SESSION['oldOrderData']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Make Order</title>

  <!-- include css page -->
  <link rel="stylesheet" href="includes/CSS/style.css">

  <script>
    function getStudentName() {
      const studentId = document.getElementById("studentId").value;

      if (!studentId) {
        alert("يرجى إدخال رقم الطالب");
        return;
      }

      fetch('getStudentName.php?studentId=' + studentId)
        .then(response => response.text())
        .then(data => {
          document.getElementById("studentName").value = data.trim();
        })
        .catch(error => {
          console.error("خطأ:", error);
          document.getElementById("studentName").value = "حدث خطأ";
        });
    }
  </script>


</head>

<body>
  <h1 style="text-align: center;margin-top: 50px;text-decoration: underline;">Add Order >> <?php echo $bookName; ?> </h1>
  <form action="makeAddOrder.php" method="POST">

    <!-- for in label = id in input -->
    <div>
      <input style="width: 46%" type="number" min="1" name="studentId" id="studentId" placeholder="Student University Id" value="<?php echo htmlspecialchars($oldOrderData['studentId']); ?>">
      <button style="height: 35px;" type="button" onclick="getStudentName()">>></button>
      <input style="width: 45%;" type="text" id="studentName" disabled placeholder="Student Name, Department Name">
      <span style="color: red; font-size:12px;position:relative; top: -10px;"><?php echo $errorsOfAddOrder["studentId"] ?></span>
    </div>

    <div>
      <input style="width: 46%" name="bookId" id="bookId" hidden value="<?php echo $bookId; ?>">
    </div>

    <div>
      <label for="orderDate">Order Date:</label>
      <input type="date" name="orderDate" id="orderDate" value="<?php echo htmlspecialchars($oldOrderData['orderDate']); ?>">
      <span style="color: red;font-size:12px;position: relative; top: -10px;"><?php echo $errorsOfAddOrder["orderDate"] ?></span>
    </div>

    <div>
      <label for="returnDate">Return Date:</label>
      <input type="date" name="returnDate" id="returnDate" value="<?php echo htmlspecialchars($oldOrderData['returnDate']); ?>">
      <span style="color: red;font-size:12px;position: relative; top: -10px;"><?php echo $errorsOfAddOrder["returnDate"] ?></span>
    </div>



    <input type="submit" value="Add Order">

  </form>
</body>

</html>

<?php
include 'includes/footer.php';
?>