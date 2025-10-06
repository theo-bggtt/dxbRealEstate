<?php


function feedTableau() {
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