<?php
include 'config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $resume_id = isset($_POST['resume_id']) ? (int)$_POST['resume_id'] : 0;

    $title = trim($_POST['title']);
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $objective = trim($_POST['objective']);
    $template = isset($_POST['template']) ? trim($_POST['template']) : 'classic';

    // Handle photo upload
    $photo_name = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, $allowed)) {
            $photo_name = "resume_" . time() . "_" . rand(1000,9999) . "." . $ext;
            move_uploaded_file($_FILES['photo']['tmp_name'], "uploads/" . $photo_name);
        }
    }

    if ($resume_id > 0) {
        // Verify this resume belongs to the logged-in user
        $check = $conn->prepare("SELECT id FROM resumes WHERE id = ? AND user_id = ?");
        $check->bind_param("ii", $resume_id, $user_id);
        $check->execute();
        if ($check->get_result()->num_rows == 0) {
            die("Unauthorized action.");
        }

        if ($photo_name) {
            $stmt = $conn->prepare("UPDATE resumes SET title=?, full_name=?, email=?, phone=?, address=?, objective=?, photo=?, template=? WHERE id=?");
            $stmt->bind_param("ssssssssi", $title, $full_name, $email, $phone, $address, $objective, $photo_name, $template, $resume_id);
        } else {
            $stmt = $conn->prepare("UPDATE resumes SET title=?, full_name=?, email=?, phone=?, address=?, objective=?, template=? WHERE id=?");
            $stmt->bind_param("sssssssi", $title, $full_name, $email, $phone, $address, $objective, $template, $resume_id);
        }
        $stmt->execute();

        // Clear old related data before re-inserting
        $conn->query("DELETE FROM education WHERE resume_id = $resume_id");
        $conn->query("DELETE FROM experience WHERE resume_id = $resume_id");
        $conn->query("DELETE FROM skills WHERE resume_id = $resume_id");

    } else {
        $stmt = $conn->prepare("INSERT INTO resumes (user_id, title, full_name, email, phone, address, objective, photo, template) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssssss", $user_id, $title, $full_name, $email, $phone, $address, $objective, $photo_name, $template);
        $stmt->execute();
        $resume_id = $stmt->insert_id;
    }

    // Save education rows
    if (isset($_POST['degree'])) {
        $stmt = $conn->prepare("INSERT INTO education (resume_id, degree, institution, year, percentage) VALUES (?, ?, ?, ?, ?)");
        foreach ($_POST['degree'] as $i => $degree) {
            $degree = trim($degree);
            $institution = trim($_POST['institution'][$i]);
            $year = trim($_POST['year'][$i]);
            $percentage = trim($_POST['percentage'][$i]);
            if ($degree == "" && $institution == "") continue;
            $stmt->bind_param("issss", $resume_id, $degree, $institution, $year, $percentage);
            $stmt->execute();
        }
    }

    // Save experience rows
    if (isset($_POST['company'])) {
        $stmt = $conn->prepare("INSERT INTO experience (resume_id, company, role, duration, description) VALUES (?, ?, ?, ?, ?)");
        foreach ($_POST['company'] as $i => $company) {
            $company = trim($company);
            $role = trim($_POST['role'][$i]);
            $duration = trim($_POST['duration'][$i]);
            $description = trim($_POST['description'][$i]);
            if ($company == "" && $role == "") continue;
            $stmt->bind_param("issss", $resume_id, $company, $role, $duration, $description);
            $stmt->execute();
        }
    }

    // Save skills
    if (isset($_POST['skill_name'])) {
        $stmt = $conn->prepare("INSERT INTO skills (resume_id, skill_name) VALUES (?, ?)");
        foreach ($_POST['skill_name'] as $skill) {
            $skill = trim($skill);
            if ($skill == "") continue;
            $stmt->bind_param("is", $resume_id, $skill);
            $stmt->execute();
        }
    }

    header("Location: view_resume.php?id=" . $resume_id);
    exit();
}
?>
