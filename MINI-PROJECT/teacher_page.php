<?php
session_start();
include 'config.php';

$teacher_name = trim($_SESSION['name']);
$department = trim($_SESSION['dept']);

// Now use these in your query
$sql = "SELECT message, submitted_at FROM feedback 
        WHERE teacher_name = '$teacher_name' AND department = '$department' 
        ORDER BY submitted_at DESC";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Teacher Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    /* Modern Color Palette */
    :root {
      --primary: #4361ee;
      --secondary: #3f37c9;
      --accent: #4895ef;
      --light: #f8f9fa;
      --dark: #212529;
      --success: rgb(113, 193, 113);
      --warning: #f72585;
      --info: #560bad;
    }

    /* Base Styles */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    body {
      font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #ebf2f6 0%, #3d3d3d 100%);
      color: var(--dark);
      overflow-x: hidden;
      min-height: 100vh;
    }

    /* Sidebar Styles */
    .sidebar {
      height: 100vh;
      width: 280px;
      position: fixed;
      background: linear-gradient(180deg, var(--primary) 0%, var(--secondary) 100%);
      color: white;
      display: flex;
      flex-direction: column;
      padding: 20px 0;
      box-shadow: 5px 0 15px rgba(0, 0, 0, 0.1);
      z-index: 1000;
      transform: translateX(0);
    }

    .sidebar-header {
      text-align: center;
      padding: 0 20px 30px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      margin-bottom: 20px;
    }

    .sidebar h2 {
      font-size: 1.8rem;
      margin-bottom: 5px;
      font-weight: 600;
      background: linear-gradient(to right, #fff 0%, #f8f9fa 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .sidebar-subtitle {
      font-size: 0.8rem;
      opacity: 0.8;
      font-weight: 300;
    }

    .nav-links {
      flex-grow: 1;
      overflow-y: auto;
      padding: 0 15px;
    }

    .sidebar a {
      display: flex;
      align-items: center;
      padding: 15px 20px;
      color: rgba(255, 255, 255, 0.9);
      text-decoration: none;
      border-radius: 8px;
      margin: 5px 0;
      font-size: 0.95rem;
      font-weight: 500;
    }

    .sidebar a i {
      margin-right: 12px;
      font-size: 1.1rem;
      width: 24px;
      text-align: center;
    }

    .sidebar a:hover {
      background: rgba(255, 255, 255, 0.15);
      transform: translateX(5px);
      color: white;
    }

    .sidebar a.active {
      background: rgba(255, 255, 255, 0.2);
      color: white;
      font-weight: 600;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .logout-btn {
      margin-top: auto;
      margin-bottom: 20px;
      background: rgba(255, 255, 255, 0.1);
      color: white;
      border: none;
      padding: 15px 20px;
      border-radius: 8px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.95rem;
      font-weight: 500;
      width: calc(100% - 40px);
      margin-left: 20px;
      transition: all 0.3s ease;
    }

    .logout-btn:hover {
      background: rgba(255, 255, 255, 0.2);
      transform: translateY(-2px);
    }

    .logout-btn i {
      margin-right: 10px;
    }

    /* Main Content */
    .main-content {
      margin-left: 280px;
      padding: 25px;
      min-height: 100vh;
      position: relative;
    }

    header {
      background: white;
      color: var(--dark);
      padding: 20px;
      font-weight: 600;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      margin-bottom: 25px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .header-title {
      font-size: 1.5rem;
      font-weight: 600;
    }

    .user-profile {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .user-avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: var(--accent);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: bold;
    }

    /* Section Styles */
    .section {
      display: none;
      animation: fadeIn 0.5s ease forwards;
      background: white;
      border-radius: 12px;
      padding: 25px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      margin-bottom: 25px;
    }

    .section.active {
      display: block;
    }

    .section-title {
      font-size: 1.4rem;
      margin-bottom: 20px;
      color: var(--primary);
      font-weight: 600;
      position: relative;
      padding-bottom: 10px;
    }

    .section-title::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 60px;
      height: 3px;
      background: linear-gradient(to right, var(--primary), var(--accent));
      border-radius: 3px;
    }

    /* Card Styles */
    .card {
      background: white;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      margin-bottom: 20px;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .card-title {
      font-size: 1.1rem;
      margin-bottom: 15px;
      color: var(--secondary);
      font-weight: 600;
    }

    /* Form Elements */
    .form-group {
      margin-bottom: 20px;
    }

    label {
      display: block;
      margin-bottom: 8px;
      font-weight: 500;
      color: var(--dark);
    }

    input[type="text"],
    input[type="number"],
    input[type="email"],
    select,
    textarea {
      width: 100%;
      padding: 12px 15px;
      border: 1px solid #e0e0e0;
      border-radius: 8px;
      font-size: 0.95rem;
      transition: all 0.3s ease;
    }

    input:focus,
    select:focus,
    textarea:focus {
      border-color: var(--accent);
      outline: none;
      box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }

    .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 12px 24px;
      font-size: 0.95rem;
      font-weight: 500;
      border-radius: 8px;
      cursor: pointer;
      border: none;
      transition: all 0.3s ease;
      text-decoration: none;
    }

    .btn-primary {
      background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
      color: white;
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 12px rgba(67, 97, 238, 0.2);
    }

    .btn-secondary {
      background: white;
      color: var(--primary);
      border: 1px solid var(--primary);
    }

    .btn-secondary:hover {
      background: rgba(67, 97, 238, 0.05);
    }

    .btn-group {
      display: flex;
      gap: 10px;
      margin-top: 20px;
    }

    /* Table Styles */
    table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
      margin: 20px 0;
      background-color: white;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      border-radius: 12px;
      overflow: hidden;
    }

    th {
      background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
      color: white;
      font-weight: 500;
      padding: 15px;
      text-align: left;
    }

    td {
      padding: 12px 15px;
      border-bottom: 1px solid #f0f0f0;
      vertical-align: middle;
    }

    tr:last-child td {
      border-bottom: none;
    }

    tr:hover td {
      background-color: rgba(67, 97, 238, 0.03);
    }

    .break, .lunch {
      background-color: #f8f9fa;
      font-weight: bold;
      color: #6c757d;
    }

    .lab {
      background-color: rgba(72, 149, 239, 0.1);
      font-weight: bold;
      color: var(--accent);
    }

    /* Timetable Specific */
    .timetable-controls {
      display: flex;
      flex-wrap: wrap;
      gap: 15px;
      margin-bottom: 20px;
    }

    .timetable-actions {
      display: flex;
      gap: 10px;
      margin-top: 20px;
    }

    /* Student Management */
    .student-form {
      background: white;
      border-radius: 12px;
      padding: 25px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      margin-bottom: 30px;
    }

    .form-row {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      margin-bottom: 15px;
    }

    .form-col {
      flex: 1;
      min-width: 200px;
    }

    /* Feedback Section */
    .feedback-item {
      background: white;
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 15px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
      transition: all 0.3s ease;
    }

    .feedback-item:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    }

    .feedback-meta {
      display: flex;
      justify-content: space-between;
      margin-bottom: 10px;
      font-size: 0.85rem;
      color: #6c757d;
    }

    .feedback-content {
      line-height: 1.6;
    }
    .qualification-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
      }
      
      .qualification-table th, 
      .qualification-table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #eee;
      }
      
      .qualification-table th {
        background-color: var(--primary);
        color: white;
      }
      
      .training-list {
        list-style-type: none;
        padding-left: 0;
      }
      
      .training-list li {
        padding: 10px 0;
        border-bottom: 1px dashed #eee;
      }
      
      .training-list li:last-child {
        border-bottom: none;
      }
      
      .form-row {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-bottom: 15px;
      }
      
      .form-col {
        flex: 1;
        min-width: 250px;
      }
    /* Responsive Design */
    @media (max-width: 992px) {
      .sidebar {
        width: 240px;
      }
      .main-content {
        margin-left: 240px;
      }
    }

    @media (max-width: 768px) {
      .sidebar {
        transform: translateX(-100%);
        position: fixed;
        top: 0;
        left: 0;
        transition: transform 0.3s ease;
      }
      .sidebar.active {
        transform: translateX(0);
      }
      .main-content {
        margin-left: 0;
      }
      .menu-toggle {
        display: block;
      }
    }

    @media print {
      .sidebar, .no-print {
        display: none !important;
      }
      .main-content {
        margin-left: 0;
      }
      .section {
        display: block !important;
        box-shadow: none;
        padding: 0;
      }
    }

    /* Animations */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeInDown {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @keyframes pulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.05); }
      100% { transform: scale(1); }
    }

    /* Utility Classes */
    .text-center { text-align: center; }
    .text-muted { color: #6c757d; }
    .mb-20 { margin-bottom: 20px; }
    .mt-20 { margin-top: 20px; }
    .p-20 { padding: 20px; }
    .d-flex { display: flex; }
    .align-center { align-items: center; }
    .justify-between { justify-content: space-between; }
    .gap-10 { gap: 10px; }
  </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-header">
      <h2><i class="fas fa-book-open"></i> EduEase</h2>
      <p class="sidebar-subtitle">Teacher Dashboard</p>
    </div>
    
    <div class="nav-links">
      <a href="#profile" onclick="showSection('profile')" class="active">
        <i class="fas fa-user"></i> Profile
      </a>
      <a href="#timetable" onclick="showSection('timetable')">
        <i class="fas fa-calendar-alt"></i> Timetable Generator
      </a>
      <a href="#invigilatorSettings" onclick="showSection('invigilatorSettings')">
        <i class="fas fa-tasks"></i> Invigilator Assigner
      </a>
      <a href="#student" onclick="showSection('student')">
        <i class="fas fa-graduation-cap"></i> Student Management
      </a>
      <a href="#feedback" onclick="showSection('feedback')">
        <i class="fas fa-comment-alt"></i> Feedback
      </a>
    </div>

    <button class="logout-btn" onclick="window.location.href='logout.php'">
      <i class="fas fa-sign-out-alt"></i> Logout
    </button>
  </div>

  <div class="main-content">
    <header>
      <div class="header-title">Welcome, <span id="teacherName">Teacher01</span></div>
      <div class="user-profile">
        <div class="user-avatar">PS</div>
      </div>
    </header>
   <!-- profile -->
    <div id="profile" class="section active">
        <h2 class="section-title">My Profile</h2>
        
        <div class="card">
          <div class="card-title">Personal Information</div>
          <div class="profile-details">
            <div class="form-row">
              <div class="form-col">
                <div class="form-group">
                  <label>Full Name</label>
                  <p>Teacher01</p>
                </div>
                <div class="form-group">
                  <label>Teacher ID</label>
                  <p>TEA-2023-4567</p>
                </div>
                <div class="form-group">
                  <label>Date of Birth</label>
                  <p>15 March 1985</p>
                </div>
                <div class="form-group">
                  <label>Gender</label>
                  <p>Male</p>
                </div>
              </div>
              
              <div class="form-col">
                <div class="form-group">
                  <label>Email</label>
                  <p>prof.smith@eduease.edu</p>
                </div>
                <div class="form-group">
                  <label>Contact Number</label>
                  <p>+1 (555) 123-4567</p>
                </div>
                <div class="form-group">
                  <label>Emergency Contact</label>
                  <p>+1 (555) 987-6543 (Spouse)</p>
                </div>
                <div class="form-group">
                  <label>Blood Group</label>
                  <p>O+</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      
        <div class="card">
          <div class="card-title">Professional Information</div>
          <div class="profile-details">
            <div class="form-row">
              <div class="form-col">
                <div class="form-group">
                  <label>Department</label>
                  <p>Computer Science</p>
                </div>
                <div class="form-group">
                  <label>Designation</label>
                  <p>Senior Teacher</p>
                </div>
                <div class="form-group">
                  <label>Date of Joining</label>
                  <p>10 August 2015</p>
                </div>
              </div>
              
              <div class="form-col">
                <div class="form-group">
                  <label>Subjects Taught</label>
                  <p>Computer Science, Mathematics, ICT</p>
                </div>
                <div class="form-group">
                  <label>Classes Assigned</label>
                  <p>Grade 9-12</p>
                </div>
                <div class="form-group">
                  <label>Total Experience</label>
                  <p>12 Years</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      
        <div class="card">
          <div class="card-title">Educational Qualifications</div>
          <div class="profile-details">
            <table class="qualification-table">
              <thead>
                <tr>
                  <th>Degree</th>
                  <th>Institution</th>
                  <th>Year</th>
                  <th>Specialization</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>M.Ed</td>
                  <td>Harvard University</td>
                  <td>2013</td>
                  <td>Educational Technology</td>
                </tr>
                <tr>
                  <td>B.Sc</td>
                  <td>MIT</td>
                  <td>2010</td>
                  <td>Computer Science</td>
                </tr>
                <tr>
                  <td>Teaching Certification</td>
                  <td>National Board</td>
                  <td>2014</td>
                  <td>Secondary Education</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      
        <div class="card">
          <div class="card-title">Professional Development</div>
          <div class="profile-details">
            <ul class="training-list">
              <li>
                <strong>STEM Teaching Workshop</strong> - National Science Foundation (2022)
              </li>
              <li>
                <strong>Classroom Management Certification</strong> - Education Academy (2021)
              </li>
              <li>
                <strong>Advanced Python Programming</strong> - Coursera (2020)
              </li>
              <li>
                <strong>Inclusive Education Training</strong> - UNESCO (2019)
              </li>
            </ul>
          </div>
        </div>
      
        <div class="card">
          <div class="card-title">Class Responsibilities</div>
          <div class="profile-details">
            <div class="form-row">
              <div class="form-col">
                <div class="form-group">
                  <label>Homeroom Teacher</label>
                  <p>Grade 10-B</p>
                </div>
                <div class="form-group">
                  <label>Club Mentor</label>
                  <p>Computer Club, Robotics Team</p>
                </div>
              </div>
              <div class="form-col">
                <div class="form-group">
                  <label>Exam Coordinator</label>
                  <p>Annual Science Fair</p>
                </div>
                <div class="form-group">
                  <label>Additional Duties</label>
                  <p>IT Support Team, Curriculum Development</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      
        <div class="card">
          <div class="card-title">Account Settings</div>
          <form>
            <div class="form-row">
              <div class="form-col">
                <div class="form-group">
                  <label for="changePassword">Change Password</label>
                  <input type="password" id="changePassword" placeholder="Enter new password">
                </div>
              </div>
              <div class="form-col">
                <div class="form-group">
                  <label for="confirmPassword">Confirm Password</label>
                  <input type="password" id="confirmPassword" placeholder="Confirm new password">
                </div>
              </div>
            </div>
            <div class="form-group">
              <label for="profilePicture">Update Profile Picture</label>
              <input type="file" id="profilePicture" accept="image/*">
            </div>
            <button type="button" class="btn btn-primary">Update Profile</button>
          </form>
        </div>
      </div>

    <!-- Timetable Generator Section -->
    <div id="timetable" class="section">
      <h2 class="section-title">Timetable Generator</h2>
      
      <div class="card">
        <form id="mainForm">
          <div class="form-group">
            <label for="numSubjects">Number of Subjects</label>
            <input type="number" id="numSubjects" min="1" required placeholder="e.g., 5">
          </div>
          
          <div class="btn-group">
            <button type="button" class="btn btn-primary" onclick="generateSubjectFields()">
              <i class="fas fa-arrow-right"></i> Next Step
            </button>
          </div>
        </form>
      </div>

      <div id="subjectInputs" class="card" style="display: none;">
        <h3 class="card-title">Enter Subject Names</h3>
        <form id="subjectNamesForm">
          <div id="subjectFields"></div>
          
          <div class="form-group">
            <label for="numLabs">Number of Labs</label>
            <input type="number" id="numLabs" min="0" required placeholder="e.g., 2">
          </div>
          
          <div class="btn-group">
            <button type="button" class="btn btn-secondary" onclick="backToStep1()">
              <i class="fas fa-arrow-left"></i> Back
            </button>
            <button type="button" class="btn btn-primary" onclick="generateLabFields()">
              <i class="fas fa-arrow-right"></i> Next Step
            </button>
          </div>
        </form>
      </div>

      <div id="labInputs" class="card" style="display: none;">
        <h3 class="card-title">Enter Lab Names</h3>
        <form id="labNamesForm">
          <div id="labFields"></div>
          
          <div class="btn-group">
            <button type="button" class="btn btn-secondary" onclick="backToStep2()">
              <i class="fas fa-arrow-left"></i> Back
            </button>
            <button type="button" class="btn btn-primary" onclick="generateTimetable()">
              <i class="fas fa-magic"></i> Generate Timetable
            </button>
          </div>
        </form>
      </div>

      <div class="card" id="timetableResult" style="display: none;">
        <h3 class="card-title">Generated Timetable</h3>
        <div style="overflow-x: auto;">
          <table id="timetableTable"></table>
        </div>
        
        <div class="timetable-actions">
          <button id="regenerateBtn" class="btn btn-secondary" onclick="generateTimetable()">
            <i class="fas fa-sync-alt"></i> Regenerate
          </button>
          <button id="printBtn" class="btn btn-primary" onclick="printTimetable()">
            <i class="fas fa-print"></i> Print Timetable
          </button>
        </div>
      </div>
    </div>

    <!-- Assignment Generator Section -->
    <div id="invigilatorSettings" class="section">
      <h2 class="section-title">Invigilator Assigner</h2>
      
      <div class="card">
        <div class="form-group">
          <label for="latinInvigilators">Invigilator Names (comma separated)</label>
          <input type="text" id="latinInvigilators" placeholder="e.g., Alice, Bob, Charlie, David, Emma">
        </div>
        
        <div class="form-group">
          <label for="latinClasses">Class Names (comma separated)</label>
          <input type="text" id="latinClasses" placeholder="e.g., A1, B2, C3, D4, E5">
        </div>
        
        <div class="form-group">
          <label for="latinDays">Number of Days</label>
          <input type="number" id="latinDays" min="1" value="5">
        </div>
        
        <button class="btn btn-primary" onclick="assignLatinSquareFlexible()">
          <i class="fas fa-calendar-check"></i> Generate Schedule
        </button>
      </div>

      <div id="latinScheduleResult" class="card" style="display: none;"></div>
    </div>

      <!-- Student Management Section -->
      <div id="student" class="section">
      <h2 class="section-title">Student Management</h2>
      
      <div class="card">
        <h3 class="card-title">Add New Student</h3>
        <form id="studentForm" onsubmit="return addStudent();">
          <div class="form-row">
            <div class="form-col">
              <div class="form-group">
                <label for="name">Student Name</label>
                <input type="text" id="name" placeholder="Student Name" required>
              </div>
              <div class="form-group">
                <label for="roll_no">Roll Number</label>
                <input type="text" id="roll_no" placeholder="Roll Number" required>
              </div>
              <div class="form-group">
                <label for="sem">Semester</label>
                <input type="text" id="sem" placeholder="Semester" required>
              </div>
              <div class="form-group">
                <label for="number">Phone Number</label>
                <input type="text" id="number" placeholder="Phone Number" required>
              </div>
            </div>
            
            <div class="form-col">
              <div class="form-group">
                <label for="sub1">Subject 1 Marks</label>
                <input type="number" id="sub1" placeholder="Subject 1 Marks" required>
              </div>
              <div class="form-group">
                <label for="sub2">Subject 2 Marks</label>
                <input type="number" id="sub2" placeholder="Subject 2 Marks" required>
              </div>
              <div class="form-group">
                <label for="sub3">Subject 3 Marks</label>
                <input type="number" id="sub3" placeholder="Subject 3 Marks" required>
              </div>
              <div class="form-group">
                <label for="sub4">Subject 4 Marks</label>
                <input type="number" id="sub4" placeholder="Subject 4 Marks" required>
              </div>
            </div>
            
            <div class="form-col">
              <div class="form-group">
                <label for="sub5">Subject 5 Marks</label>
                <input type="number" id="sub5" placeholder="Subject 5 Marks" required>
              </div>
              <div class="form-group">
                <label for="sub6">Subject 6 Marks</label>
                <input type="number" id="sub6" placeholder="Subject 6 Marks" required>
              </div>
              <div class="form-group">
                <label for="mid1">Mid 1 Marks</label>
                <input type="number" id="mid1" placeholder="Mid 1 Marks" required>
              </div>
              <div class="form-group">
                <label for="mid2">Mid 2 Marks</label>
                <input type="number" id="mid2" placeholder="Mid 2 Marks" required>
              </div>
            </div>
            
            <div class="form-col">
              <div class="form-group">
                <label for="sgpa">SGPA</label>
                <input type="number" step="0.01" id="sgpa" placeholder="SGPA" required>
              </div>
              <div class="form-group">
                <label for="cgpa">CGPA</label>
                <input type="number" step="0.01" id="cgpa" placeholder="CGPA" required>
              </div>
            </div>
          </div>
          
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> Add Student
          </button>
        </form>
      </div>
      
      <div class="card">
        <h3 class="card-title">Student Performance Dashboard</h3>
        <div style="overflow-x: auto;">
          <table id="studentList">
            <thead>
              <tr>
                <th>Name</th>
                <th>Roll No</th>
                <th>Semester</th>
                <th>Sub1</th>
                <th>Sub2</th>
                <th>Sub3</th>
                <th>Sub4</th>
                <th>Sub5</th>
                <th>Sub6</th>
                <th>Mid1</th>
                <th>Mid2</th>
                <th>SGPA</th>
                <th>CGPA</th>
                <th>Contact</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>

<!-- Feedback -->
<div id="feedback" class="section">
<h2 class="section-title">Student Feedback</h2>
<div class="card">
   <div id="teacherFeedbackSection">
      <div class="feedback-item">
       <div class="feedback-content">
<?php
echo "<div id='teacherFeedbackSection'>";
echo "<h2>🗣️ Student Feedback</h2>";

if ($result && $result->num_rows > 0) {
  echo "<ul style='list-style-type: none; padding: 0;'>";
  while ($row = $result->fetch_assoc()) {
    $message = htmlspecialchars($row['message']);
    $timestamp = $row['submitted_at'];
    echo "<li style='background: #f1f1f1; padding: 10px; margin-bottom: 10px; border-radius: 5px;'>";
    echo "<strong>$timestamp:</strong> $message";
    echo "</li>";
  }
  echo "</ul>";
} else {
  echo "<p>No feedback available yet.</p>";
}

echo "</div>";
?>
</div>
</div>
</div>
</div>
</div>


    <footer>© 2025 EduEase Dashboard</footer>
  </div>

  <script>
      function showSection(id) {
      // Hide all sections
      document.querySelectorAll('.section').forEach(section => {
        section.classList.remove('active');
      });
      
      // Show the selected section
      document.getElementById(id).classList.add('active');
      
      // Update active nav link
      document.querySelectorAll('.sidebar a').forEach(link => {
        link.classList.remove('active');
      });
      document.querySelector(`.sidebar a[href="#${id}"]`).classList.add('active');


    }

        let subjects = [];
        let labs = [];

        function generateSubjectFields() {
            let numSubjects = parseInt(document.getElementById("numSubjects").value);
            if (numSubjects < 1) {
                alert("Please enter a valid number of subjects.");
                return;
            }

            let subjectFields = document.getElementById("subjectFields");
            subjectFields.innerHTML = "";

            for (let i = 0; i < numSubjects; i++) {
                let input = document.createElement("input");
                input.type = "text";
                input.placeholder = `Enter name of subject ${i + 1}`;
                input.required = true;
                input.className = "subjectInput";
                subjectFields.appendChild(input);
                subjectFields.appendChild(document.createElement("br"));
            }

            document.getElementById("subjectInputs").style.display = "block";
            document
        }

        function generateLabFields() {
            let numLabs = parseInt(document.getElementById("numLabs").value);

            let labFields = document.getElementById("labFields");
            labFields.innerHTML = "";

            for (let i = 0; i < numLabs; i++) {
                let input = document.createElement("input");
                input.type = "text";
                input.placeholder = `Enter name of lab ${i + 1}`;
                input.required = true;
                input.className = "labInput";
                labFields.appendChild(input);
                labFields.appendChild(document.createElement("br"));
            }

            document.getElementById("labInputs").style.display = "block";
        }
    
        function generateTimetable() {
            subjects = Array.from(document.querySelectorAll(".subjectInput")).map(input => input.value.trim()).filter(Boolean);
            labs = Array.from(document.querySelectorAll(".labInput")).map(input => input.value.trim()).filter(Boolean);

            if (subjects.length === 0) {
                alert("Please enter valid subject names.");
                return;
            }

            let timetable = document.getElementById("timetableTable");
            timetable.innerHTML = '';

            let days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
            let periods = [
                "9:20-10:10", "10:10-11:00", "11:00-11:10 (Break)", "11:10-12:00",
                "12:00-12:50", "12:50-1:30 (Lunch)", "1:30-2:20", "2:20-3:10", "3:10-4:00"
            ];

            let schedule = [];
            let subjectIndex = 0;

            let labDays = [];
            while (labDays.length < labs.length) {
                let randomDay = Math.floor(Math.random() * days.length);
                if (!labDays.includes(randomDay)) {
                    labDays.push(randomDay);
                }
            }

            for (let i = 0; i < days.length; i++) {
                let row = [`<td>${days[i]}</td>`];
                let isLabDay = labDays.includes(i);
                let labAssigned = false;

                for (let j = 0; j < periods.length; j++) {
                    if (periods[j].includes("Break")) {
                        row.push(`<td class='break'>BREAK</td>`);
                    } else if (periods[j].includes("Lunch")) {
                        row.push(`<td class='lunch'>LUNCH</td>`);
                    } else if (isLabDay && !labAssigned && j === 6) {
                        let labName = labs[labDays.indexOf(i)];
                        row.push(`<td class='lab' colspan="3">${labName} (Lab)</td>`);
                        labAssigned = true;
                        j += 2;
                    } else {
                        row.push(`<td>${subjects[subjectIndex]}</td>`);
                        subjectIndex = (subjectIndex + 1) % subjects.length;
                    }
                }
                schedule.push(row);
            }

            let headerRow = "<tr><th>Day</th>";
            periods.forEach(p => headerRow += `<th>${p}</th>`);
            headerRow += "</tr>";
            timetable.innerHTML += headerRow;

            schedule.forEach(row => {
                let rowHtml = "<tr>";
                row.forEach(cell => rowHtml += cell);
                rowHtml += "</tr>";
                timetable.innerHTML += rowHtml;
            });

            document.getElementById("regenerateBtn").style.display = "inline-block";
            document.getElementById("printBtn").style.display = "inline-block";
            document.getElementById("timetableResult").style.display="block";
            document.getElementById("labInputs").style.display="none";
        }

        function printTimetable() {
            window.print();
        }
        function assignLatinSquareFlexible() {
    const invigilators = document.getElementById("latinInvigilators").value.split(',').map(v => v.trim());
    const classes = document.getElementById("latinClasses").value.split(',').map(v => v.trim());
    const numDays = parseInt(document.getElementById("latinDays").value);
    const output = document.getElementById("latinScheduleResult");

    const numInvigilators = invigilators.length;
    const numClasses = classes.length;

    if (numClasses === 0 || numInvigilators === 0 || numDays === 0) {
        alert("Please enter valid invigilators, classes, and days.");
        return;
    }

    if (numInvigilators < numClasses) {
        alert("Number of invigilators must be at least equal to number of classes.");
        return;
    }

    // Initialize tracking to prevent repeats: class -> Set of used invigilators
    const used = {};
    classes.forEach(cls => used[cls] = new Set());

    let result = `<h3 class="card-title">Flexible Invigilation Schedule</h3>
                 <div class="schedule-container">`;

    for (let day = 0; day < numDays; day++) {
        result += `<div class="day-card">
                      <h4>Day ${day + 1}</h4>
                      <table class="schedule-table">
                          <tr><th>Class</th><th>Invigilator</th></tr>`;

        let assignedToday = new Set();

        for (let i = 0; i < numClasses; i++) {
            const cls = classes[i];
            const available = invigilators.filter(name => 
                !used[cls].has(name) && !assignedToday.has(name)
            );

            if (available.length === 0) {
                result += `<tr><td>${cls}</td><td class="error">⚠ Not enough unique invigilators</td></tr>`;
                continue;
            }

            const assigned = available[day % available.length]; // fair rotation
            used[cls].add(assigned);
            assignedToday.add(assigned);

            result += `<tr><td>${cls}</td><td>${assigned}</td></tr>`;
        }

        result += `</table></div>`;
    }

    result += `</div>`;
    output.innerHTML = result;
    output.style.display = "block";
    
    // Add some styling to the dynamically generated content
    const style = document.createElement('style');
    style.textContent = `
        .schedule-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .day-card {
            background: white;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .day-card h4 {
            color: var(--primary);
            margin-bottom: 10px;
        }
        .schedule-table {
            width: 100%;
            border-collapse: collapse;
        }
        .schedule-table th, .schedule-table td {
            padding: 8px 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        .schedule-table th {
            background-color: var(--primary);
            color: white;
        }
        .error {
            color: var(--warning);
        }
    `;
    document.head.appendChild(style); // Append to head instead of output
}

function addStudent() {
  const data = {
    name: document.getElementById("name").value,
    roll_no: document.getElementById("roll_no").value,
    sem: document.getElementById("sem").value,
    sub1: document.getElementById("sub1").value,
    sub2: document.getElementById("sub2").value,
    sub3: document.getElementById("sub3").value,
    sub4: document.getElementById("sub4").value,
    sub5: document.getElementById("sub5").value,
    sub6: document.getElementById("sub6").value,
    mid1: document.getElementById("mid1").value,
    mid2: document.getElementById("mid2").value,
    sgpa: document.getElementById("sgpa").value,
    cgpa: document.getElementById("cgpa").value,
    number: document.getElementById("number").value
  };

  fetch("add_student.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: new URLSearchParams(data).toString()
  })
  .then(res => res.text())
  .then(response => {
    alert(response);
    loadStudentDashboard(); // refresh student view
  });

  return false;
}

function loadStudentDashboard() {
  fetch("get_all_students.php")
    .then(res => res.json())
    .then(data => {
      const table = document.querySelector("#studentList tbody");
      table.innerHTML = "";
      data.forEach(student => {
        table.innerHTML += `
          <tr>
            <td>${student.name}</td>
            <td>${student.roll_no}</td>
            <td>${student.sem}</td>
            <td>${student.sub1}</td>
            <td>${student.sub2}</td>
            <td>${student.sub3}</td>
            <td>${student.sub4}</td>
            <td>${student.sub5}</td>
            <td>${student.sub6}</td>
            <td>${student.mid1}</td>
            <td>${student.mid2}</td>
            <td>${student.SGPA}</td>
            <td>${student.CGPA}</td>
            <td>${student.number}</td>
          </tr>
        `;
      });
    });
}

window.onload = loadStudentDashboard;
  </script>

</body>
</html>
