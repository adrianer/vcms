<?php
$moduleName = "Base Internet Vereine";
$version = "2.14";
$styleSheet = "";
$installScript = "";
$uninstallScript = "";
$updateScript = "";

$pages[] = new LibPage("vereindetail", "scripts/", "association.php", "", "Verein");
$dependencies = array();

$menuElementsInternet = array();
$menuElementsIntranet = array();
$menuElementsAdministration = array();
$includes = array();
$headerStrings = array();
?>