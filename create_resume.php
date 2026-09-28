<?php
include 'config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Create Resume - Resume Builder</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="navbar">
    <strong>Resume Builder</strong>
    <div>
        <a href="dashboard.php">Dashboard</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container-wide">
    <h2>Create new resume</h2>

    <form method="POST" action="save_resume.php" enctype="multipart/form-data">

        <h3>Basic details</h3>
        <label>Resume title (e.g. "Software Developer Resume")</label>
        <input type="text" name="title" required>

        <label>Photo</label>
        <input type="file" name="photo" accept="image/*">

        <label>Choose template</label>
        <div>
            <label class="template-option">
                <input type="radio" name="template" value="classic" checked> Classic
            </label>
            <label class="template-option">
                <input type="radio" name="template" value="modern"> Modern
            </label>
            <label class="template-option">
                <input type="radio" name="template" value="minimal"> Minimal
            </label>
        </div>

        <label>Full name</label>
        <input type="text" name="full_name" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Phone</label>
        <input type="text" name="phone">

        <label>Address</label>
        <input type="text" name="address">

        <label>Career objective</label>
        <textarea name="objective" placeholder="A short summary about yourself..."></textarea>

        <h3>Education</h3>
        <div id="education-wrap"></div>
        <button type="button" class="btn btn-small" onclick="addEducation()">+ Add education</button>

        <h3>Experience</h3>
        <div id="experience-wrap"></div>
        <button type="button" class="btn btn-small" onclick="addExperience()">+ Add experience</button>

        <h3>Skills</h3>
        <div id="skills-wrap"></div>
        <button type="button" class="btn btn-small" onclick="addSkill()">+ Add skill</button>

        <br>
        <button type="submit" class="btn" style="margin-top:25px;">Save resume</button>
        <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<script src="js/script.js"></script>
<script>
    // Add one row of each by default
    addEducation();
    addExperience();
    addSkill();
</script>

</body>
</html>
