<?php session_start();
include('../../includes/header.php');
include('menu.php');
    include "../../includes/connexion.php";
    
  
if (isset($_POST["ean"], $_POST["localisation"], $_POST["famille"], $_POST["marque"], $_POST["modele"], $_POST["numdeserie"])) {
    $ean = $_POST["ean"] ;
    $localisation = $_POST["localisation"] ;
    $famille = $_POST["famille"] ;
    $marque = $_POST["marque"] ;
    $modele = $_POST["modele"] ;
    $numdeserie = $_POST["numdeserie"] ;
    $numfacture = $_POST["numfacture"] ;
    $montant = $_POST["montant"] ;
    $sql = "INSERT INTO `biens` (`ean`, `localisation`, `famille`, `marque`, `modele`, `numdeserie`, `numfacture`, `montant`) VALUES ('".$ean."', '".$localisation."', '".$famille."', '".$marque."', '".$modele."', '".$numdeserie."', '".$numfacture."', '".$montant."')" ;
    $requete = $bdd->query($sql) or die(mysql_error()) ;
    if ($requete) {
        echo("La modification à été correctement effectuée") ;
        $sql2= "INSERT INTO `log` (`id`, `date`, `commentaire`) VALUES ('".$_SESSION['id']."', NOW(), 'A ajoute la ligne ".$_POST['edit_ean']."')";
        $bdd->query($sql2);
    } else {
        echo("La modification à échouée") ;
    }
}
if (isset($_POST["edit_ean"], $_POST["edit_localisation"], $_POST["edit_famille"], $_POST["edit_marque"], $_POST["edit_modele"], $_POST["edit_numdeserie"], $_POST["edit_numfacture"], $_POST["edit_montant"])) {
    $sql = "UPDATE `biens` SET `localisation`='".$_POST["edit_localisation"]."',`famille`='".$_POST["edit_famille"]."',`marque`='".$_POST["edit_marque"]."',`modele`='".$_POST["edit_modele"]."',`numdeserie`='".$_POST["edit_numdeserie"]."',`numfacture`='".$_POST["edit_numfacture"]."',`montant`='".$_POST["edit_montant"]."' WHERE `ean`= '".$_POST["edit_ean"]."'" ;
    $requete = $bdd->query($sql) or die(mysql_error()) ;
    if ($requete) {
        echo("La modification à été correctement effectuée") ;
        $sql2= "INSERT INTO `log` (`id`, `date`, `commentaire`) VALUES ('".$_SESSION['id']."', NOW(), 'A modifie la ligne ".$_POST['edit_ean']."')";
        $bdd->query($sql2);
    } else {
        echo("La modification à échouée") ;
    }
}
include "../../includes/footer.php"; ?>

	
