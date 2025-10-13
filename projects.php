<?php
require('start.php');
require('functionsDEV.php');
$projects = feedProjects();

$projectNum = filter_input(INPUT_GET, 'projectNum', FILTER_VALIDATE_INT);
$name = filter_input(INPUT_GET, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$devName = filter_input(INPUT_GET, 'devName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$params = [];

function checkParam($paramName)
{
    $validParam = False;
    if (isset($_GET["$paramName"]) && $_GET["$paramName"] != '') {
        return true;
    } else {
        return False;
    }
}

if (checkParam('valider') == True) {
    if (checkParam('projectNum')) {
        array_push($params, ['projectNum', "$projectNum"]);
    }
    if (checkParam('name')) {
        array_push($params, ['name', "$name"]);
    }
    if (checkParam('devName')) {
        array_push($params, ['devName', "$devName"]);
    }
    $projects = filterProjects($projects, $params);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/main.css">
    <title><?php echo $lang['MAINPAGE_TITLE']; ?></title>
</head>

<body>
    <header>
        <?php require('header.php') ?>
    </header>

    <main>
        <div class="slideshow-container">
            <div class="slideshow-title">
                <h2><?php echo $lang['DEV_TITLE']; ?></h2>
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
                <input type="number" name="projectNum" step="1" placeholder="Numéro de projet"
                    value="<?php echo ($projectNum); ?>">
                <input type="text" name="name" placeholder="Nom" value="<?php echo ($name); ?>">
                <input type="text" name="devName" placeholder="Nom" value="<?php echo ($devName); ?>">

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
</body>

</html>