<?php
require_once __DIR__ . '/start.php';

require_once __DIR__ . '/include/config/config.php'; // BASE_URL, ASSETS_URL
require_once __DIR__ . '/include/locale/' . $_SESSION['langue'] . '.php';
?>

<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($_SESSION['langue']); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>css/main.css">
    <title><?php echo htmlspecialchars($lang['MAINPAGE_TITLE']); ?></title>
</head>
<body>
    <header>
        <?php require_once __DIR__ . '/include/header.php'; ?>
    </header>

    <main>
        <div class="slideshow-container">
            <div class="slideshow-title">
                <h2><?php echo $lang['SLIDESHOW_TITLE']; ?></h2>
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
        <div class="presentation">
            <div>
                <h2><?php echo htmlspecialchars($lang['THE_CONCEPT_TITLE']); ?></h2>
                <p><?php echo htmlspecialchars($lang['THE_CONCEPT_TEXT']); ?></p>
            </div>
            <img src="https://limeswood.ae/wp-content/uploads/2023/01/5.jpg" alt="Presentation Image">
        </div>
    </main>

    <footer>
        <?php
        include_once __DIR__ . '/include/footer.php';
        ?>
        
    </footer>
</body>
</html>