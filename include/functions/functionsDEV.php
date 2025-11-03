<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/config.php';
$pdo = getPDO();

function checkParam($paramName)
{
    if (isset($_GET["$paramName"]) && $_GET["$paramName"] != '') {
        return true;
    } else {
        return false;
    }
}

function feedDevelopers()
{
    $pdo = getPDO();
    $stmt = $pdo->query("SELECT * FROM developers"); // Adjust table name as per your schema
    return $stmt->fetchAll();
}

function feedProjects()
{
    $pdo = getPDO();
    $stmt = $pdo->query("SELECT * FROM projects"); // Adjust table name as per your schema
    return $stmt->fetchAll();
}

// Your existing filterDevelopers function (unchanged)
function filterDevelopers($dev, $params)
{
    foreach ($dev as $key => $developer) {
        foreach ($params as [$filterName, $filterValue]) {
            $matchFound = false;
            foreach ($developer as $fieldName => $fieldValue) {
                if (strtoupper($fieldName) == strtoupper($filterName)) {
                    if (in_array(strtolower($fieldName), ['developer_en', 'registration_date', 'webpage'])) {
                        if (str_contains(strtoupper($fieldValue), strtoupper($filterValue))) {
                            $matchFound = true;
                            break;
                        }
                    } elseif (strtoupper($fieldName) == 'LICENSE_NUMBER') {
                        if (strtoupper($fieldValue) == strtoupper($filterValue)) {
                            $matchFound = true;
                            break;
                        }
                    }
                }
            }
            if (!$matchFound) {
                unset($dev[$key]);
                break;
            }
        }
    }
    return array_values($dev);
}




function filterProjects($dev, $params)
{
    foreach ($dev as $key => $developer) {
        foreach ($params as [$filterName, $filterValue]) {
            $matchFound = false;
            foreach ($developer as $fieldName => $fieldValue) {
                if (
                    strtoupper($fieldName) == strtoupper($filterName) ||
                    (strtoupper($filterName) == 'PROJECTNUM' && strtoupper($fieldName) == 'PROJECT_NUMBER') ||
                    (strtoupper($filterName) == 'NAME' && strtoupper($fieldName) == 'PROJECT_EN') ||
                    (strtoupper($filterName) == 'STARTDATE' && strtoupper($fieldName) == 'START_DATE')
                ) {
                    if (strtoupper($filterName) == 'PROJECTNUM') {
                        if (strval($fieldValue) == strval($filterValue)) {
                            $matchFound = true;
                            break;
                        }
                    } else {
                        if (str_contains(strtoupper((string) $fieldValue), strtoupper((string) $filterValue))) {
                            $matchFound = true;
                            break;
                        }
                    }
                }
            }
            if (!$matchFound) {
                unset($dev[$key]);
                break;
            }
        }
    }
    return array_values($dev);
}

function showDevelopers($developers)
{
    foreach ($developers as $dev) {
        $developerID = htmlspecialchars($dev['developer_number'] ?? '');
        echo "<tr onclick=\"window.location.href='developer?projectID=" . $developerID . "'\" style=\"cursor: pointer;\">";
        echo "<td>" . htmlspecialchars($dev['developer_number'] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars($dev['developer_en'] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars($dev['registration_date'] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars($dev['webpage'] ?? '') . "</td>";
        echo "</tr>";

    }
}

function showProjects($projects) {
    echo "<p>" . count($projects) . " projects found.</p>";
    foreach ($projects as $project) {
        $projectID = $project['PROJECT_NUMBER'] ?? 0;
        $encodedID = urlencode($projectID);
        echo "<tr onclick=\"window.location.href='details.php?projectID=" . $projectID . "'\" style=\"cursor: pointer;\">";
        echo "<td>" . htmlspecialchars($projectID) . "</td>";
        echo "<td>" . htmlspecialchars($project['PROJECT_EN'] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars($project['START_DATE'] ?? '') . "</td>";
        echo "</tr>";
    }
}
?>