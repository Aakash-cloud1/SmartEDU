<?php
session_start();
include 'config.php';

$teacherId = $_SESSION['teacher_id'];
$studentId = $_POST['student_id'];
$marks = $_POST['marks'];

$subjectId = $conn->query("SELECT id FROM subjects WHERE teacher_id = $teacherId")->fetch_assoc()['id'];

$check = $conn->query("SELECT id FROM marks WHERE student_id = $studentId AND subject_id = $subjectId");

if ($check->num_rows > 0) {
  $conn->query("UPDATE marks SET marks = $marks WHERE student_id = $studentId AND subject_id = $subjectId");
  echo "Marks updated!";
} else {
  $conn->query("INSERT INTO marks (student_id, subject_id, marks) VALUES ($studentId, $subjectId, $marks)");
  echo "Marks inserted!";
}
?>
