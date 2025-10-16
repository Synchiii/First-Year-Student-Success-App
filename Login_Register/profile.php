<?php
session_start();
require_once "database.php";

// Redirect if not logged in
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user"];

// Update profile if form submitted
if (isset($_POST['save'])) {
    $firstName = trim($_POST['first_name'] ?? '');
    $middleName = trim($_POST['middle_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $suffix = trim($_POST['suffix'] ?? '');
    $age = intval($_POST['age'] ?? 0);
    $sex = $_POST['sex'] ?? '';
    $birthDate = $_POST['birth_date'] ?? '';
    $contact = trim($_POST['contact'] ?? '');
    $course = trim($_POST['course'] ?? '');
    $section = trim($_POST['section'] ?? '');

    $stmt = mysqli_prepare($conn, "UPDATE users SET first_name=?, middle_name=?, last_name=?, suffix=?, age=?, sex=?, birth_date=?, contact=?, course=?, section=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, "ssssisssssi", $firstName, $middleName, $lastName, $suffix, $age, $sex, $birthDate, $contact, $course, $section, $user_id);
    mysqli_stmt_execute($stmt);
}

// Fetch user data
$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result) ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>User Profile</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {background-color: #f8f9fa; margin:0; font-family: 'Poppins', sans-serif; min-height: 100vh;}
.profile-container {display: flex; justify-content: center; align-items: center; padding: 40px;}
.profile-card {width: 100%; max-width: 900px; background-color: #fff; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); padding: 40px;}
.profile-header {display: flex; align-items: center; gap: 30px; margin-bottom: 30px; border-bottom: 2px solid #e9ecef; padding-bottom: 20px;}
.profile-header img {width: 130px; height: 130px; border-radius: 50%; object-fit: cover; border: 4px solid #394E25;}
.back-btn {position: fixed; top: 20px; left: 20px;}
.save-btn {background-color: #394E25; border: none;}
.save-btn:hover {background-color: #506B33;}

/* Sidebar styles */
.sidebar {width: 250px; background-color: #394E25; color: white; position: fixed; top:0; right:0; height:100%; padding:20px 0; transition: transform 0.3s ease; z-index:999; transform: translateX(250px);}
.sidebar.show {transform: translateX(0);}
.sidebar a {color:white; text-decoration:none; display:block; padding:12px 20px; transition: background 0.3s;}
.sidebar a:hover, .sidebar a.active {background-color: #7ba950;}
.menu-img {position: fixed; top: 15px; right: 15px; width: 60px; height: 60px; border-radius:50%; cursor:pointer; object-fit: cover; box-shadow: 0 4px 10px rgba(0,0,0,0.3); transition: transform 0.2s ease, box-shadow 0.3s ease; z-index:1000;}
.menu-img:hover {transform: scale(1.05); box-shadow:0 6px 15px rgba(0,0,0,0.4);}
</style>
</head>
<body>

<!-- Sidebar trigger -->
<img src="logo.png" alt="Menu Icon" class="menu-img" id="menuToggle">

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <h4 class="text-center mb-4">My App</h4>
    <a href="profile.php" class="active">Profile</a>
    <a href="Wellness.html">Wellness</a>
    <a href="Study_tips.html">Study Tips</a>
    <a href="Calendar.html">Calendar</a>
    <a href="Peer_group.html">Reminder</a>
    <a href="Mentor.html">Mentor</a>
    <hr class="text-white mx-3">
    <a href="#">Settings</a>
    <a href="logout.php">Logout</a>
</div>

<!-- Back Button -->
<button class="btn btn-secondary back-btn" onclick="window.location.href='index.php'">← Back</button>

<!-- Profile content -->
<div class="profile-container">
<div class="profile-card">
    <div class="profile-header">
        <img src="https://via.placeholder.com/130" alt="User Photo">
        <div>
            <h2 class="fw-bold">User Profile</h2>
            <p class="text-muted">View or edit your personal details below</p>
        </div>
    </div>

    <form id="profileForm" method="post">
        <div class="row g-3">
            <?php function val($user,$key){return htmlspecialchars($user[$key]??'');} ?>
            <div class="col-md-6"><label class="form-label fw-semibold">First Name</label><input type="text" name="first_name" class="form-control" value="<?=val($user,'first_name')?>" readonly></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Middle Name</label><input type="text" name="middle_name" class="form-control" value="<?=val($user,'middle_name')?>" readonly></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Last Name</label><input type="text" name="last_name" class="form-control" value="<?=val($user,'last_name')?>" readonly></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Suffix</label><input type="text" name="suffix" class="form-control" value="<?=val($user,'suffix')?>" readonly></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Age</label><input type="number" name="age" class="form-control" value="<?=val($user,'age')?>" readonly></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Sex</label>
                <select name="sex" class="form-control" disabled>
                    <option value="m" <?= (val($user,'sex')=='m')?'selected':''?>>Male</option>
                    <option value="f" <?= (val($user,'sex')=='f')?'selected':''?>>Female</option>
                    <option value="o" <?= (val($user,'sex')=='o')?'selected':''?>>Other</option>
                </select>
            </div>
            <div class="col-md-6"><label class="form-label fw-semibold">Birth Date</label><input type="date" name="birth_date" class="form-control" value="<?=val($user,'birth_date')?>" readonly></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Contact No.</label><input type="text" name="contact" class="form-control" value="<?=val($user,'contact')?>" readonly></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Course</label><input type="text" name="course" class="form-control" value="<?=val($user,'course')?>" readonly></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Section</label><input type="text" name="section" class="form-control" value="<?=val($user,'section')?>" readonly></div>
        </div>

        <div class="text-end mt-4">
            <button type="button" id="editBtn" class="btn btn-primary me-2">Edit</button>
            <button type="submit" name="save" id="saveBtn" class="btn save-btn text-white" disabled>Save</button>
        </div>
    </form>
</div>
</div>

<script>
const editBtn = document.getElementById("editBtn");
const saveBtn = document.getElementById("saveBtn");
const inputs = document.querySelectorAll("#profileForm input, #profileForm select");

// Make fields editable
editBtn.addEventListener("click", () => {
    inputs.forEach(input => input.removeAttribute("readonly"));
    document.querySelector("select[name='sex']").removeAttribute("disabled");
    saveBtn.disabled = false;
    editBtn.disabled = true;
});

// Sidebar hover
const menuImg = document.getElementById('menuToggle');
const sidebar = document.getElementById('sidebar');
menuImg.addEventListener('mouseenter',()=>{sidebar.classList.add('show');});
sidebar.addEventListener('mouseenter',()=>{sidebar.classList.add('show');});
menuImg.addEventListener('mouseleave',()=>{setTimeout(()=>{if(!sidebar.matches(':hover')) sidebar.classList.remove('show');},200);});
sidebar.addEventListener('mouseleave',()=>{sidebar.classList.remove('show');});
</script>

</body>
</html>
