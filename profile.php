<?php
session_start();
require_once "database.php";

// Redirect if not logged in
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user"];

// Handle profile update (name, gender, and picture)
if (isset($_POST['save'])) {
    $firstName = trim($_POST['first_name'] ?? '');
    $middleName = trim($_POST['middle_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $gender = $_POST['gender'] ?? '';
    $profilePic = null;

    // Handle image upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileTmpPath = $_FILES['profile_picture']['tmp_name'];
        $fileName = basename($_FILES['profile_picture']['name']);
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileExt, $allowed)) {
            $newFileName = "profile_" . $user_id . "_" . time() . "." . $fileExt;
            $dest = $uploadDir . $newFileName;
            move_uploaded_file($fileTmpPath, $dest);
            $profilePic = $dest;
        }
    }

    // Update database
    if ($profilePic) {
        $stmt = mysqli_prepare($conn, "UPDATE users SET first_name=?, middle_name=?, last_name=?, gender=?, profile_picture=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, "sssssi", $firstName, $middleName, $lastName, $gender, $profilePic, $user_id);
    } else {
        $stmt = mysqli_prepare($conn, "UPDATE users SET first_name=?, middle_name=?, last_name=?, gender=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, "ssssi", $firstName, $middleName, $lastName, $gender, $user_id);
    }

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

// Fetch user data
$stmt = mysqli_prepare($conn, "SELECT first_name, middle_name, last_name, gender, email, profile_picture, created_at FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result) ?? [];

// Full name
$full_name = trim(($user['first_name'] ?? '') . ' ' . ($user['middle_name'] ?? '') . ' ' . ($user['last_name'] ?? ''));
$profileImage = !empty($user['profile_picture']) ? $user['profile_picture'] : 'default-profile.jpg';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Profile Page</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<style>
body {
  margin: 0;
  font-family: 'Poppins', sans-serif;
  background-color: #f8f9fa;
}

/* Sidebar */
.sidebar {
  position: fixed;
  top: 0;
  left: 0;
  height: 100%;
  width: 260px;
  background-color: #394E25;
  color: white;
  padding-top: 20px;
  overflow-y: auto;
}

/* Sidebar user header */
.sidebar-profile {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 0 20px;
  margin-bottom: 30px;
}

.sidebar-profile img {
  width: 55px;
  height: 55px;
  border-radius: 50%;
  border: 2px solid #fff;
  object-fit: cover;
}

.sidebar-profile .user-info h5 {
  font-size: 1rem;
  margin: 0;
  color: #fff;
}

.sidebar-profile .user-info span {
  font-size: 0.85rem;
  color: #d8e3cc;
}

/* Sidebar Links */
.sidebar a {
  display: block;
  padding: 12px 20px;
  color: white;
  text-decoration: none;
  transition: 0.3s;
}

.sidebar a:hover,
.sidebar a.active {
  background-color: #7ba950;
  border-radius: 6px;
}

/* Main content */
.main-content {
  margin-left: 280px;
  padding: 40px;
}

/* Profile Card */
.profile-card {
  background-color: white;
  border-radius: 15px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  padding: 30px;
  max-width: 800px;
  margin: auto;
}

/* Profile Image + Info (Top) */
.profile-header {
  display: flex;
  align-items: center;
  gap: 25px;
  margin-bottom: 30px;
}

.profile-header img {
  width: 130px;
  height: 130px;
  border-radius: 50%;
  object-fit: cover;
  border: 3px solid #394E25;
}

.profile-header .info h3 {
  margin: 0;
  color: #394E25;
  font-weight: 700;
}

.profile-header .info p {
  margin: 5px 0;
  color: #666;
}

/* Buttons */
.btn-save {
  background-color: #394E25;
  color: white;
  border: none;
  padding: 10px 25px;
  border-radius: 8px;
  transition: 0.3s;
}

.btn-save:hover {
  background-color: #5c7c38;
}
</style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <div class="sidebar-profile">
    <img src="<?= htmlspecialchars($profileImage) ?>" alt="Profile">
    <div class="user-info">
      <h5><?= htmlspecialchars($full_name ?: 'User Name') ?></h5>
      <span><?= htmlspecialchars($user['email'] ?? '') ?></span>
    </div>
  </div>

  <a href="profile.php" class="active"><i class="bi bi-person-circle me-2"></i>Profile</a>
  <a href="Wellness.html"><i class="bi bi-heart-pulse me-2"></i>Wellness</a>
  <a href="Study_tips.html"><i class="bi bi-lightbulb me-2"></i>Study Tips</a>
  <a href="calendar.php"><i class="bi bi-calendar-event me-2"></i>Calendar</a>
  <a href="Peer_group.html"><i class="bi bi-bell me-2"></i>Reminder</a>
  <a href="Mentor.html"><i class="bi bi-mortarboard me-2"></i>Mentor</a>
  <a href="#"><i class="bi bi-people me-2"></i>Peer Group</a>
  <hr class="text-white mx-3">
  <a href="#"><i class="bi bi-gear me-2"></i>Settings</a>
  <a href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a>
</div>

<!-- Main Content -->
<div class="main-content">
  <div class="profile-card">
    <div class="profile-header">
      <img src="<?= htmlspecialchars($profileImage) ?>" alt="Profile Picture">
      <div class="info">
        <h3><?= htmlspecialchars($full_name ?: 'User Name') ?></h3>
        <p><?= htmlspecialchars($user['email'] ?? '') ?></p>
      </div>
    </div>

    <form method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label class="form-label">Profile Picture</label>
        <input type="file" name="profile_picture" class="form-control" accept="image/*">
      </div>

      <div class="row">
        <div class="col-md-4">
          <label class="form-label">First Name</label>
          <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($user['first_name'] ?? '') ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label">Middle Name</label>
          <input type="text" name="middle_name" class="form-control" value="<?= htmlspecialchars($user['middle_name'] ?? '') ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label">Last Name</label>
          <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($user['last_name'] ?? '') ?>">
        </div>
      </div>

      <div class="mb-3 mt-3">
        <label class="form-label">Gender</label>
        <select name="gender" class="form-select">
          <option value="Male" <?= ($user['gender'] ?? '') == 'Male' ? 'selected' : '' ?>>Male</option>
          <option value="Female" <?= ($user['gender'] ?? '') == 'Female' ? 'selected' : '' ?>>Female</option>
          <option value="Other" <?= ($user['gender'] ?? '') == 'Other' ? 'selected' : '' ?>>Other</option>
        </select>
      </div>

      <div class="text-center mt-4">
        <button type="submit" name="save" class="btn-save">Save Changes</button>
      </div>
    </form>
  </div>
</div>

</body>
</html>
