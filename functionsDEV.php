<?php


function updateDevelopers() {
    $dsn = "mysql:host=localhost;dbname=dxbRealEstate;charset=utf8mb4";
    $pdo = new PDO($dsn, 'promoter', 'oZg1lR3uq0EFTB]z');
    $sql = "INSERT INTO developers (developer_number, developer_en, registration_date, license_source_en, license_type_en, legal_status_en, webpage, phone, fax, license_number, license_issue_datem license_expiry_date, chamber_of_commerce_no) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $statement = $pdo->prepare($sql);
    $statement->execute();

}


function importProjectsFromCSV($csvFilePath) {
    $dsn = "mysql:host=localhost;dbname=dxbRealEstate;charset=utf8mb4";
    $pdo = new PDO($dsn, 'promoter', 'oZg1lR3uq0EFTB]z');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Open CSV file for reading
    if (!file_exists($csvFilePath) || !is_readable($csvFilePath)) {
        throw new Exception("CSV file not found or not readable.");
    }

    if (($handle = fopen($csvFilePath, 'r')) === false) {
        throw new Exception("Could not open the CSV file.");
    }

    // Read the header line first
    $headers = fgetcsv($handle);

    // Remove BOM from first header if present
    $headers[0] = preg_replace('/^\xEF\xBB\xBF/', '', $headers[0]);

    // Prepare your insert statement with all relevant columns
    $sql = "INSERT IGNORE INTO projects (
    PROJECT_NUMBER, PROJECT_EN, DEVELOPER_NUMBER, DEVELOPER_EN,
    START_DATE, END_DATE, ADOPTION_DATE, PRJ_TYPE_EN, PROJECT_VALUE,
    ESCROW_ACCOUNT_NUMBER, PROJECT_STATUS, PERCENT_COMPLETED, INSPECTION_DATE,
    COMPLETION_DATE, DESCRIPTION_EN, AREA_EN, ZONE_EN, CNT_LAND,
    CNT_BUILDING, CNT_VILLA, CNT_UNIT, MASTER_PROJECT_EN
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";


    $stmt = $pdo->prepare($sql);

    // Loop through each line of CSV and insert into database
    while (($row = fgetcsv($handle)) !== false) {
        // If the CSV row has fewer columns than headers, skip
        if (count($row) < count($headers)) continue;

        // Bind values and execute
        $stmt->execute($row);
    }

    fclose($handle);
}

function feedProjects() {
    $dsn = "mysql:host=localhost;dbname=dxbRealEstate;charset=utf8mb4";
    $pdo = new PDO($dsn, 'promoter', 'oZg1lR3uq0EFTB]z');
    $sql = "SELECT PROJECT_NUMBER, PROJECT_EN, DEVELOPER_EN, START_DATE, END_DATE, PROJECT_STATUS, PROJECT_VALUE FROM projects";

    $statement = $pdo->prepare($sql);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

function feedDevelopers() {
    $dsn = "mysql:host=localhost;dbname=dxbRealEstate;charset=utf8mb4";
    $pdo = new PDO($dsn, 'promoter', 'oZg1lR3uq0EFTB]z');
    $sql = "SELECT DEVELOPER_EN AS name, REGISTRATION_DATE AS regDate, LICENSE_NUMBER AS licenseNum, WEBPAGE AS website FROM developers";

    $statement = $pdo->prepare($sql);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

function filterDevelopers($dev, $params) {
    foreach ($dev as $key => $developer) {
        foreach ($params as [$filterName, $filterValue]) {
            $matchFound = false;
            foreach ($developer as $fieldName => $fieldValue) {
                if (strtoupper($fieldName) == strtoupper($filterName)) {
                    if (in_array(strtolower($fieldName), ['name', 'address', 'regdate', 'website'])) {
                        if (str_contains(strtoupper($fieldValue), strtoupper($filterValue))) {
                            $matchFound = true;
                            break;
                        }
                    } elseif (strtoupper($fieldName) == 'LICENSENUM') {
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


function showDevelopers($dev) {

    foreach ($dev as $clef => $valeur) {
        echo("<tr>");
        foreach ($dev[$clef] as $nomChamp => $valeurChamp) {
            echo("<td> $valeurChamp </td>");
        }
        echo("</tr>");
    }

}



?>