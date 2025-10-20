<?php
// Include configuration for BASE_URL
require_once __DIR__ . '/config/config.php'; // Ensure BASE_URL is defined

// Include initialization script to handle session and language
require_once __DIR__ . '/../public/start.php'; // Handles session and language switch

// Fallback to ensure $lang is available (though start.php should set $_SESSION['langue'])
if (!isset($lang) || !is_array($lang)) {
    require_once __DIR__ . '/locale/en.php'; // Default language file as fallback
}
?>

<nav>
    <a href="<?php echo BASE_URL; ?>index.php?langue=<?php echo htmlspecialchars($_SESSION['langue']); ?>"><?php echo htmlspecialchars($lang['MAINPAGE_TITLE']); ?></a>
    <a href="<?php echo BASE_URL; ?>developers.php?langue=<?php echo htmlspecialchars($_SESSION['langue']); ?>"><?php echo htmlspecialchars($lang['NAV_DEV_TITLE']); ?></a>
    <a href="<?php echo BASE_URL; ?>projects.php?langue=<?php echo htmlspecialchars($_SESSION['langue']); ?>"><?php echo htmlspecialchars($lang['NAV_PROJECTS_TITLE']); ?></a>
    <a href="<?php echo BASE_URL; ?>contact.php?langue=<?php echo htmlspecialchars($_SESSION['langue']); ?>"><?php echo htmlspecialchars($lang['NAV_CONTACT_TITLE']); ?></a>
</nav>
<h1><?php echo htmlspecialchars($lang['MAINPAGE_TITLE']); ?></h1>
<div>
    <form action="" method="GET">
        <button type="submit" name="langue" value="fr">Français</button>
        <button type="submit" name="langue" value="en">English</button>

        <?php
        if (isset($_SESSION["username"]) && !empty($_SESSION["username"])) {
            // Use relative path from public/ to dashboard/, adjust if included from dashboard/
            $dashboardPath = (dirname($_SERVER['PHP_SELF']) === '/dashboard') ? 'dashboard.php' : '../dashboard/dashboard.php';
            echo "<a href=\"" . $dashboardPath . "\">" . htmlspecialchars($_SESSION["username"]) . "</a>";
        } else {
            $dashboardPath = (dirname($_SERVER['PHP_SELF']) === '/dashboard') ? 'dashboard.php' : '../dashboard/dashboard.php';
            echo "<a href=\"" . $dashboardPath . "\">Connexion</a>";
        }
        ?>

    </form>
</div>