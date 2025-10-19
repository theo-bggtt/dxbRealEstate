<?php

require_once __DIR__ . '/../db.php'; // Path to db.php in the root
$pdo = getPDO(); // Use the centralized getPDO() function

?>

<div class="welcome-panel">
    <h2>Welcome to Your Dashboard</h2>
    <p>Here you can manage your account, view your activity, and access exclusive features.</p>
    <ul>
        <li><a href="profile.php">Edit Profile</a></li>
        <li><a href="settings.php">Account Settings</a></li>
        <li><a href="activity.php">View Activity</a></li>
    </ul>
</div>