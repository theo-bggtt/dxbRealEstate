<?php


function feedTableau() {
    $tableau = [
    ["name" => "Sobha", "address" => "12 rue des margines", "regDate" => "11/11/2011", "licenseNum" => "114"],
    ["name" => "Jerus", "address" => "45 rue des deux tours", "regDate" => "02/05/2025", "licenseNum" => "112"],

    ["name" => "Emaar Properties", "address" => "Emaar Square, Sheikh Zayed Road, Dubai", "regDate" => "1997", "licenseNum" => "111"],
    ["name" => "Damac Properties", "address" => "DAMAC Hills, Dubai", "regDate" => "2002", "licenseNum" => "265"],
    ["name" => "Nakheel Properties", "address" => "Palm Jumeirah, Dubai", "regDate" => "2000", "licenseNum" => "951"],
    ["name" => "Danube Properties", "address" => "Danube Business Park, Dubai", "regDate" => "1993", "licenseNum" => "763"],
    ["name" => "Azizi Developments", "address" => "API World Tower, Sheikh Zayed Road, Dubai", "regDate" => "2007", "licenseNum" => "233"],
    ["name" => "Union Properties", "address" => "Dubai", "regDate" => "1987", "licenseNum" => "225"],
    ["name" => "Select Group", "address" => "Business Bay / Dubai Marina, Dubai", "regDate" => "2002", "licenseNum" => "511"],
    ["name" => "LEOS Developments", "address" => "Jumeirah Village Circle, Dubai", "regDate" => "2022", "licenseNum" => "222"],
    ["name" => "Tiger Group", "address" => "Deira / Dubai", "regDate" => "1975", "licenseNum" => "50"],
    ["name" => "MAG Property Development", "address" => "Dubai", "regDate" => "2003", "licenseNum" => "42"],
    ["name" => "Omniyat", "address" => "Dubai", "regDate" => "2005", "licenseNum" => "?"],
    ["name" => "Ellington Properties", "address" => "Dubai", "regDate" => "2014", "licenseNum" => "552"]
  ];
    return $tableau;
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