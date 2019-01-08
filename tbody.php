<tbody>
<?php
	include "includes/connexion.php";
	$sql_ean_biensASC = "SELECT DISTINCT ean from biens ORDER BY ean ASC";
	$req_fonc = $bdd->query($sql_ean_biensASC);
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
	$cmpt = 0;
	$sql_biensASC = 'SELECT * FROM biens ORDER BY ean ASC LIMIT '.$_GET['debut'].','.$nb_affichage_par_page;
	$req_user = $bdd->query($sql_biensASC);
	$chemin_facture =  "factures/facture-";
	
	while ($donnees = $req_user->fetch()) {
		$loc = $donnees["localisation"];
		$sql_ad_nomprenom = "SELECT DISTINCT active_directory_lastname, active_directory_firstname from tbl_import_active_directory  WHERE active_directory_uid_number LIKE '$loc' ";

		$req_loc = $bdd2->query($sql_ad_nomprenom);
		$donnees2 = $req_loc->fetch();
		$sql_extensionfichier= "SELECT extension FROM upload WHERE nom LIKE 'facture-".$donnees["numfacture"]."'";
		$req_extension = $bdd->query($sql_extensionfichier);
		while ($donnees3 = $req_extension->fetch()) {
			$ext = $donnees3['extension'];
		}
		echo "<tr>";
			echo "<td class='column1'>".$donnees["ean"]."</td>\n";
			echo "<td class='column2'><a href='".BASESITE."result.php?search=".$donnees["localisation"]."'>".$donnees["localisation"]."</a> (".$donnees2["active_directory_firstname"]." ".$donnees2["active_directory_lastname"].")</td>\n";
			echo "<td class='column3'><a href='".BASESITE."result.php?search=".$donnees["famille"]."'>".$donnees["famille"]."</a></td>\n";
			echo "<td class='column4'><a href='".BASESITE."result.php?search=".$donnees["marque"]."'>".$donnees["marque"]."</a></td>\n";
			echo "<td class='column5'><a href='".BASESITE."result.php?search=".$donnees["modele"]."'>".$donnees["modele"]."</a></td>\n";
			echo "<td class='column6'>".$donnees["numdeserie"]."</td>\n";
            if (file_exists($chemin_facture.$donnees["numfacture"].'.'.$ext)) {
                echo "<td class='column7'><a href='".BASESITE."admin/download-".$donnees["numfacture"]."'>".$donnees["numfacture"]."</a></td>\n";
            }else{
                echo "<td class='column7'>".$donnees["numfacture"]."</td>\n";
            }
			echo "<td class='column8'>".$donnees["montant"]."</td>\n";
		if (isset($_SESSION['id']) ) {
			echo "<td class='column9'><a href='".BASESITE."admin/ligne-".$donnees["ean"]."'><img src='".BASESITE."images/modif.png' height='35' width='35'></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
            if ($_SESSION['id'] == 2 || $_SESSION['id'] == 3) {
				echo "<a href='".BASESITE."admin/supligne-".$donnees["ean"]."' onClick=\"return confirm('Êtes-vous sûr de vouloir supprimer la ligne ?')\"><img src='".BASESITE."images/corbeille.png' height='35' width='35'></a>&nbsp;";
			}
			if (isset($donnees["numfacture"]) ) {
				echo "<a href='".BASESITE."admin/upload-".$donnees["numfacture"]."'><img src='".BASESITE."images/upload.png' height='35' width='35'></a>";
			} else {
			echo "<button onClick=\"myFunction()\"><img src='".BASESITE."images/upload.png' height='35' width='35'></button>";
			}
			echo "</td>";
		}
		echo "</tr>";
	}
	$req_fonc->closeCursor(); 
	?>	
</tbody>