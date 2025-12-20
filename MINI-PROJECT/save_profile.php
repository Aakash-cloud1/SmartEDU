<?php
session_start();
include 'config.php'; // Make sure DB connection is established

$email = $_SESSION['email'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect fields
    $roll = $_POST['roll'] ?? '';
    $section = $_POST['section'] ?? '';
    $college = $_POST['college'] ?? '';
    $cgpa = $_POST['cgpa'] ?? '';
    $backlogs = $_POST['backlogs'] ?? '';
    $father = $_POST['father'] ?? '';
    $mother = $_POST['mother'] ?? '';
    $inter_percent = $_POST['inter_percent'] ?? '';
    $inter_college = $_POST['inter_college'] ?? '';
    $inter_board = $_POST['inter_board'] ?? '';
    $hobbies = $_POST['hobbies'] ?? '';
    $skills = $_POST['skills'] ?? '';
    $goals = $_POST['goals'] ?? '';

    // Handle profile photo if uploaded
    $profile_photo = null;
    if (isset($_FILES['profilePhoto']) && $_FILES['profilePhoto']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $file_tmp = $_FILES['profilePhoto']['tmp_name'];
        $file_name = uniqid() . '_' . $_FILES['profilePhoto']['name'];
        $upload_path = $upload_dir . $file_name;

        if (move_uploaded_file($file_tmp, $upload_path)) {
            $profile_photo = $upload_path;
        }
    }

    // Validation: Ensure required fields are filled
    if (!$email || !$roll || !$section || !$college || !$cgpa || !$father || !$mother || !$inter_percent || !$inter_college || !$inter_board) {
        echo "<script>alert('Please fill all required fields.'); window.history.back();</script>";
        exit();
    }

    // Check if profile already exists (to decide UPDATE or INSERT)
    $stmt = $conn->prepare("SELECT email FROM student_profiles WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Update
        if ($profile_photo) {
            $query = "UPDATE student_profiles SET roll=?, section=?, college=?, cgpa=?, backlogs=?, father=?, mother=?, inter_percent=?, inter_college=?, inter_board=?, hobbies=?, skills=?, goals=?, profile_photo=? WHERE email=?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssssissssssssss", $roll, $section, $college, $cgpa, $backlogs, $father, $mother, $inter_percent, $inter_college, $inter_board, $hobbies, $skills, $goals, $profile_photo, $email);
        } else {
            $query = "UPDATE student_profiles SET roll=?, section=?, college=?, cgpa=?, backlogs=?, father=?, mother=?, inter_percent=?, inter_college=?, inter_board=?, hobbies=?, skills=?, goals=? WHERE email=?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssssisssssssss", $roll, $section, $college, $cgpa, $backlogs, $father, $mother, $inter_percent, $inter_college, $inter_board, $hobbies, $skills, $goals, $email);
        }
    } else {
        // Insert
        $query = "INSERT INTO student_profiles (email, roll, section, college, cgpa, backlogs, father, mother, inter_percent, inter_college, inter_board, hobbies, skills, goals, profile_photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssissssssssss", $email, $roll, $section, $college, $cgpa, $backlogs, $father, $mother, $inter_percent, $inter_college, $inter_board, $hobbies, $skills, $goals, $profile_photo);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Profile saved successfully'); window.location.href='user_page.php';</script>";
    } else {
        echo "<script>alert('Error saving profile'); window.history.back();</script>";
    }
    
    
    $stmt->close();
    $conn->close();
}
?>

