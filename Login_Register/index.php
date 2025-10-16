<?php
session_start();
if (!isset($_SESSION["user"])) {
   header("Location: login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

    <title>User Dashboard</title>




    <style>
    body {
      min-height: 100vh;
      margin: 0;
      overflow-x: hidden;
      background-color: #f8f9fa;
      font-family: 'Poppins', sans-serif;
    }

    /* Sidebar (Right Side) */
    .sidebar {
      width: 250px;
      background-color: #394E25;
      color: white;
      position: fixed;
      top: 0;
      right: 0;
      height: 100%;
      padding: 20px 0;
      transition: transform 0.3s ease;
      z-index: 999;
      transform: translateX(250px);
    }

    .sidebar.show {
      transform: translateX(0);
    }

    .sidebar a {
      color: white;
      text-decoration: none;
      display: block;
      padding: 12px 20px;
      transition: background 0.3s;
    }

    .sidebar a:hover,
    .sidebar a.active {
      background-color: #7ba950;
    }

    /* Image Button (Top Right Corner) */
    .menu-img {
      position: fixed;
      top: 15px;
      right: 15px;
      width: 60px;
      height: 60px;
      border-radius: 50%;
      cursor: pointer;
      object-fit: cover;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
      transition: transform 0.2s ease, box-shadow 0.3s ease;
      z-index: 1000;
    }

    .menu-img:hover {
      transform: scale(1.05);
      box-shadow: 0 6px 15px rgba(0, 0, 0, 0.4);
    }

    /* Main Content */
    .content {
      padding: 60px 40px;
      text-align: center;
    }

    /* Welcome Section */
    .welcome-section {
      margin-top: 40px;
      margin-bottom: 60px;
    }

    .welcome-section h2 {
      font-size: 3rem;
      font-weight: 700;
      background: linear-gradient(90deg, #4CAF50, #8BC34A);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      text-shadow: 2px 2px 8px rgba(0,0,0,0.1);
      margin-bottom: 25px;
    }

    .welcome-section img {
      max-width: 100%;
      border-radius: 20px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.2);
      margin: 20px 0;
    }

    .welcome-section p {
      font-size: 1.2rem;
      color: #444;
      max-width: 700px;
      margin: 0 auto 30px;
      line-height: 1.6;
    }

    .welcome-section video {
      width: 80%;
      border-radius: 15px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.3);
    }
       footer {
      text-align: center;
      padding: 15px 0;
      background-color: #394E25;
      color: white;
      font-size: 0.9rem;
    }
  </style>
</head>







   
<body>
  <!-- Image Button -->
  <img src="logo.png" alt="Menu Icon" class="menu-img" id="menuToggle">

  <!-- Sidebar (Right Side) -->
  <div class="sidebar" id="sidebar">
    <h4 class="text-center mb-4">My App</h4>

    <a href="profile.php" class="active"><i class="bi bi-person-circle me-2"></i>Profile</a>
    <a href="profile.php"><i class="bi bi-heart-pulse me-2"></i>Wellness</a>
    <a href="Study_tips.html"><i class="bi bi-lightbulb me-2"></i>Study Tips</a>
    <a href="calendar.php"><i class="bi bi-calendar-event me-2"></i>Calendar</a>
    <a href="Peer_group.html"><i class="bi bi-bell me-2"></i>Reminder</a>
    <a href="Mentor.html"><i class="bi bi-mortarboard me-2"></i>Mentor</a>
    <a href="#"><i class="bi bi-people me-2"></i>Peer Group</a>

    <hr class="text-white mx-3">

    <a href="#"><i class="bi bi-gear me-2"></i>Settings</a>
    <a href="logout.php"><i class="bi bi-logout me-2"></i>logout</a>
    </div>
  </div>


  <!-- Main Content -->
  <div class="content container">
    <div class="welcome-section">
      <h2>🌿 Welcome to Your New Journey, Freshies! 🌿</h2>

      <img src="c:\Users\Jaspir\Downloads\welcome-students-banner-flat-vector-260nw-2187608913.webp" alt="Welcome Image">

      <p>
        Congratulations on beginning this exciting new chapter!  
        Our platform is here to help you grow, connect, and thrive.  
        Explore wellness tips, meet your mentors, and unlock tools  
        to make your student life meaningful and fun.  
        Welcome to the community — your adventure starts here!
      </p>

      <video controls>
        <source src="https://www.w3schools.com/html/mov_bbb.mp4" type="video/mp4">
        Your browser does not support the video tag.
      </video>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    const menuImg = document.getElementById('menuToggle');
    const sidebar = document.getElementById('sidebar');

    // Sidebar opens when hovering the image
    menuImg.addEventListener('mouseenter', () => {
      sidebar.classList.add('show');
    });

    // Keep open while hovering sidebar
    sidebar.addEventListener('mouseenter', () => {
      sidebar.classList.add('show');
    });

    // Close when mouse leaves image or sidebar
    menuImg.addEventListener('mouseleave', () => {
      setTimeout(() => {
        if (!sidebar.matches(':hover')) sidebar.classList.remove('show');
      }, 200);
    });

    sidebar.addEventListener('mouseleave', () => {
      sidebar.classList.remove('show');
    });
  </script>

    <footer>
    © 2025 Study Success Guide | Designed for First Year College Students
  </footer>
</body>
</html>