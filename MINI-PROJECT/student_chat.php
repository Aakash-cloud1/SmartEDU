<?php
session_start();
include 'config.php';

if (!isset($_SESSION['name'])) {
    header('Location: login.php');
    exit();
}

$name = $_SESSION['name'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['message'])) {
    $message = trim($_POST['message']);
    $stmt = $conn->prepare("INSERT INTO chat_messages (student_name, message) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $message);
    $stmt->execute();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Student Interaction</title>
  <style>
    body {
      font-family: Arial;
      background: #f1f1f1;
    }
    .chat-box {
      width: 60%;
      margin: auto;
      margin-top: 50px;
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      height: 500px;
      overflow-y: scroll;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .message {
      margin-bottom: 15px;
    }
    .message strong {
      color: #2c3e50;
    }
    .message span {
      font-size: 0.8em;
      color: #999;
    }
    form {
      width: 60%;
      margin: auto;
      margin-top: 20px;
      display: flex;
      gap: 10px;
    }
    textarea {
      flex: 1;
      padding: 10px;
      border-radius: 5px;
    }
    button {
      padding: 10px 20px;
      background: #3498db;
      color: #fff;
      border: none;
      border-radius: 5px;
    }
  </style>
</head>
<body>

  <div class="chat-box" id="chatBox">
    <?php
    $result = $conn->query("SELECT * FROM chat_messages ORDER BY timestamp DESC LIMIT 50");
    while ($row = $result->fetch_assoc()) {
      echo "<div class='message'><strong>{$row['student_name']}:</strong> {$row['message']}<br><span>{$row['timestamp']}</span></div>";
    }
    ?>
  </div>

  <form method="POST">
    <textarea name="message" required placeholder="Type your message..."></textarea>
    <button type="submit">Send</button>
  </form>

  <script>
    // Auto scroll to bottom
    var chatBox = document.getElementById('chatBox');
    chatBox.scrollTop = chatBox.scrollHeight;
  </script>

</body>
</html>
