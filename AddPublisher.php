<?php
session_start();
include('includes/header.php');

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
$errorsOfAddPublisher = isset($_SESSION['errorsOfAddPublisher']) ? $_SESSION['errorsOfAddPublisher'] : array("name" => "", "birthdate" => "", "age" => "", "nationality" => "");

// Get previous input values
$oldPublisherData = isset($_SESSION['oldPublisherData']) ? $_SESSION['oldPublisherData'] : array("name" => "", "birthdate" => "", "age" => "", "nationality" => "");

// After getting, clear the session
unset($_SESSION['errorsOfAddPublisher']);
unset($_SESSION['oldPublisherData']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Publisher</title>

    <link rel="stylesheet" href="includes/CSS/style.css?v=1.0">
</head>

<body>
    <h1 style="text-align: center;margin-top: 50px;text-decoration: underline;">Add Publisher</h1>
    <form action="makeAddPublisher.php" method="POST">

        <!-- for in label = id in input -->
        <div>
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($oldPublisherData['name']); ?>">
            <span style="color: red; font-size:12px;position:relative; top: -10px;"><?php echo $errorsOfAddPublisher["name"] ?></span>
        </div>

        <div>
            <label for="birthdate">Birthdate:</label>
            <input type="date" name="birthdate" id="birthdate" value="<?php echo htmlspecialchars($oldPublisherData['birthdate']); ?>">
            <span style="color: red;font-size:12px;position: relative; top: -10px;"><?php echo $errorsOfAddPublisher["birthdate"] ?></span>
        </div>

        <div>
            <label for="age">Age:</label>
            <input type="number" name="age" id="age" value="<?php echo htmlspecialchars($oldPublisherData['age']); ?>">
            <span style="color: red;font-size:12px;position: relative; top: -10px;"><?php echo $errorsOfAddPublisher["age"] ?></span>
        </div>

        <div>

            <label for="nationality">Nationality:</label>
            <input type="text" name="nationality" id="nationality" value="<?php echo htmlspecialchars($oldPublisherData['nationality']); ?>">
            <span style="color: red;font-size:12px;position: relative; top: -10px;"><?php echo $errorsOfAddPublisher["nationality"] ?></span>
        </div>

        <input type="submit" value="Add Publisher">

    </form>
</body>

</html>

<?php
include('includes/footer.php');
?>