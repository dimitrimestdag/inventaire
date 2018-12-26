<tbody>
<?php
    include "../../includes/connexion.php";
    session_start();
    include('../../includes/header.php');
    include('menu.php');
    $ean = $_GET['ean'];
    $sql = 'DELETE FROM biens WHERE ean = '.$ean;
    $req_sup = $bdd->query($sql);
    if ($req_sup) {
        echo("La modification à été correctement effectuée") ;
        $sql2= "INSERT INTO `log` (`id`, `date`, `commentaire`) VALUES ('".$_SESSION['id']."', NOW(), 'A supprimer la ligne".$ean."')";
        $bdd->query($sql2);
    } else {
        echo("La modification à échouée") ;
    }
    
?>
</tbody>
<?php include "../../includes/footer.php"; ?>