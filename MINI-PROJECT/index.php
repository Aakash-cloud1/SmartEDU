<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Welcome to EduEase</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      margin: 0;
      background-image: url('bg-image.jpg');
      background-size: cover;
      background-position: center;
      animation: fadeInBody 2s ease-in;
    }

    @keyframes fadeInBody {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    .welcome-container {
      text-align: center;
      background-color: rgba(255, 255, 255, 0.88);
      padding: 50px;
      border-radius: 16px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
      animation: slideUp 1.5s ease-out;
    }

    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translateY(50px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .welcome-container h1 {
      font-size: 40px;
      margin-bottom: 30px;
      color: #333;
    }

    .welcome-container a {
      display: inline-block;
      text-decoration: none;
      background-color: #749474;
      color: #fff;
      padding: 14px 30px;
      border-radius: 10px;
      font-size: 20px;
      transition: all 0.4s ease;
    }

    .welcome-container a:hover {
      background-color: #5f7a5f;
      transform: scale(1.05);
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="welcome-container">
      <h1>Welcome to EduEase</h1>
      <a href="login.php">Click Here to Login / Register</a>
    </div>
  </div>
</body>
</html>
