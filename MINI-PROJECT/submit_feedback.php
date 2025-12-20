<?php
include 'config.php';

if (!isset($_POST['teacher_info']) || !isset($_POST['message'])) {
  echo "Error: Missing teacher or feedback message.";
  exit;
}

list($teacher_name, $department) = explode("|", $_POST['teacher_info']);
$message = $_POST['message'];

$sql = "INSERT INTO feedback (teacher_name, department, message) 
        VALUES ('$teacher_name', '$department', '$message')";

if ($conn->query($sql) === TRUE) {
  echo "Feedback submitted successfully!";
} else {
  echo "Error: " . $conn->error;
}
?>
