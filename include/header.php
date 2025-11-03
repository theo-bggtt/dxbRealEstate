<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/../start.php';

if (!isset($lang) || !is_array($lang)) {
    require_once __DIR__ . '/locale/en.php';
}
?>

<header class="site-header">
    <div class="header-container">
        <h1 class="site-title"><?php echo htmlspecialchars($lang['MAINPAGE_TITLE']); ?></h1>
        <nav class="main-nav">
            <ul>
                <li><a href="<?php echo BASE_URL; ?>index?langue=<?php echo htmlspecialchars($_SESSION['langue']); ?>"
                        class="nav-link"><?php echo htmlspecialchars($lang['MAINPAGE_TITLE']); ?></a></li>
                <li><a href="<?php echo BASE_URL; ?>developers?langue=<?php echo htmlspecialchars($_SESSION['langue']); ?>"
                        class="nav-link"><?php echo htmlspecialchars($lang['NAV_DEV_TITLE']); ?></a></li>
                <li><a href="<?php echo BASE_URL; ?>projects?langue=<?php echo htmlspecialchars($_SESSION['langue']); ?>"
                        class="nav-link"><?php echo htmlspecialchars($lang['NAV_PROJECTS_TITLE']); ?></a></li>
                <li><a href="<?php echo BASE_URL; ?>contact?langue=<?php echo htmlspecialchars($_SESSION['langue']); ?>"
                        class="nav-link"><?php echo htmlspecialchars($lang['NAV_CONTACT_TITLE']); ?></a></li>
            </ul>
        </nav>
        <div class="user-actions">
            <form action="<?php echo BASE_URL; ?>include/lang_switch.php" method="POST" class="language-switcher">
                <select name="langue" onchange="this.form.submit()" class="language-select">
                    <option value="en" <?php echo $_SESSION['langue'] == 'en' ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($lang['ENGLISH'] ?? 'English'); ?></option>
                    <option value="fr" <?php echo $_SESSION['langue'] == 'fr' ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($lang['FRENCH'] ?? 'Français'); ?></option>
                    <!-- Add more language options as needed -->
                </select>
            </form>
            <?php if (isset($_SESSION["username"]) && !empty($_SESSION["username"])): ?>
                <div class="user-menu">
                    <button
                        class="btn btn-primary user-toggle"><?php echo htmlspecialchars($_SESSION["username"]); ?></button>
                    <div class="user-dropdown">
                        <a href="<?php echo BASE_URL; ?>dashboard?langue=<?php echo htmlspecialchars($_SESSION['langue']); ?>"
                            class="dropdown-link"><?php echo htmlspecialchars($lang['DASHBOARD'] ?? 'Dashboard'); ?></a>
                        <a href="<?php echo BASE_URL; ?>dashboard/logout?langue=<?php echo htmlspecialchars($_SESSION['langue']); ?>"
                            class="dropdown-link"><?php echo htmlspecialchars($lang['LOGOUT'] ?? 'Logout'); ?></a>
                    </div>
                </div>
            <?php else: ?>
                <a href="<?php echo BASE_URL; ?>dashboard?langue=<?php echo htmlspecialchars($_SESSION['langue']); ?>"
                    class="btn btn-primary"><?php echo htmlspecialchars($lang['LOGIN'] ?? 'Connexion'); ?></a>
            <?php endif; ?>
        </div>
    </div>
</header>