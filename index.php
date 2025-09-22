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
            <a href="index.php">Accueil</a>
            <a href="developers.php">Dev</a>
            <a href="projects.php">Projets</a>
            <a href="contact.php">Contact</a>
        </nav>
        <h1>Accueil</h1>
        <div>
            <a href="./?langue=fr">Francais</a>
            <a href="./?langue=en">English</a>
        </div>
        
    </header>

    <main>
        
        <div class="slideshow-container">
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
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla bibendum nec sapien nec aliquam. Nulla urna libero, sollicitudin eu blandit sit amet, convallis ac magna. Aenean malesuada nec nibh in tincidunt. Integer euismod tristique eros eget molestie. Vivamus malesuada nunc augue. Nullam turpis ipsum, tincidunt blandit porta at, luctus id lectus. Vivamus congue mauris sapien, ullamcorper fringilla turpis convallis ut.
                    <br><br>                    

                    Aenean eros ex, eleifend quis porta sit amet, interdum vel eros. Vestibulum congue porta justo, ut varius diam efficitur eu. Maecenas arcu nisi, tincidunt luctus sem ullamcorper, malesuada consequat massa. Curabitur ut eros tincidunt, mollis lacus at, congue erat. In eu nulla vel magna dignissim euismod ac a arcu. Donec ullamcorper neque a sem sollicitudin, laoreet suscipit mauris pulvinar. Cras lobortis dictum hendrerit. Curabitur ultricies lectus nibh, sed vehicula nibh vulputate vitae. Nulla facilisi. Praesent odio orci, ornare ac sapien nec, feugiat bibendum enim.
                </p>
            </div>
            <img src="https://limeswood.ae/wp-content/uploads/2023/01/6.jpg" alt="">
        </div>
    </main>   

    <footer>
        <div class="socials">
            <a href="instagram.com">Instagram</a>
            <a href="facebook.com">Facebook</a>
            <a href="tiktok.com">Tik Tok</a>   
        </div>
    </footer>
    
</body>
</html>