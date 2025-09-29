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

    <main>
        
        <div class="slideshow-container">
            <div class="slideshow-title">
                <h2><?php echo $lang['SLIDESHOW_TITLE'];?></h2>
            </div>
            <div class="slideshow-content">
                <img src="https://limeswood.ae/wp-content/uploads/2023/01/6.jpg" alt="">
            </div>
            <div class="slideshow-content">
                <img src="https://limeswood.ae/wp-content/uploads/2023/01/5.jpg" alt="">
            </div>
            <div class="slideshow-content">
                <img src="https://limeswood.ae/wp-content/uploads/2023/01/2.jpg" alt="">
            </div>

        </div>
        <div class="presentation">
            <div>
                <h2><?php echo $lang['THE_CONCEPT_TITLE'];?></h2>
                <p> <?php echo $lang['THE_CONCEPT_TEXT'];?></p>
            </div>
            <img src="https://limeswood.ae/wp-content/uploads/2023/01/6.jpg" alt="">
        </div>
    </main>   

    <footer>
        <div class="socials">
            <a href="https://instagram.com">Instagram</a>
            <a href="https://facebook.com">Facebook</a>
            <a href="https://tiktok.com">TikTok</a>   
        </div>
    </footer>
    
</body>
</html>