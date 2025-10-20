<?php
// Include initialization script
require_once __DIR__ . '/start.php';

// Include configuration and language files
require_once __DIR__ . '/../include/config/config.php'; // BASE_URL definition
require_once __DIR__ . '/../include/locale/' . $_SESSION['langue'] . '.php';

// Set default language if not set
if (!isset($_SESSION['langue']) || empty($_SESSION['langue'])) {
    $_SESSION['langue'] = 'en'; // Default to 'en'
}

// Handle language change from form submission
if (isset($_GET['langue'])) {
    if (in_array($_GET['langue'], AVAILABLE_LANGUAGES)) {
        $_SESSION['langue'] = $_GET['langue'];
    }
    header('Location: ' . BASE_URL . 'public/index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($_SESSION['langue']); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>../assets/css/main.css">
    <title><?php echo htmlspecialchars($lang['NAV_CONTACT_TITLE']); ?></title>
</head>
<body>
    <header>
        <?php require_once __DIR__ . '/../include/header.php'; ?>
    </header>
    
</body>
</html>