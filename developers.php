<?php
require_once __DIR__ . '/start.php';

require_once __DIR__ . '/include/config/config.php'; // BASE_URL and ASSETS_URL definition
require_once __DIR__ . '/include/locale/' . $_SESSION['langue'] . '.php';

require_once __DIR__ . '/include/functions/functionsDEV.php';

$dev = feedDevelopers();

$address = filter_input(INPUT_GET, 'address', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$name = filter_input(INPUT_GET, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$regDate = filter_input(INPUT_GET, 'regDate', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$licenseNum = filter_input(INPUT_GET, 'licenseNum', FILTER_VALIDATE_INT);
$website = filter_input(INPUT_GET, 'website', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$params = [];

if (checkParam('valider') == true) {
  if (checkParam('address')) {
    array_push($params, ['address', "$address"]);
  }
  if (checkParam('name')) {
    array_push($params, ['name', "$name"]);
  }
  if (checkParam('regDate')) {
    $unixTime = strtotime($regDate);
    if ($unixTime !== false) {
      $dateAChercher = date('d/m/Y', $unixTime);
      array_push($params, ['regDate', "$dateAChercher"]);
    }
  }
  if (checkParam('licenseNum')) {
    array_push($params, ['licenseNum', "$licenseNum"]);
  }
  if (checkParam('website')) {
    array_push($params, ['website', "$website"]);
  }
  $dev = filterDevelopers($dev, $params);
}
?>

<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($_SESSION['langue']); ?>">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>../assets/css/main.css">
  <title><?php echo htmlspecialchars($lang['NAV_DEV_TITLE']); ?></title>
</head>

<body>
  <header>
    <?php require_once __DIR__ . '/include/header.php'; ?>
  </header>

  <main>
    <div class="slideshow-container">
      <div class="slideshow-title">
        <h2><?php echo htmlspecialchars($lang['DEV_TITLE']); ?></h2>
      </div>
      <div class="slideshow-content">
        <img src="https://limeswood.ae/wp-content/uploads/2023/01/6.jpg" alt="Slideshow Image 1">
      </div>
      <div class="slideshow-content">
        <img src="https://limeswood.ae/wp-content/uploads/2023/01/5.jpg" alt="Slideshow Image 2">
      </div>
      <div class="slideshow-content">
        <img src="https://limeswood.ae/wp-content/uploads/2023/01/2.jpg" alt="Slideshow Image 3">
      </div>
    </div>
    <section>
      <form class="lux-form" action="" method="GET">
        <input type="text" name="langue" value="<?php echo $_SESSION['langue']; ?>" hidden>
        <input type="text" name="name" placeholder="Nom" value="<?php echo $name ?>">
        <input type="date" name="regDate" value="<?php echo $regDate; ?>">
        <input type="number" name="licenseNum" step="1" placeholder="Numéro de licence"
          value="<?php echo $licenseNum ?>">
        <input type="text" name="website" placeholder="Website" value="<?php echo $website ?>">
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
        showDevelopers(developers: $dev);
        ?>
      </table>
    </section>
  </main>
  <footer>
    <?php
    include_once __DIR__ . '/include/footer.php';
    ?>

  </footer>
</body>

</html>