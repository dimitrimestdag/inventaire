<tbody>
<?php session_start();
include('../../includes/header.php');
include "../../includes/connexion.php";
$loc = $_GET['loc'];
$sql = "UPDATE biens SET localisation = REPLACE(localisation, ".$loc.", 999999) WHERE localisation = ".$loc;
$req_out = $bdd->query($sql);
    if($req_out)
    {
      echo("La modification à été correctement effectuée") ;
      $sql2= "INSERT INTO `log` (`id`, `date`, `commentaire`) VALUES ('".$_SESSION['id']."', NOW(), 'A supprimer les biens de ".$loc."')";
      $bdd->query($sql2);
    }
    else
    {
      echo("La modification à échouée") ;
    }
	
?>
</tbody>
<?php include "../../includes/footer.php"; ?>