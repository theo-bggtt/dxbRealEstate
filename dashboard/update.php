<?php
require_once '../start.php';
require_once '../include/config/config.php';
require_once '../include/locale/' . $_SESSION['langue'] . '.php';

if (!isset($_SESSION["username"]) || empty($_SESSION["username"])) {
    header("Location: login");
    exit;
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $bio = filter_input(INPUT_POST, 'bio', FILTER_SANITIZE_STRING);
    $language = filter_input(INPUT_POST, 'language', FILTER_SANITIZE_STRING);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = $lang['INVALID_EMAIL'] ?? 'Invalid email format';
    }

    if (empty($errors)) {
        if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../uploads/';
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $max_size = 5 * 1024 * 1024;

            if (in_array($_FILES['profile_pic']['type'], $allowed_types) && $_FILES['profile_pic']['size'] <= $max_size) {
                $ext = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
                $filename = uniqid('profile_') . '.' . $ext;
                $upload_path = $upload_dir . $filename;

                if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $upload_path)) {
                    $_SESSION['profile_pic'] = ASSETS_URL . 'uploads/' . $filename;

                    if (isset($_SESSION['profile_pic']) && file_exists('../' . str_replace(ASSETS_URL, '', $_SESSION['profile_pic']))) {
                        unlink('../' . str_replace(ASSETS_URL, '', $_SESSION['profile_pic']));
                    }
                } else {
                    $errors[] = $lang['UPLOAD_FAILED'] ?? 'Failed to upload profile picture';
                }
            } else {
                $errors[] = $lang['INVALID_FILE'] ?? 'Invalid file type or size';
            }
        }

        if (empty($errors)) {
            $_SESSION['email'] = $email;
            $_SESSION['bio'] = $bio;
            $_SESSION['langue'] = $language;

            $success = true;
        }
    }
}

header('Location: ' . BASE_URL . 'dashboard/settings');
exit;
?>