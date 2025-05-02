<?php
session_start();
include('includes/header.php');
include('includes/db.php');

// Get Authors Name
$queryAuthor = "SELECT `author_id` as authorId,`name` FROM `authors`;";
$AuthorsName = mysqli_query($conn, $queryAuthor);

// Get Authors Name
$queryPublisher = "SELECT `publisher_id` as publisherId,`name` FROM `publishers`;";
$PublishersName = mysqli_query($conn, $queryPublisher);

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
$errorsOfAddBook = isset($_SESSION['errorsOfAddBook']) ? $_SESSION['errorsOfAddBook'] : array("name" => "", "title" => "", "publishdate" => "", "quantityavailable" => "", "author" => "", "publisher" => "" , "image"=>"");

// Get previous input values
$oldBookData = isset($_SESSION['oldBookData']) ? $_SESSION['oldBookData'] : array("name" => "", "title" => "", "publishdate" => "", "quantityavailable" => "", "authorId" => "", "publisherId" => "", "image"=>"");

// After getting, clear the session
unset($_SESSION['errorsOfAddBook']);
unset($_SESSION['oldBookData']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Add Book</title>

  <link rel="stylesheet" href="includes/CSS/style.css?v=1.0">
</head>

<body>
  <h1 style="text-align: center;margin-top: 50px;text-decoration: underline;">Add Book</h1>
  <form action="makeAddBook.php" method="POST" enctype="multipart/form-data">

    <!-- for in label = id in input -->
    <div>
      <label for="name">Name:</label>
      <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($oldBookData['name']); ?>">
      <span style="color: red; font-size:12px;position:relative; top: -10px;"><?php echo $errorsOfAddBook["name"] ?></span>
    </div>

    <div>
      <label for="title">Title:</label>
      <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($oldBookData['title']); ?>">
      <span style="color: red;font-size:12px;position: relative; top: -10px;"><?php echo $errorsOfAddBook["title"] ?></span>
    </div>

    <div>
      <label for="publishdate">Publish Date:</label>
      <input type="date" name="publishdate" id="publishdate" value="<?php echo htmlspecialchars($oldBookData['publishdate']); ?>">
      <span style="color: red;font-size:12px;position: relative; top: -10px;"><?php echo $errorsOfAddBook["publishdate"] ?></span>
    </div>

    <div>
      <label for="quantityavailable">Quantity Available:</label>
      <input type="number" name="quantityavailable" id="quantityavailable" value="<?php echo htmlspecialchars($oldBookData['quantityavailable']); ?>">
      <span style="color: red;font-size:12px;position: relative; top: -10px;"><?php echo $errorsOfAddBook["quantityavailable"] ?></span>
    </div>

    <div>
      <label for="authorId">Author:</label>
      <select id="authorId" name="authorId">
        <option disabled <?php echo empty($oldBookData['authorId']) ? 'selected' : ''; ?>>Select Author..</option>
        <?php
        while ($row = mysqli_fetch_assoc($AuthorsName)) {
          $selected = ($row["authorId"] == $oldBookData['authorId']) ? 'selected' : '';
          echo "<option value='" . htmlspecialchars($row["authorId"]) . "' $selected>" . htmlspecialchars($row["name"]) . "</option>";
        }
        ?>
      </select>
      <span style="color: red; font-size: 12px; position: relative; top: -10px;">
        <?php echo $errorsOfAddBook["author"]; ?>
      </span>
    </div>


    <div>
      <label for="publisherId">Publisher:</label>
      <select id="publisherId" name="publisherId">
        <option disabled <?php echo empty($oldBookData['publisherId']) ? 'selected' : ''; ?>>Select Publisher..</option>
        <?php
        while ($row = mysqli_fetch_assoc($PublishersName)) {
          $selected = ($row["publisherId"] == $oldBookData['publisherId']) ? 'selected' : '';
          echo "<option value='" . htmlspecialchars($row["publisherId"]) . "' $selected>" . htmlspecialchars($row["name"]) . "</option>";
        }
        ?>
      </select>
      <span style="color: red; font-size: 12px; position: relative; top: -10px;">
        <?php echo $errorsOfAddBook["publisher"]; ?>
      </span>
    </div>


    <div>
      <label for="image">Upload Image:</label>
      <input style="display: block;" type="file" id="image" name="image" accept="image/*">
      <span style="color: red; font-size: 12px; position: relative; top: -20px;">
        <?php echo $errorsOfAddBook["image"]; ?>
      </span>
    </div>



    <input type="submit" value="Add Book">

  </form>
</body>

</html>

<?php
include('includes/footer.php');
?>