<?php
  session_start();
  require('functions.php');
  $dev = feedTableau();

  $langue_dispo = array('en','fr');

  var_dump(date(null, null));
  var_dump($_GET);

  $_SESSION['langue'] = 'fr'; 
  $address = filter_input(INPUT_GET, 'address', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $name = filter_input(INPUT_GET, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $regDate = filter_input(INPUT_GET, 'regDate', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $licenseNum =filter_input(INPUT_GET, 'licenseNum', FILTER_VALIDATE_INT);
  
  function checkParam($paramName) {
    $validParam = False;
    if(isset($_GET["$paramName"]) && $_GET["$paramName"] != '') {
      return true;
      echo('VALIDER');
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
      var_dump($dateAChercher);
      $dev = filterDevelopers($dev, 'regDate', $dateAChercher);
    }
    if(checkParam('licenseNum')) {
      $dev = filterDevelopers($dev, 'licenseNum', $licenseNum);      
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
      <table>
        <tr>
          <th>Name</th>
          <th>Address</th>
          <th>Registration Date</th>
          <th>License number</th>
        </tr>
        <?php 
        showDevelopers($dev);
        ?>
      </table>

      <form action="" method="GET">
        <input type="text" name="langue" id="" value="<?php echo($_SESSION['langue']); ?>" hidden>
        <input type="text" name="name" id="">
        <input type="text" name="address" id="">
        <input type="date" name="regDate" id="">
        <input type="number" name="licenseNum" id="" step=1>
        <input type="submit" name="valider" id="" value="envoyer">
        <input type="reset" name="Reset" id="" value="Reset">
      </form>
    </main>
    
</body>
</html>