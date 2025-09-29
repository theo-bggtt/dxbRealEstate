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
    <link rel="stylesheet" href="main.css">
    <title><?php echo $lang['MAINPAGE_TITLE'];?></title>
</head>
<body>
    <header>
        <nav>
            <a href="index.php"><?php echo $lang['MAINPAGE_TITLE'];?></a>
            <a href="developers.php">Dev</a>
            <a href="projects.php">Projets</a>
            <a href="contact.php">Contact</a>
        </nav>
        <h1><?php echo $lang['MAINPAGE_TITLE'];?></h1>
        <div>
          <form action="" method="GET">
            <button type="submit" name="langue" value="fr">Français</button>
            <button type="submit" name="langue" value="en">English</button>
          </form>
        </div>
    </header>   
    
</body>
</html>