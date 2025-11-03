<?php
require_once '../start.php';
require_once __DIR__ . '/config/config.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['langue'])) {
    $langue = filter_input(INPUT_POST, 'langue', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $allowed_languages = ['en', 'fr']; // Add more as needed

    if (in_array($langue, $allowed_languages)) {
        $_SESSION['langue'] = $langue;
        // Optionally, reload the language file
        require_once __DIR__ . '/locale/' . $_SESSION['langue'] . '.php';
    }
}

// Redirect back to the referring page or a default page
$redirect_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : BASE_URL . 'index';
header("Location: $redirect_url");
exit;
?>