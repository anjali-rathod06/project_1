<?php
include 'config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$resume_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $conn->prepare("SELECT * FROM resumes WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $resume_id, $user_id);
$stmt->execute();
$resume = $stmt->get_result()->fetch_assoc();

if (!$resume) {
    die("Resume not found.");
}

$edu_stmt = $conn->prepare("SELECT * FROM education WHERE resume_id = ?");
$edu_stmt->bind_param("i", $resume_id);
$edu_stmt->execute();
$education = $edu_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$exp_stmt = $conn->prepare("SELECT * FROM experience WHERE resume_id = ?");
$exp_stmt->bind_param("i", $resume_id);
$exp_stmt->execute();
$experience = $exp_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$skill_stmt = $conn->prepare("SELECT * FROM skills WHERE resume_id = ?");
$skill_stmt->bind_param("i", $resume_id);
$skill_stmt->execute();
$skills = $skill_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Resume - Resume Builder</title>
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
    <h2>Edit resume</h2>

    <form method="POST" action="save_resume.php" enctype="multipart/form-data">
        <input type="hidden" name="resume_id" value="<?php echo $resume_id; ?>">

        <h3>Basic details</h3>
        <label>Resume title</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($resume['title']); ?>" required>

        <label>Photo</label>
        <?php if (!empty($resume['photo'])): ?>
            <div style="margin-bottom:8px;">
                <img src="uploads/<?php echo htmlspecialchars($resume['photo']); ?>" style="width:80px; height:80px; object-fit:cover; border-radius:8px;">
            </div>
        <?php endif; ?>
        <input type="file" name="photo" accept="image/*">
        <small style="color:#777;">Photo badalva mate j navi file select karo, nahi to juni j rehshe.</small>

        <label>Choose template</label>
        <div>
            <label class="template-option">
                <input type="radio" name="template" value="classic" <?php if ($resume['template']=='classic' || !$resume['template']) echo 'checked'; ?>> Classic
            </label>
            <label class="template-option">
                <input type="radio" name="template" value="modern" <?php if ($resume['template']=='modern') echo 'checked'; ?>> Modern
            </label>
            <label class="template-option">
                <input type="radio" name="template" value="minimal" <?php if ($resume['template']=='minimal') echo 'checked'; ?>> Minimal
            </label>
        </div>

        <label>Full name</label>
        <input type="text" name="full_name" value="<?php echo htmlspecialchars($resume['full_name']); ?>" required>

        <label>Email</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($resume['email']); ?>" required>

        <label>Phone</label>
        <input type="text" name="phone" value="<?php echo htmlspecialchars($resume['phone']); ?>">

        <label>Address</label>
        <input type="text" name="address" value="<?php echo htmlspecialchars($resume['address']); ?>">

        <label>Career objective</label>
        <textarea name="objective"><?php echo htmlspecialchars($resume['objective']); ?></textarea>

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
        <button type="submit" class="btn" style="margin-top:25px;">Update resume</button>
        <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<script src="js/script.js"></script>
<script>
    const educationData = <?php echo json_encode($education); ?>;
    const experienceData = <?php echo json_encode($experience); ?>;
    const skillsData = <?php echo json_encode($skills); ?>;

    if (educationData.length > 0) {
        educationData.forEach(e => addEducation(e));
    } else {
        addEducation();
    }

    if (experienceData.length > 0) {
        experienceData.forEach(x => addExperience(x));
    } else {
        addExperience();
    }

    if (skillsData.length > 0) {
        skillsData.forEach(s => addSkill(s));
    } else {
        addSkill();
    }
</script>

</body>
</html>
