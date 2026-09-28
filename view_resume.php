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
$education = $edu_stmt->get_result();

$exp_stmt = $conn->prepare("SELECT * FROM experience WHERE resume_id = ?");
$exp_stmt->bind_param("i", $resume_id);
$exp_stmt->execute();
$experience = $exp_stmt->get_result();

$skill_stmt = $conn->prepare("SELECT * FROM skills WHERE resume_id = ?");
$skill_stmt->bind_param("i", $resume_id);
$skill_stmt->execute();
$skills = $skill_stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?php echo htmlspecialchars($resume['title']); ?> - Resume Builder</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="navbar no-print">
    <strong>Resume Builder</strong>
    <div>
        <a href="dashboard.php">Dashboard</a>
        <a href="edit_resume.php?id=<?php echo $resume_id; ?>">Edit</a>
        <a href="javascript:window.print()">Print / Download PDF</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="resume-preview template-<?php echo htmlspecialchars($resume['template'] ?: 'classic'); ?>">
    <?php if (!empty($resume['photo'])): ?>
        <img src="uploads/<?php echo htmlspecialchars($resume['photo']); ?>"
             style="width:110px; height:110px; object-fit:cover; border-radius:50%; float:right; border:3px solid #3498db;">
    <?php endif; ?>
    <h1><?php echo htmlspecialchars($resume['full_name']); ?></h1>
    <div class="contact-info">
        <?php echo htmlspecialchars($resume['email']); ?>
        <?php if ($resume['phone']) echo " &middot; " . htmlspecialchars($resume['phone']); ?>
        <?php if ($resume['address']) echo " &middot; " . htmlspecialchars($resume['address']); ?>
    </div>

    <?php if ($resume['objective']): ?>
        <h3>Objective</h3>
        <p><?php echo nl2br(htmlspecialchars($resume['objective'])); ?></p>
    <?php endif; ?>

    <?php if ($education->num_rows > 0): ?>
        <h3>Education</h3>
        <?php while ($e = $education->fetch_assoc()): ?>
            <div class="entry">
                <div class="row">
                    <span><?php echo htmlspecialchars($e['degree']); ?></span>
                    <span><?php echo htmlspecialchars($e['year']); ?></span>
                </div>
                <div style="color:#666; font-size:14px;">
                    <?php echo htmlspecialchars($e['institution']); ?>
                    <?php if ($e['percentage']) echo " &middot; " . htmlspecialchars($e['percentage']); ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>

    <?php if ($experience->num_rows > 0): ?>
        <h3>Experience</h3>
        <?php while ($x = $experience->fetch_assoc()): ?>
            <div class="entry">
                <div class="row">
                    <span><?php echo htmlspecialchars($x['role']); ?> - <?php echo htmlspecialchars($x['company']); ?></span>
                    <span><?php echo htmlspecialchars($x['duration']); ?></span>
                </div>
                <div style="color:#666; font-size:14px;">
                    <?php echo nl2br(htmlspecialchars($x['description'])); ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>

    <?php if ($skills->num_rows > 0): ?>
        <h3>Skills</h3>
        <div>
            <?php while ($s = $skills->fetch_assoc()): ?>
                <span class="skill-tag"><?php echo htmlspecialchars($s['skill_name']); ?></span>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
