<?php
session_start();
require('functionsDEV.php');

// Fetch all projects initially
$projects = feedProjects();  // <-- You will need to implement this function in functionsDEV.php

$langue_dispo = array('en', 'fr');

$_SESSION['langue'] = 'fr';

$projectNumber = filter_input(INPUT_GET, 'projectNumber', FILTER_VALIDATE_INT);
$projectStatus = filter_input(INPUT_GET, 'projectStatus', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$startDate = filter_input(INPUT_GET, 'startDate', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$endDate = filter_input(INPUT_GET, 'endDate', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$projectType = filter_input(INPUT_GET, 'projectType', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$params = [];

function checkParam($paramName) {
    return isset($_GET[$paramName]) && $_GET[$paramName] !== '';
}

if (checkParam('langue')) {
    if (in_array($_GET['langue'], $GLOBALS['langue_dispo'])) {
        $_SESSION['langue'] = $_GET['langue'];
    }
}

if (checkParam('valider')) {
    if (checkParam('projectNumber')) {
        $params['PROJECT_NUMBER'] = $projectNumber;
    }
    if (checkParam('projectStatus')) {
        $params['PROJECT_STATUS'] = $projectStatus;
    }
    if (checkParam('startDate')) {
        $params['START_DATE'] = $startDate;
    }
    if (checkParam('endDate')) {
        $params['END_DATE'] = $endDate;
    }
    if (checkParam('projectType')) {
        $params['PRJ_TYPE_EN'] = $projectType;
    }

    if (!empty($params)) {
        $projects = filterProjects($projects, $params);  // Implement filtering in functionsDEV.php
    }
}

include('locale/' . $_SESSION['langue'] . '.php');
?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION['langue']; ?>">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="main.css" />
    <title><?php echo $lang['PROJECTS_TITLE']; ?></title>
</head>
<body>
    <header>
        <?php require('header.php'); ?>
    </header>

    <main>
        <section>
            <h2><?php echo $lang['PROJECTS_TITLE']; ?></h2>

            <form class="lux-form" method="GET" action="">
                <input type="hidden" name="langue" value="<?php echo $_SESSION['langue']; ?>" />
                
                <input type="number" name="projectNumber" placeholder="<?php echo $lang['PROJECT_NUMBER']; ?>" value="<?php echo htmlspecialchars($projectNumber); ?>" />
                
                <input type="text" name="projectStatus" placeholder="<?php echo $lang['PROJECT_STATUS']; ?>" value="<?php echo htmlspecialchars($projectStatus); ?>" />
                
                <input type="date" name="startDate" placeholder="<?php echo $lang['START_DATE']; ?>" value="<?php echo htmlspecialchars($startDate); ?>" />
                
                <input type="date" name="endDate" placeholder="<?php echo $lang['END_DATE']; ?>" value="<?php echo htmlspecialchars($endDate); ?>" />
                
                <input type="text" name="projectType" placeholder="<?php echo $lang['PROJECT_TYPE']; ?>" value="<?php echo htmlspecialchars($projectType); ?>" />
                
                <input type="submit" name="valider" value="<?php echo $lang['FILTER']; ?>" />
                <input type="reset" value="<?php echo $lang['RESET']; ?>" onclick="window.location.href=window.location.pathname;" />
            </form>

            <table class="table">
                <thead>
                    <tr>
                        <th><?php echo $lang['PROJECT_NUMBER']; ?></th>
                        <th><?php echo $lang['PROJECT_EN']; ?></th>
                        <th><?php echo $lang['DEVELOPER_EN']; ?></th>
                        <th><?php echo $lang['START_DATE']; ?></th>
                        <th><?php echo $lang['END_DATE']; ?></th>
                        <th><?php echo $lang['PROJECT_STATUS']; ?></th>
                        <th><?php echo $lang['PROJECT_VALUE']; ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($projects as $project) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($project['PROJECT_NUMBER']) . "</td>";
                        echo "<td>" . htmlspecialchars($project['PROJECT_EN']) . "</td>";
                        echo "<td>" . htmlspecialchars($project['DEVELOPER_EN']) . "</td>";
                        echo "<td>" . htmlspecialchars($project['START_DATE']) . "</td>";
                        echo "<td>" . htmlspecialchars($project['END_DATE']) . "</td>";
                        echo "<td>" . htmlspecialchars($project['PROJECT_STATUS']) . "</td>";
                        echo "<td>" . htmlspecialchars(number_format($project['PROJECT_VALUE'], 2)) . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>
