<?php

session_start();

$langue_dispo = array('en', 'fr');

$_SESSION['langue'] = 'fr';

if (isset($_GET['langue']) && $_GET['langue'] != '') {
    if (in_array($_GET['langue'], $langue_dispo)) {
        $_SESSION['langue'] = $_GET['langue'];
    }
}
include('locale/' . $_SESSION['langue'] . '.php');

?>