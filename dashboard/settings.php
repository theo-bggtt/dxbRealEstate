<?php
// Include initialization script
require_once '../start.php';

// Include configuration and language files
require_once '../include/config/config.php'; // BASE_URL and ASSETS_URL definition
require_once '../include/locale/' . $_SESSION['langue'] . '.php';

if (!isset($_SESSION["username"]) || empty($_SESSION["username"])) {
    $action = $_GET['action'] ?? 'login';
    header("Location: login");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>css/main.css">
    <title><?php echo $lang['PROFILE_TITLE'] ?? 'User Profile'; ?></title>
    <script>
        function togglePasswordVisibility(id) {
            const input = document.getElementById(id);
            const icon = input.nextElementSibling;
            if (input.type === 'password') {
                input.type = 'text';
                icon.textContent = '🙈';
            } else {
                input.type = 'password';
                icon.textContent = '👁️';
            }
        }
    </script>
</head>

<body>
    <header>
        <?php require('../include/header.php'); ?>
    </header>

    <main>
        <div class="profile-container">
            <div class="profile-header">
                <h1><?php echo htmlspecialchars($_SESSION['username']); ?></h1>
            </div>

            <section class="profile-section">
                <h2><?php echo $lang['PERSONAL_INFO'] ?? 'Personal Information'; ?></h2>
                <form class="profile-form" action="<?php echo BASE_URL; ?>dashboard/update" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="username"><?php echo $lang['USERNAME'] ?? 'Username'; ?>:</label>
                        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="email"><?php echo $lang['EMAIL'] ?? 'Email'; ?>:</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="bio"><?php echo $lang['BIO'] ?? 'Bio'; ?>:</label>
                        <textarea id="bio" name="bio"><?php echo htmlspecialchars($_SESSION['bio'] ?? ''); ?></textarea>
                    </div>
                    <div class="profile-actions">
                        <button type="submit" class="btn btn-primary"><?php echo $lang['SAVE_CHANGES'] ?? 'Save Changes'; ?></button>
                    </div>
                </form>
            </section>

            <section class="profile-section">
                <h2><?php echo $lang['SECURITY'] ?? 'Security'; ?></h2>
                <form class="profile-form" action="<?php echo BASE_URL; ?>dashboard/change_password" method="POST">
                    <div class="form-group password-toggle">
                        <label for="current_password"><?php echo $lang['CURRENT_PASSWORD'] ?? 'Current Password'; ?>:</label>
                        <input type="password" id="current_password" name="current_password">
                        <span class="toggle-icon" onclick="togglePasswordVisibility('current_password')">👁️</span>
                    </div>
                    <div class="form-group password-toggle">
                        <label for="new_password"><?php echo $lang['NEW_PASSWORD'] ?? 'New Password'; ?>:</label>
                        <input type="password" id="new_password" name="new_password">
                        <span class="toggle-icon" onclick="togglePasswordVisibility('new_password')">👁️</span>
                    </div>
                    <div class="form-group password-toggle">
                        <label for="confirm_password"><?php echo $lang['CONFIRM_PASSWORD'] ?? 'Confirm Password'; ?>:</label>
                        <input type="password" id="confirm_password" name="confirm_password">
                        <span class="toggle-icon" onclick="togglePasswordVisibility('confirm_password')">👁️</span>
                    </div>
                    <div class="profile-actions">
                        <button type="submit" class="btn btn-primary"><?php echo $lang['CHANGE_PASSWORD'] ?? 'Change Password'; ?></button>
                    </div>
                </form>
            </section>

            <section class="profile-section">
                <h2><?php echo $lang['ACCOUNT_ACTIONS'] ?? 'Account Actions'; ?></h2>
                <div class="profile-actions">
                    <a href="<?php echo BASE_URL; ?>dashboard/logout" class="btn btn-secondary"><?php echo $lang['LOGOUT'] ?? 'Logout'; ?></a>
                    <button onclick="if(confirm('<?php echo $lang['CONFIRM_DELETE'] ?? 'Are you sure you want to delete your account?'; ?>')) { window.location.href = '<?php echo BASE_URL; ?>profile/delete'; }" class="btn btn-danger"><?php echo $lang['DELETE_ACCOUNT'] ?? 'Delete Account'; ?></button>
                </div>
            </section>
        </div>
    </main>

</body>

</html>