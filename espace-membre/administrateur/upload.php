<?php
include "../../includes/connexion.php";
session_start();
include('../../includes/header.php');
include('menu.php');
$num = $_GET['num'];

echo $num ;
?>

<form method="post" enctype="multipart/form-data">
 <div>
 <?php $nom =  "factures/facture-".$num ; ?>
   <input type="file" id="file" name="file"/>
 </div>
 <div>
   <button>Envoyer</button>
 </div>
</form>
<?php
if (isset($_FILES['file']['tmp_name'])) {
    if ($_FILES['file']['error'] > 0) {
        $erreur = "Erreur lors du transfert";
    }
    $ext = substr(strrchr($_FILES['file']['name'],'.'),1);
    $resultat = move_uploaded_file($_FILES['file']['tmp_name'], $nom.".".$ext);
    if ($resultat) {
        echo "Transfert réussi, redirection dans un instant ...";
        $sql_log_upload= "INSERT INTO `log` (`id`, `date`, `commentaire`) VALUES ('".$_SESSION['id']."', NOW(), 'A uploadé facture-".$num."')";
        $bdd->query($sql_log_upload);
        $sql_info_upload= "INSERT INTO `upload` (`nom`, `date`, `taille`, `extension`) VALUES ('facture-".$num."', NOW(),'".$_FILES['file']['size']."', '".$ext."')";
        $bdd->query($sql_info_upload);
        redirection(BASESITE.'admin/inventaire', $time=5);
    }
}
?>
