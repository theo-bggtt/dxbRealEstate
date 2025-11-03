<?php
// Include initialization script
require_once __DIR__ . '/start.php';

// Include configuration and language files
require_once __DIR__ . '/include/config/config.php'; // BASE_URL definition
require_once __DIR__ . '/include/locale/' . $_SESSION['langue'] . '.php';

// Include custom functions
require_once __DIR__ . '/include/functions/functionsDEV.php';

$projects = feedProjects();

$projectNum = filter_input(INPUT_GET, 'projectNum', FILTER_VALIDATE_INT);
$name = filter_input(INPUT_GET, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$startDate = filter_input(INPUT_GET, 'startDate', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$params = [];

if (checkParam('valider') == true) {
    if (checkParam('projectNum') && $projectNum !== null) {
        array_push($params, ['projectNum', (string)$projectNum]);
    }
    if (checkParam('name') && !empty($name)) {
        array_push($params, ['name', $name]);
    }
    if (checkParam('startDate') && !empty($startDate)) {
        array_push($params, ['startDate', $startDate]);
    }
    $projects = filterProjects($projects, $params);
}
?>

<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($_SESSION['langue']); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>../assets/css/main.css">
    <title><?php echo htmlspecialchars($lang['NAV_PROJECTS_TITLE']); ?></title>
</head>

<body>
    <header>
        <?php require_once __DIR__ . '/include/header.php'; ?>
    </header>

    <main>
        <div class="slideshow-container">
            <div class="slideshow-title">
                <h2><?php echo htmlspecialchars($lang['PROJECTS_TITLE']); ?></h2>
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
                <input type="text" name="langue" value="<?php echo $_SESSION['langue'] ?>" hidden>
                <input type="number" name="projectNum" step="1" placeholder="Numéro de projet"
                    value="<?php echo $projectNum ?>">
                <input type="text" name="name" placeholder="Nom du projet" value="<?php echo $name ?>">
                <input type="text" name="startDate" placeholder="Date de début" value="<?php echo $startDate ?>">

                <input type="submit" name="valider" value="Envoyer">
                <input type="reset" value="Effacer les filtres"
                    onclick="window.location.href=window.location.pathname;">
            </form>
            <table class="table">
                <tr>
                    <th>Project number</th>
                    <th>Project Name</th>
                    <th>Developer Name</th>
                </tr>
                <?php
                showProjects($projects);
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