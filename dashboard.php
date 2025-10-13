<?php
  require("start.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/main.css">
    <title><?php echo $lang['MAINPAGE_TITLE'];?></title>
</head>
<body>
    <header>
        <?php require('header.php') ?>
    </header>

    <main>
        <?php
        if (isset($_SESSION["username"]) && !empty($_SESSION["username"])) {?>
        <h2>Welcome to your dashboard, <?php echo isset($_SESSION["username"]) ? htmlspecialchars($_SESSION["username"]) : 'Guest'; ?>!</h2>
        <p>This is a protected area. Only logged-in users can see this.</p>
        <a href="panel/logout.php">Logout</a>
        <?php
        } else {
            require('panel/login.php');
        }
        ?>
    </main>
    
</body>
</html>