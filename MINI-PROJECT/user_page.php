<?php
session_start();
include 'config.php';  // Your database connection

if (!isset($_SESSION['name']) || !isset($_SESSION['email']) || !isset($_SESSION['dept'])) {
    header('Location: login.php');  // Redirect to login if not logged in
    exit();
}

$name = $_SESSION['name'];  // Get student name
$email = $_SESSION['email'];  // Get student email
$department = $_SESSION['dept'];  // Get student department

// Prepare SQL query to fetch student data
$query = "SELECT sub1, sub2, sub3, sub4, sub5, sub6, SGPA, CGPA FROM students WHERE name=?";
$stmt = $conn->prepare($query);

if ($stmt === false) {
  die('Error preparing statement: ' . $conn->error); // Error handling for prepare statement
}

$stmt->bind_param("s", $name);  // Bind email parameter to the query
$stmt->execute();

$result = $stmt->get_result();

// Check if any data is returned
if ($result->num_rows > 0) {
    // Fetch data and display results
    $student_data = $result->fetch_assoc();
    $sub1 = $student_data['sub1'];
    $sub2 = $student_data['sub2'];
    $sub3 = $student_data['sub3'];
    $sub4 = $student_data['sub4'];
    $sub5 = $student_data['sub5'];
    $sub6 = $student_data['sub6'];
    $SGPA = $student_data['SGPA'];
    $CGPA = $student_data['CGPA'];
} else {
    // If no data found, set a message
    $no_data_message = "Data not updated.";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Student Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    /* Modern Color Palette */
    :root {
      --primary: #4361ee;
      --secondary: #3f37c9;
      --accent: #4895ef;
      --light: #f8f9fa;
      --dark: #212529;
      --success: #4cc9f0;
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
      background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
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

    .sidebar a, .sidebar li {
      display: flex;
      align-items: center;
      padding: 15px 20px;
      color: rgba(255, 255, 255, 0.9);
      text-decoration: none;
      border-radius: 8px;
      margin: 5px 0;
      font-size: 0.95rem;
      font-weight: 500;
      cursor: pointer;
    }

    .sidebar li i {
      margin-right: 12px;
      font-size: 1.1rem;
      width: 24px;
      text-align: center;
    }

    .sidebar li:hover {
      background: rgba(255, 255, 255, 0.15);
      transform: translateX(5px);
      color: white;
    }

    .sidebar li.active {
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
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background: var(--accent);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: bold;
      overflow: hidden;
    }

    .user-avatar img {
      width: 100%;
      height: 100%;
      object-fit: cover;
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

    /* Profile Specific Styles */
    .profile-container {
      max-width: 1000px;
      margin: 0 auto;
      padding: 25px;
    }

    .profile-header {
      margin-bottom: 30px;
      background: #f0f8ff;
      border-left: 5px solid var(--accent);
      padding: 20px;
      border-radius: 12px;
    }

    .profile-form-card {
      background: white;
      border-radius: 12px;
      padding: 25px;
    }

    .profile-form {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
    }

    .profile-form h4 {
      grid-column: span 2;
      color: var(--secondary);
      margin-top: 20px;
      font-size: 1.1rem;
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

    /* Chat Styles */
    .chat-box {
      height: 400px;
      overflow-y: auto;
      border: 1px solid #e0e0e0;
      padding: 15px;
      border-radius: 12px;
      background: white;
      margin-bottom: 15px;
    }

    .chat-message {
      margin-bottom: 15px;
      padding-bottom: 15px;
      border-bottom: 1px dashed #eee;
    }

    .chat-message:last-child {
      border-bottom: none;
    }

    .chat-meta {
      display: flex;
      justify-content: space-between;
      font-size: 0.85rem;
      color: #6c757d;
      margin-bottom: 5px;
    }

    /* Results Table */
    .results-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    .results-table th, 
    .results-table td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #eee;
    }

    .results-table th {
      background-color: var(--primary);
      color: white;
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
      .profile-form {
        grid-template-columns: 1fr;
      }
      .profile-form h4 {
        grid-column: span 1;
      }
    }
  </style>
</head>

<body>
  <div class="sidebar">
    <div class="sidebar-header">
      <h2><i class="fas fa-user-graduate"></i> Student Panel</h2>
    </div>
    
    <div class="nav-links">
      <ul>
        <li onclick="showSection('profile')" class="active">
          <i class="fas fa-user"></i> Profile
        </li>
        <li onclick="showSection('feedback')">
          <i class="fas fa-comment-alt"></i> Feedback
        </li>
        <li onclick="showSection('interaction')">
          <i class="fas fa-comments"></i> Interaction
        </li>
        <li onclick="showSection('results')">
          <i class="fas fa-chart-line"></i> Results
        </li>
      </ul>
    </div>

    <button class="logout-btn" onclick="window.location.href='logout.php'">
      <i class="fas fa-sign-out-alt"></i> Logout
    </button>
  </div>

  <div class="main-content">
    <header>
      <div class="header-title">Welcome, <span id="teacherName"><?php echo $name; ?></span></div>
      <div class="user-profile">
        <div class="user-avatar">
          <?php echo strtoupper(substr($name, 0, 2)); ?>
        </div>
      </div>
    </header>

    <!-- Profile Section -->
    <div id="profile" class="section active">
      <div class="profile-container">
        <div class="card profile-header">
          <h3 class="section-title">Student Profile</h3>
          <div class="form-row">
            <div class="form-col">
              <div class="form-group">
                <label>Name</label>
                <p><?php echo $name; ?></p>
              </div>
              <div class="form-group">
                <label>Email</label>
                <p><?php echo $email; ?></p>
              </div>
            </div>
            <div class="form-col">
              <div class="form-group">
                <label>Department</label>
                <p><?php echo $department; ?></p>
              </div>
            </div>
          </div>
        </div>

        <div class="card profile-form-card">
          <h3 class="section-title">Academic & Personal Information</h3>
          <form action="save_profile.php" method="POST" class="profile-form" enctype="multipart/form-data">
            <div class="form-row">
              <div class="form-group">
                <label for="roll">Roll Number</label>
                <input type="text" id="roll" name="roll" placeholder="Enter roll number" required>
              </div>
              <div class="form-group">
                <label for="section">Section</label>
                <input type="text" id="section" name="section" placeholder="Enter section" required>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label for="college">College</label>
                <input type="text" id="college" name="college" placeholder="Enter college name" required>
              </div>
              <div class="form-group">
                <label for="cgpa">Current CGPA</label>
                <input type="number" step="0.01" id="cgpa" name="cgpa" placeholder="Enter CGPA" required>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label for="backlogs">Number of Backlogs</label>
                <input type="number" id="backlogs" name="backlogs" placeholder="Enter number of backlogs" required>
              </div>
              <div class="form-group">
                <label for="father">Father's Name</label>
                <input type="text" id="father" name="father" placeholder="Enter father's name" required>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label for="mother">Mother's Name</label>
                <input type="text" id="mother" name="mother" placeholder="Enter mother's name" required>
              </div>
              <div class="form-group">
                <label for="profile_pic">Profile Picture</label>
                <input type="file" id="profile_pic" name="profile_pic" accept="image/*">
              </div>
            </div>

            <h4>Intermediate Details</h4>

            <div class="form-row">
              <div class="form-group">
                <label for="inter_percent">Intermediate Percentage</label>
                <input type="number" step="0.01" id="inter_percent" name="inter_percent" placeholder="Enter percentage" required>
              </div>
              <div class="form-group">
                <label for="inter_college">Intermediate College</label>
                <input type="text" id="inter_college" name="inter_college" placeholder="Enter college name" required>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label for="inter_board">Intermediate Board</label>
                <input type="text" id="inter_board" name="inter_board" placeholder="Enter board name" required>
              </div>
              <div class="form-group">
                <label for="hobbies">Hobbies</label>
                <input type="text" id="hobbies" name="hobbies" placeholder="Enter your hobbies">
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label for="skills">Skills</label>
                <input type="text" id="skills" name="skills" placeholder="Enter your skills">
              </div>
              <div class="form-group">
                <label for="goals">Career Goals</label>
                <textarea id="goals" name="goals" rows="3" placeholder="Describe your career goals"></textarea>
              </div>
            </div>

            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save"></i> Save Profile
            </button>
          </form>
        </div>
      </div>
    </div>

    <!-- Feedback Form -->
    <div id="feedback" class="section">
      <div class="card">
        <h3 class="section-title">Feedback</h3>
        <form action="submit_feedback.php" method="POST">
          <div class="form-group">
            <label for="teacherSelect">Select Teacher</label>
            <select name="teacher_info" required id="teacherSelect" class="form-control">
              <option value="">-- Select a Teacher --</option>
              <!-- Options will be populated via JS -->
            </select>
          </div>

          <div class="form-group">
            <label for="message">Your Feedback</label>
            <textarea name="message" id="message" rows="4" required class="form-control"></textarea>
          </div>

          <button type="submit" class="btn btn-primary">
            <i class="fas fa-paper-plane"></i> Submit Feedback
          </button>
        </form>
      </div>
    </div>

    <!-- Interaction Section -->
    <div id="interaction" class="section">
      <div class="card">
        <h3 class="section-title">Student Group Chat</h3>

        <div class="chat-box" id="chatBox">
          <?php
            $chatResult = $conn->query("SELECT * FROM chat_messages ORDER BY timestamp DESC LIMIT 50");
            while ($row = $chatResult->fetch_assoc()) {
              echo '<div class="chat-message">';
              echo '<div class="chat-meta">';
              echo '<strong>' . htmlspecialchars($row['student_name']) . '</strong>';
              echo '<span>' . htmlspecialchars($row['timestamp']) . '</span>';
              echo '</div>';
              echo '<p>' . htmlspecialchars($row['message']) . '</p>';
              echo '</div>';
            }
          ?>
        </div>

        <form method="POST">
          <div class="form-group">
            <textarea name="chat_message" placeholder="Type your message..." required style="width: 100%; height: 100px;"></textarea>
          </div>
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-paper-plane"></i> Send Message
          </button>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['chat_message'])) {
            $message = trim($_POST['chat_message']);
            $stmt = $conn->prepare("INSERT INTO chat_messages (student_name, message) VALUES (?, ?)");
            $stmt->bind_param("ss", $name, $message);
            $stmt->execute();
            echo "<meta http-equiv='refresh' content='0'>"; // Refresh to show new message
        }
        ?>
      </div>
    </div>

    <!-- Results Section -->
    <div id="results" class="section">
      <div class="card">
        <h3 class="section-title">Academic Results</h3>
        <?php if (isset($no_data_message)): ?>
          <p><?php echo $no_data_message; ?></p>
        <?php else: ?>
          <div class="table-responsive">
            <table class="results-table">
              <thead>
                <tr>
                  <th>Subject</th>
                  <th>Score</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Subject 1</td>
                  <td><?php echo $sub1; ?></td>
                </tr>
                <tr>
                  <td>Subject 2</td>
                  <td><?php echo $sub2; ?></td>
                </tr>
                <tr>
                  <td>Subject 3</td>
                  <td><?php echo $sub3; ?></td>
                </tr>
                <tr>
                  <td>Subject 4</td>
                  <td><?php echo $sub4; ?></td>
                </tr>
                <tr>
                  <td>Subject 5</td>
                  <td><?php echo $sub5; ?></td>
                </tr>
                <tr>
                  <td>Subject 6</td>
                  <td><?php echo $sub6; ?></td>
                </tr>
                <tr>
                  <td><strong>SGPA</strong></td>
                  <td><strong><?php echo $SGPA; ?></strong></td>
                </tr>
                <tr>
                  <td><strong>CGPA</strong></td>
                  <td><strong><?php echo $CGPA; ?></strong></td>
                </tr>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <script>
    function showSection(id) {
      // Hide all sections
      document.querySelectorAll('.section').forEach(section => {
        section.classList.remove('active');
      });
      
      // Show selected section
      document.getElementById(id).classList.add('active');
      
      // Update active nav link
      document.querySelectorAll('.sidebar li').forEach(link => {
        link.classList.remove('active');
      });
      document.querySelector(`.sidebar li[onclick="showSection('${id}')"]`).classList.add('active');
      
      // Scroll to top
      window.scrollTo(0, 0);
    }

    // Fetch teachers dynamically
    window.onload = function () {
      fetch("get_teachers.php")
        .then(res => res.json())
        .then(data => {
          const dropdown = document.getElementById("teacherSelect");
          data.forEach(t => {
            const option = document.createElement("option");
            option.value = `${t.name}|${t.dept}`; // format: "name|dept"
            option.textContent = `${t.name} (${t.dept})`;
            dropdown.appendChild(option);
          });
        });

      // Auto-scroll chat to bottom
      const chatBox = document.getElementById('chatBox');
      chatBox.scrollTop = chatBox.scrollHeight;
    };

    // Load profile picture if exists
    function loadProfilePicture() {
      fetch('get_profile_picture.php')
        .then(response => response.json())
        .then(data => {
          if (data.imageUrl) {
            const avatar = document.querySelector('.user-avatar');
            avatar.innerHTML = '';
            const img = document.createElement('img');
            img.src = data.imageUrl;
            avatar.appendChild(img);
          }
        });
    }

    // Call the function when page loads
    loadProfilePicture();
  </script>
</body>
</html>