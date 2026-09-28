<?php
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT id, title, full_name, created_at FROM resumes WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$resumes = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dashboard - Resume Builder</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="navbar">
    <strong>Resume Builder</strong>
    <div>
        <span>Hello, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container-wide">
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h2 style="margin:0;">My resumes</h2>
        <a href="create_resume.php" class="btn">+ New resume</a>
    </div>

    <div style="margin-top:25px;">
        <?php if ($resumes->num_rows == 0): ?>
            <p style="color:#777;">You haven't created any resume yet. Click "New resume" to get started.</p>
        <?php else: ?>
            <?php while ($row = $resumes->fetch_assoc()): ?>
                <div class="resume-card">
                    <div>
                        <strong><?php echo htmlspecialchars($row['title']); ?></strong><br>
                        <span style="color:#777; font-size:13px;">
                            <?php echo htmlspecialchars($row['full_name']); ?> &middot;
                            Created on <?php echo date('d M Y', strtotime($row['created_at'])); ?>
                        </span>
                    </div>
                    <div>
                        <a href="view_resume.php?id=<?php echo $row['id']; ?>" class="btn btn-small">View</a>
                        <a href="edit_resume.php?id=<?php echo $row['id']; ?>" class="btn btn-small btn-secondary">Edit</a>
                        <a href="delete_resume.php?id=<?php echo $row['id']; ?>" class="btn btn-small btn-danger"
                           onclick="return confirm('Delete this resume?');">Delete</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
