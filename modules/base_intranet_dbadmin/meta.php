<?php
$moduleName = "Intranet Verwaltung";
$version = "2.27";
$styleSheet = "";
$installScript = "";
$uninstallScript = "";
$updateScript = "";

$vorstand = array("senior", "jubelsenior", "consenior", "fuchsmajor", "fuchsmajor2", "scriptor", "quaestor", "dachverbandsberichterstatter");
$internetwart = array("internetwart");

//Datenbank
$pages[] = new LibPage("intranet_admin_db_personenliste", "scripts/person/", "personen.php", new LibAccessRestriction("", array_merge($vorstand, $internetwart)), "Personen");
$pages[] = new LibPage("intranet_admin_db_person", "scripts/person/", "person.php", new LibAccessRestriction("", array_merge($vorstand, $internetwart)), "Person");
$pages[] = new LibPage("intranet_admin_db_semesterliste", "scripts/semester/", "semesters.php", new LibAccessRestriction("", $internetwart), "Semester");
$pages[] = new LibPage("intranet_admin_db_semester", "scripts/semester/", "semester.php", new LibAccessRestriction("", $internetwart), "Semester");
$pages[] = new LibPage("intranet_admin_db_veranstaltungsliste", "scripts/veranstaltung/", "veranstaltungen.php", new LibAccessRestriction("", array_merge($vorstand, $internetwart)), "Veranstaltungen");
$pages[] = new LibPage("intranet_admin_db_veranstaltung", "scripts/veranstaltung/", "veranstaltung.php", new LibAccessRestriction("", array_merge($vorstand, $internetwart)), "Veranstaltung");
$pages[] = new LibPage("intranet_admin_db_vereinsliste", "scripts/verein/", "vereine.php", new LibAccessRestriction("", array_merge($vorstand, $internetwart)), "Vereine");
$pages[] = new LibPage("intranet_admin_db_verein", "scripts/verein/", "verein.php", new LibAccessRestriction("", array_merge($vorstand, $internetwart)), "Verein");
$pages[] = new LibPage("intranet_admin_db_vipliste", "scripts/vip/", "vips.php", new LibAccessRestriction("", array_merge($vorstand, $internetwart)), "Vips");
$pages[] = new LibPage("intranet_admin_db_vip", "scripts/vip/", "vip.php", new LibAccessRestriction("", array_merge($vorstand, $internetwart)), "Vip");
$pages[] = new LibPage("intranet_admin_db_vereinsmitgliedschaftenliste", "scripts/vereinsmitgliedschaft/", "vereinsmitgliedschaften.php", new LibAccessRestriction("", array_merge($vorstand, $internetwart)), "Vereinsmitgliedschaften");
$pages[] = new LibPage("intranet_admin_db_vereinsmitgliedschaft", "scripts/vereinsmitgliedschaft/", "vereinsmitgliedschaft.php", new LibAccessRestriction("", array_merge($vorstand, $internetwart)), "Vereinsmitgliedschaft");

$pages[] = new LibPage("intranet_admin_db_gruppen", "scripts/", "gruppen.php", new LibAccessRestriction("", $internetwart), "Gruppen");
$pages[] = new LibPage("intranet_admin_db_status", "scripts/", "status.php", new LibAccessRestriction("", $internetwart), "Status");
$pages[] = new LibPage("intranet_admin_db_region", "scripts/", "regionen.php", new LibAccessRestriction("", $internetwart), "Regionen");


$menuFolderDatenbank = new LibMenuFolder("", "Daten", 50);
$menuFolderDatenbank->addElement(new LibMenuEntry("intranet_admin_db_personenliste", "Personen", 200));
$menuFolderDatenbank->addElement(new LibMenuEntry("intranet_admin_db_semesterliste", "Semester", 300));
$menuFolderDatenbank->addElement(new LibMenuEntry("intranet_admin_db_veranstaltungsliste", "Veranstaltungen", 400));
$menuFolderDatenbank->addElement(new LibMenuEntry("intranet_admin_db_vereinsliste", "Vereine", 500));
$menuFolderDatenbank->addElement(new LibMenuEntry("intranet_admin_db_vipliste", "Vips", 550));
$menuFolderDatenbank->addElement(new LibMenuEntry("intranet_admin_db_vereinsmitgliedschaftenliste", "Mitgliedschaften", 501));
$menuFolderDatenbank->addElement(new LibMenuEntry("intranet_admin_db_gruppen", "Gruppen", 800));
$menuFolderDatenbank->addElement(new LibMenuEntry("intranet_admin_db_status", "Status", 900));
$menuFolderDatenbank->addElement(new LibMenuEntry("intranet_admin_db_region", "Regionen", 1000));

$menuElementsInternet = array();
$menuElementsIntranet = array();
$menuElementsAdministration[] = $menuFolderDatenbank;

$includes = array();
$headerStrings = array();
$dependencies[] = new LibMinDependency("Login-Modul", "base_internet_login", 1.0);
?>