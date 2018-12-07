<?php session_start();
include('includes/header.php');?>

<body>

	<div class="limiter">
		<div class="container-table100">
			<div class="wrap-table100">
				<div class="table100">
					<table>
						<?php include('theader.php');?>
						<tbody>
							<?php
							include "includes/connexion.php";
							
							if(isset($_GET['search'])) {
								$chainesearch = addslashes($_GET['search']);  
							} elseif(isset($_POST['search'])) {
								$chainesearch = addslashes($_POST['search']); 
							} else {
								$chainesearch = "";
							}
							if ($chainesearch <> "") { 
								include "includes/connexion.php";
								$requete = "SELECT * from biens WHERE ean LIKE '". $chainesearch 
									."%' OR localisation LIKE '". $chainesearch 
									."%' OR famille LIKE '". $chainesearch
									."%' OR marque LIKE '". $chainesearch
									."%' OR numdeserie LIKE '". $chainesearch
									."%' OR localisation IN (SELECT localisation FROM localisation WHERE nom LIKE '". $chainesearch
																								."%' OR prenom LIKE '". $chainesearch
									."%') OR modele LIKE '". $chainesearch ."%'";
								$requete2 = "SELECT * from localisation WHERE localisation LIKE '". $chainesearch 
									."%' OR nom LIKE '". $chainesearch
									."%' OR prenom LIKE '". $chainesearch ."%'"; 
								$resultat = $bdd->query($requete) or die(print_r($bdd->errorInfo()));
								$nb_fonc = 0;
								$sql = 'SELECT * FROM biens ORDER BY ean ASC';
								$cmpt = 0;
								while ($donnees = $resultat->fetch()) {
									$sql2 = "SELECT DISTINCT nom, prenom from localisation  WHERE localisation LIKE '". $donnees["localisation"] ."'";
									$req_loc = $bdd->query($sql2);
									$donnees3 = $req_loc->fetch();
									echo "<tr>";
									if (isset($donnees["ean"]) &&  ($donnees["ean"] <> "")) {
										echo "<td class='column1'>".$donnees["ean"]."</td>\n";
									}						
									if (isset($donnees["localisation"]) &&  ($donnees["localisation"] <> "")) {
										echo "<td class='column2'><a href='result.php?search=".$donnees["localisation"]."'>".$donnees["localisation"]."</a> (".$donnees3["prenom"]." ".$donnees3["nom"].")</td>\n";
									}
									if (isset($donnees["famille"]) &&  ($donnees["famille"] <> "")) {
										echo "<td class='column3'><a href='result.php?search=".$donnees["famille"]."'>".$donnees["famille"]."</a></td>\n";
									}
									if (isset($donnees["marque"]) &&  ($donnees["marque"] <> "")) {
										echo "<td class='column4'><a href='result.php?search=".$donnees["marque"]."'>".$donnees["marque"]."</a></td>\n";
									}
									if (isset($donnees["modele"]) &&  ($donnees["modele"] <> "")) {
										echo "<td class='column5'><a href='result.php?search=".$donnees["modele"]."'>".$donnees["modele"]."</a></td>\n";
									}
									if (isset($donnees["numdeserie"]) &&  ($donnees["numdeserie"] <> "")) {
										echo "<td class='column6'>".$donnees["numdeserie"]."</td>\n";
									}
								
										echo "<td class='column7'>".$donnees["numfacture"]."</td>\n";
									
									
										echo "<td class='column8'>".$donnees["montant"]."</td>\n";
									
									echo "</tr>";
								}
							}
							?>
						</tbody>
					</table>
					<div class="row justify-content-center">
						<div class="col-12 col-md-10 col-lg-8">
							<form class="card card-sm" action ="" method="post">
								<div class="card-body row no-gutters align-items-center">
									<div class="col">
										<input class="form-control form-control-lg form-control-borderless" type="search" placeholder="Rechercher un nom ou autre" name='search'>
									</div>
									<div class="col-auto">
										<button class="btn btn-lg btn-success" type="submit">Rechercher</button>
									</div>
								</div>
							</form>
						</div>
					</div>
					<?php include "includes/footer.php"?>