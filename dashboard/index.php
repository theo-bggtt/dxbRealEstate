<?php
// Include initialization script
require_once '../start.php';

// Include configuration and language files
require_once '../include/config/config.php'; // BASE_URL and ASSETS_URL definition
require_once '../include/locale/' . $_SESSION['langue'] . '.php';

if (!isset($_SESSION["username"]) || empty($_SESSION["username"])) {
    $action = $_GET['action'] ?? 'login';
    header("Location: login.php");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/main.css">
    <title><?php echo $lang['DASHBOARD_TITLE']; ?></title>
</head>

<body>
    <header>
        <?php require('../include/header.php'); ?>
    </header>

    <main>
        <h2>Welcome to your dashboard, <?php echo htmlspecialchars($_SESSION["username"]); ?>!</h2>
        <nav>
            <ul>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="settings.php">Settings</a></li>
                <li><a href="reports.php">Reports</a></li>
            </ul>
        </nav>
        <p>This is a protected area. Only logged-in users can see this.</p>
        <a href="logout.php">Logout</a>
    </main>
</body>

</html>