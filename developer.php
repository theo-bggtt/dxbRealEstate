<?php
require_once __DIR__ . '/start.php';

require_once __DIR__ . '/include/config/config.php'; // BASE_URL and ASSETS_URL definition
require_once __DIR__ . '/include/locale/' . $_SESSION['langue'] . '.php';

require_once __DIR__ . '/include/functions/functionsDEV.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>../assets/css/main.css">
    <title>Document</title>
</head>

<body>
    <header>
        <?php require_once __DIR__ . '/include/header.php'; ?>
    </header>
    <main>

    </main>
    <footer>
        <?php
        include_once __DIR__ . '/include/footer.php';
        ?>

    </footer>
</body>

</html>