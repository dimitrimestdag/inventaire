<?php
// changepass.php
$sql_changepass = SELECT."id, pseudo".MEMBRE." ORDER BY pseudo ASC ";
//info.php
$sql_searchAD = "SELECT * from tbl_import_active_directory WHERE active_directory_lastname LIKE '". $chainesearch
            ."%' OR active_directory_firstname LIKE '". $chainesearch
            ."%' OR active_directory_id_sage_compagny LIKE '". $chainesearch
            ."%' OR active_directory_id_sage_employee LIKE '". $chainesearch
            ."%' OR active_directory_id_sage_manager LIKE '". $chainesearch
            ."%' OR active_directory_uid_number LIKE '". $chainesearch ."%'";
//insertion.php
$sql_ajout_bien = "INSERT INTO `biens` (`ean`, `localisation`, `famille`, `marque`, `modele`, `numdeserie`, `numfacture`, `montant`) VALUES ('".$ean."', '".$localisation."', '".$famille."', '".$marque."', '".$modele."', '".$numdeserie."', '".$numfacture."', '".$montant."')" ;
$sql_log_ajout= "INSERT INTO `log` (`id`, `date`, `commentaire`) VALUES ('".$_SESSION['id']."', NOW(), 'A ajoute la ligne ".$_POST['edit_ean']."')";
$sql_update_bien = "UPDATE `biens` SET `localisation`='".$_POST["edit_localisation"]."',`famille`='".$_POST["edit_famille"]."',`marque`='".$_POST["edit_marque"]."',`modele`='".$_POST["edit_modele"]."',`numdeserie`='".$_POST["edit_numdeserie"]."',`numfacture`='".$_POST["edit_numfacture"]."',`montant`='".$_POST["edit_montant"]."' WHERE `ean`= '".$_POST["edit_ean"]."'" ;
$sql_log_update= "INSERT INTO `log` (`id`, `date`, `commentaire`) VALUES ('".$_SESSION['id']."', NOW(), 'A modifie la ligne ".$_POST['edit_ean']."')";
//inventairemodif.php
$sql_familleASC = 'SELECT * FROM famille ORDER BY famille ASC';
$sql_marqueASC = 'SELECT * FROM marque ORDER BY marque ASC';
$sql_adASC = "SELECT DISTINCT active_directory_lastname, active_directory_firstname, active_directory_uid_number from tbl_import_active_directory ORDER BY active_directory_lastname ASC ";
$sql_MAXean = "SELECT MAX(ean) FROM biens";
//lignedel.php
$sql_del_bien = 'DELETE FROM biens WHERE ean = '.$ean;
$sql_log_del= "INSERT INTO `log` (`id`, `date`, `commentaire`) VALUES ('".$_SESSION['id']."', NOW(), 'A supprimer la ligne".$ean."')";
//lignemodif.php
$sql_bienean = 'SELECT * FROM biens WHERE ean = '.$ean;
    //$sql_adASC
    //$sql_familleASC
    //$sql_marqueASC
//sortie.php
$sql_update_bien_sortie = "UPDATE biens SET localisation = REPLACE(localisation, ".$loc.", 999999) WHERE localisation = ".$loc;
$sql_adnom = "SELECT DISTINCT active_directory_lastname, active_directory_firstname, active_directory_uid_number from tbl_import_active_directory WHERE active_directory_uid_number = ".$loc;
$sql_log_sortie= "INSERT INTO `log` (`id`, `date`, `commentaire`) VALUES ('".$_SESSION['id']."', NOW(), 'A supprime les biens de ".$nom."')";
//result.php
$sql_ad_searchuid = "SELECT active_directory_uid_number FROM tbl_import_active_directory WHERE active_directory_lastname LIKE '". $chainesearch
."%' OR active_directory_firstname LIKE '". $chainesearch."%'";
$sql_search_biens = "SELECT * from biens WHERE ean LIKE '%". $chainesearch
                    ."%' OR localisation LIKE '". $chainesearch
                    ."%' OR famille LIKE '". $chainesearch
                    ."%' OR marque LIKE '". $chainesearch
                    ."%' OR numdeserie LIKE '". $chainesearch
                    ."%' OR localisation ='".$chainesearch."' OR modele LIKE '". $chainesearch ."%'";
$sql_search_biensi = "SELECT * from biens WHERE ean LIKE '%". $chainesearch
                    ."%' OR localisation LIKE '". $chainesearch
                    ."%' OR famille LIKE '". $chainesearch
                    ."%' OR marque LIKE '". $chainesearch
                    ."%' OR numdeserie LIKE '". $chainesearch
                    ."%' OR localisation =".$tabloc[$i]." OR modele LIKE '". $chainesearch ."%'";
$sql_localisation = "SELECT DISTINCT nom, prenom from localisation  WHERE localisation LIKE '". $donnees["localisation"] ."'";
//tbody.php
$sql_ean_biensASC = "SELECT DISTINCT ean from biens ORDER BY ean ASC";
$sql_biensASC = 'SELECT * FROM biens ORDER BY ean ASC LIMIT '.$_GET['debut'].','.$nb_affichage_par_page;
$sql_ad_nomprenom = "SELECT DISTINCT active_directory_lastname, active_directory_firstname from tbl_import_active_directory  WHERE active_directory_uid_number LIKE '$loc' ";
