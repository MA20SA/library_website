<?php
include 'includes/db.php';

if (isset($_GET['studentId'])) {
    $studentId = intval($_GET['studentId']); // تأمين الرقم
    $query = "SELECT s.name as studentName , d.name as departmentName FROM `students` as s 
              inner JOIN departments as d on s.department_id = d.department_id
              WHERE `university_number` = $studentId;";

    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        echo $row['studentName'] . ", " . $row['departmentName'];
    } else {
        echo "الطالب غير موجود";
    }
}
?>
