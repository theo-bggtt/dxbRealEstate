<?php
// include/functions/functionsDEV.php
require_once __DIR__ . '/../config/db.php';

function checkParam($paramName)
{
  $validParam = false;
  if (isset($_GET["$paramName"]) && $_GET["$paramName"] != '') {
    return true;
  } else {
    return false;
  }
}

function feedDevelopers() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM developers"); // Adjust table name as per your schema
    return $stmt->fetchAll();
}

function feedProjects() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM projects"); // Adjust table name as per your schema
    return $stmt->fetchAll();
}

function filterDevelopers($developers, $params) {
    // Implement filtering logic here based on $params
    // Example: Return filtered array
    return $developers; // Placeholder, update with actual filtering
}

function filterProjects($projects, $params) {
    // Implement filtering logic here based on $params
    // Example: Return filtered array
    return $projects; // Placeholder, update with actual filtering
}

function showDevelopers($developers) {
    foreach ($developers as $dev) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($dev['name']) . "</td>";
        echo "<td>" . htmlspecialchars($dev['regDate']) . "</td>";
        echo "<td>" . htmlspecialchars($dev['licenseNum']) . "</td>";
        echo "<td>" . htmlspecialchars($dev['website']) . "</td>";
        echo "</tr>";
    }
}

function showProjects($projects) {
    foreach ($projects as $project) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($project['projectNum']) . "</td>";
        echo "<td>" . htmlspecialchars($project['name']) . "</td>";
        echo "<td>" . htmlspecialchars($project['devName']) . "</td>";
        echo "</tr>";
    }
}
?>