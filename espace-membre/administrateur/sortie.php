<tbody>
<?php session_start();
include('../../includes/header.php');
include "../../includes/connexion.php";
$loc = $_GET['loc'];
$sql = "UPDATE biens SET localisation = REPLACE(localisation, ".$loc.", 999999) WHERE localisation = ".$loc;
$sql2 = "SELECT DISTINCT active_directory_lastname, active_directory_firstname, active_directory_uid_number from tbl_import_active_directory WHERE active_directory_uid_number = ".$loc;
$req_nom = $bdd2->query($sql2);
while ($donnees = $req_nom->fetch()) {
    $nom = $donnees['active_directory_firstname'].' '.$donnees['active_directory_lastname'];
}
$req_out = $bdd->query($sql);
    if ($req_out) {
        echo("La modification à été correctement effectuée") ;
        $sql2= "INSERT INTO `log` (`id`, `date`, `commentaire`) VALUES ('".$_SESSION['id']."', NOW(), 'A supprime les biens de ".$nom."')";
        $bdd->query($sql2);
    } else {
        echo("La modification à échouée") ;
    }    
?>
</tbody>
<?php include "../../includes/footer.php"; ?>
