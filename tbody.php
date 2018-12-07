
<tbody>
<?php
	include "includes/connexion.php";
	include "includes/barrenavigation.php";
	$req_fonc = $bdd->query("SELECT DISTINCT ean from biens ORDER BY ean ASC");
	function NombreTotal ($req_fonc){
		$nb_total = 0;
		while ($fonc[$nb_total] = $req_fonc->fetch()) {
			$nb_total ++;
		}
		return $nb_total;
	}
	$nb_total = NombreTotal($req_fonc);
	if (!isset($_GET['debut'])) $_GET['debut'] = 0;
	$nb_affichage_par_page = 40;
	$nb_fonc = 0;
	while ($fonc[$nb_fonc] = $req_fonc->fetch()) {
		if ($nb_fonc == 0) {
			$nb_fonc++;
		}
		else {
			$nb_fonc++;
		}
		
	}
	$sql = 'SELECT * FROM biens ORDER BY ean ASC LIMIT '.$_GET['debut'].','.$nb_affichage_par_page;
	$cmpt = 0;
	$req_user = $bdd->query($sql);
	while ($donnees = $req_user->fetch()) {
		$loc = $donnees["localisation"];
		$sql2 = "SELECT DISTINCT nom, prenom from localisation  WHERE localisation LIKE '$loc' ";
		$req_loc = $bdd->query($sql2);	// On récupère tout le contenu de la table
		$donnees2 = $req_loc->fetch();
		echo "<tr>";
			echo "<td class='column1'>".$donnees["ean"]."</td>\n";
			echo "<td class='column2'><a href='".BASESITE."result.php?search=".$donnees["localisation"]."'>".$donnees["localisation"]."</a> (".$donnees2["prenom"]." ".$donnees2["nom"].")</td>\n";
			echo "<td class='column3'><a href='".BASESITE."result.php?search=".$donnees["famille"]."'>".$donnees["famille"]."</a></td>\n";
			echo "<td class='column4'><a href='".BASESITE."result.php?search=".$donnees["marque"]."'>".$donnees["marque"]."</a></td>\n";
			echo "<td class='column5'><a href='".BASESITE."result.php?search=".$donnees["modele"]."'>".$donnees["modele"]."</a></td>\n";
			echo "<td class='column6'>".$donnees["numdeserie"]."</td>\n";
			echo "<td class='column7'>".$donnees["numfacture"]."</td>\n";
			echo "<td class='column8'>".$donnees["montant"]."</td>\n";

		if (isset($_SESSION['id']) ) {
			echo "<td class='column9'><a href='".BASESITE."admin/ligne-".$donnees["ean"]."'><img src='".BASESITE."images/modif.png' height='35' width='35'></a>";
            if ($_SESSION['id'] == 2 || $_SESSION['id'] == 3) {
                echo "<a href='".BASESITE."admin/supligne-".$donnees["ean"]."' onClick=\"return confirm('Êtes-vous sûr de vouloir supprimer la ligne ?')\"><img src='".BASESITE."images/corbeille.png' height='35' width='35'></a>";
			}
			echo "</td>";
		}

		echo "</tr>";
	}

	
	$req_fonc->closeCursor(); 
	?>
		
</tbody>