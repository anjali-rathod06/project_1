<?php
include 'config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$resume_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $conn->prepare("DELETE FROM resumes WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $resume_id, $user_id);
$stmt->execute();

header("Location: dashboard.php");
exit();
?>
