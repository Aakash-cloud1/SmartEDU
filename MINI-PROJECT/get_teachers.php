<?php
include 'config.php';

$result = $conn->query("SELECT name, dept FROM user_db WHERE role = 'teacher'");
$teachers = [];

while ($row = $result->fetch_assoc()) {
  $teachers[] = $row;
}

echo json_encode($teachers);
?>
