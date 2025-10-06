<?php
  session_start();
  require('functionsDEV.php');
  $dev = feedTableau();

  $langue_dispo = array('en','fr');

  $_SESSION['langue'] = 'fr'; 
  $address = filter_input(INPUT_GET, 'address', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $name = filter_input(INPUT_GET, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $regDate = filter_input(INPUT_GET, 'regDate', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $licenseNum =filter_input(INPUT_GET, 'licenseNum', FILTER_VALIDATE_INT);
  $website = filter_input(INPUT_GET, 'website', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  
  function checkParam($paramName) {
    $validParam = False;
    if(isset($_GET["$paramName"]) && $_GET["$paramName"] != '') {
      return true;
    } else {
      return False;
    }
  }
  
  
  if(checkParam('langue') === True){ 
    if(in_array($_GET['langue'], $langue_dispo))
    {       
      $_SESSION['langue'] = $_GET['langue'];
    }
  }


  if (checkParam('valider') == True) {
    if(checkParam('address')) {
      $dev = filterDevelopers($dev, 'address', $address);
    }
    if(checkParam('name')) {
      $dev = filterDevelopers($dev, 'name', $name);
    }
    if(checkParam('regDate')) {
      $unixTime = strtotime($regDate);
      $dateAChercher = date('d/m/Y', $unixTime);
      $dev = filterDevelopers($dev, 'regDate', $dateAChercher);
    }
    if(checkParam('licenseNum')) {
      $dev = filterDevelopers($dev, 'licenseNum', $licenseNum);      
    }
    if(checkParam('website')) {
      $dev = filterDevelopers($dev, 'website', $website);      
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
        <?php require('header.php') ?>
    </header>

    <main>
      <div class="slideshow-container">
            <div class="slideshow-title">
                <h2><?php echo $lang['DEV_TITLE'];?></h2>
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
      <section>
        <form class="lux-form" action="" method="GET">
          <input type="text" name="langue" value="<?php echo($_SESSION['langue']); ?>" hidden>
          <input type="text" name="name" placeholder="Nom" value="<?php echo($name);?>">
          <input type="date" name="regDate" value="<?php echo($regDate);?>">
          <input type="number" name="licenseNum" step="1" placeholder="Numéro de licence" value="<?php echo($licenseNum);?>">
          <input type="text" name="website" placeholder="Website" value="<?php echo($website);?>">
          
          <input type="submit" name="valider" value="Envoyer">
          <input type="reset" value="Effacer les filtres" onclick="window.location.href=window.location.pathname;">

        </form>
        <table class="table">
          <tr>
            <th>Name</th>
            
            <th>Registration Date</th>
            <th>License number</th>
            <th>Website</th>
          </tr>
          <?php 
          showDevelopers($dev);
          ?>
        </table>
      </section>
    </main>
</body>
</html>