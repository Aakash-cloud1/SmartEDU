<?php
include 'config.php';

$name = $_POST['name'];
$roll = $_POST['roll_no'];
$sem = $_POST['sem'];
$sub1 = $_POST['sub1'];
$sub2 = $_POST['sub2'];
$sub3 = $_POST['sub3'];
$sub4 = $_POST['sub4'];
$sub5 = $_POST['sub5'];
$sub6 = $_POST['sub6'];
$mid1 = $_POST['mid1'];
$mid2 = $_POST['mid2'];
$sgpa = $_POST['sgpa'];
$cgpa = $_POST['cgpa'];
$number = $_POST['number'];

$sql = "INSERT INTO students 
(name, roll_no, sem, sub1, sub2, sub3, sub4, sub5, sub6, mid1, mid2, SGPA, CGPA, number)
VALUES 
('$name', '$roll', '$sem', $sub1, $sub2, $sub3, $sub4, $sub5, $sub6, $mid1, $mid2, $sgpa, $cgpa, '$number')";

if ($conn->query($sql) === TRUE) {
  echo "Student added successfully!";
} else {
  echo "Error: " . $conn->error;
}
?>
