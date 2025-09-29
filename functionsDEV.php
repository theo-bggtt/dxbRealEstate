<?php


function feedTableau() {
    $json = file_get_contents('csvjson.json');
    $tableau = extractDevelopers($json);
    return $tableau;
}

function extractDevelopers($jsonString) {
    $data = json_decode($jsonString, true);

    $result = [];

    foreach ($data as $developer) {
        $result[] = (object) [
            'name'       => $developer['DEVELOPER_EN'],
            'regDate'    => $developer['REGISTRATION_DATE'],
            'licenseNum' => $developer['LICENSE_NUMBER'],
            'webpage'    => $developer['WEBPAGE'],
        ];
    }

    return $result;
}


function filterDevelopers($dev, $filterName, $filterValue) {
    foreach ($dev as $clef => $valeur) {
        foreach($dev[$clef] as $nomChamp => $valeurChamp) {
            if (strtoupper($nomChamp) == strtoupper($filterName) && ($nomChamp == 'name' || $nomChamp == 'address' || $nomChamp == 'regDate') && !str_contains(strtoupper($valeurChamp), strtoupper($filterValue))) {
                unset($dev[$clef]);
                array_values($dev);
            } else if (strtoupper($nomChamp) == strtoupper($filterName) && strtoupper($nomChamp) == strtoupper('licenseNum') && strtoupper($valeurChamp) != strtoupper($filterValue)) {
                unset($dev[$clef]);
                array_values($dev);
            }
        }
    }
    return $dev;
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