<?php
  session_start();

  $langue_dispo = array('en','fr');

  $_SESSION['langue'] = 'fr';   

  if(isset($_GET['langue']) && $_GET['langue'] != ''){ 
    if(in_array($_GET['langue'], $langue_dispo))
    {       
      $_SESSION['langue'] = $_GET['langue'];
    }
  }
  include('locale/'.$_SESSION['langue'].'.php');

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