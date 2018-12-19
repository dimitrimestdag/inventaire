<?php //session_start();
include('includes/define.php');?>
<!DOCTYPE html>
<html lang="fr">
	<head>
	<title>Inventaire</title>
	<meta charset="UTF-8">
	<meta name="author" content="Dimitri Mestdag" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html;charset=utf8" />
	<!--===============================================================================================-->
	<link rel="icon" type="image/png" href="<?php echo BASESITE; ?>images/icons/favicon.ico" />
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo BASESITE; ?>vendor/bootstrap/css/bootstrap.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo BASESITE; ?>fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo BASESITE; ?>vendor/animate/animate.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo BASESITE; ?>vendor/css-hamburgers/hamburgers.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo BASESITE; ?>vendor/select2/select2.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo BASESITE; ?>vendor/perfect-scrollbar/perfect-scrollbar.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo BASESITE; ?>css/util.css">
	<link rel="stylesheet" type="text/css" href="<?php echo BASESITE; ?>css/main.css">
	<link rel="stylesheet" type="text/css" href="<?php echo BASESITE; ?>css/mainlogin.css">
	<!--===============================================================================================-->

</head>
	<body>
	<div class="limiter">
		<div class="container-table100">
			<div class="wrap-table100">
				<div class="table100">
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
								$sqlad = "SELECT active_directory_uid_number FROM tbl_import_active_directory WHERE active_directory_lastname LIKE '". $chainesearch
								."%' OR active_directory_firstname LIKE '". $chainesearch."%'";
								$resultat2 = $bdd2->query($sqlad);
								$i=0;
								$requete = "SELECT * from biens WHERE ean LIKE '%". $chainesearch 
																	."%' OR localisation LIKE '". $chainesearch 
																	."%' OR famille LIKE '". $chainesearch
																	."%' OR marque LIKE '". $chainesearch
																	."%' OR numdeserie LIKE '". $chainesearch
																	."%' OR localisation ='".$chainesearch."' OR modele LIKE '". $chainesearch ."%'";
								                              
								$resultat = $bdd->query($requete) or die(print_r($bdd->errorInfo()));
								while ($donnees2 = $resultat2->fetch()) {
									$tabloc = array($i => $donnees2['active_directory_uid_number']);
									$requete = "SELECT * from biens WHERE ean LIKE '%". $chainesearch
																	."%' OR localisation LIKE '". $chainesearch
																	."%' OR famille LIKE '". $chainesearch
																	."%' OR marque LIKE '". $chainesearch
																	."%' OR numdeserie LIKE '". $chainesearch
																	."%' OR localisation =".$tabloc[$i]." OR modele LIKE '". $chainesearch ."%'";
									$i++;
									$resultat = $bdd->query($requete) or die(print_r($bdd->errorInfo()));
									while ($donnees = $resultat->fetch()) {
										$sql2 = "SELECT DISTINCT nom, prenom from localisation  WHERE localisation LIKE '". $donnees["localisation"] ."'";
										$req_loc = $bdd->query($sql2);
										$donnees3 = $req_loc->fetch();
										echo "<tr>";
											echo "<td class='column1'>".$donnees["ean"]."</td>\n";
											echo "<td class='column2'><a href='result.php?search=".$donnees["localisation"]."'>".$donnees["localisation"]."</a> (".$donnees3["prenom"]." ".$donnees3["nom"].")</td>\n";
											echo "<td class='column3'><a href='result.php?search=".$donnees["famille"]."'>".$donnees["famille"]."</a></td>\n";
											echo "<td class='column4'><a href='result.php?search=".$donnees["marque"]."'>".$donnees["marque"]."</a></td>\n";
											echo "<td class='column5'><a href='result.php?search=".$donnees["modele"]."'>".$donnees["modele"]."</a></td>\n";
											echo "<td class='column6'>".$donnees["numdeserie"]."</td>\n";
										echo "<td class='column7'>".$donnees["numfacture"]."</td>\n";
										echo "<td class='column8'>".$donnees["montant"]."</td>\n";
										if (isset($_SESSION['id'])) {
											echo "<td class='column9'><a href='".BASESITE."admin/ligne-".$donnees["ean"]."'><img src='".BASESITE."images/modif.png' height='35' width='35'></a>";
											if ($_SESSION['id'] == 2 || $_SESSION['id'] == 3) {
												echo "<a href='".BASESITE."admin/supligne-".$donnees["ean"]."' onClick=\"return confirm('Êtes-vous sûr de vouloir supprimer la ligne ?')\"><img src='".BASESITE."images/corbeille.png' height='35' width='35'></a>";
											}
											echo "</td>";
										}
										echo "</tr>";
									}
								}
								while ($donnees = $resultat->fetch()) {
									$sql2 = "SELECT DISTINCT nom, prenom from localisation  WHERE localisation LIKE '". $donnees["localisation"] ."'";
									$req_loc = $bdd->query($sql2);
									$donnees3 = $req_loc->fetch();
									echo "<tr>";
										echo "<td class='column1'>".$donnees["ean"]."</td>\n";
										echo "<td class='column2'><a href='result.php?search=".$donnees["localisation"]."'>".$donnees["localisation"]."</a> (".$donnees3["prenom"]." ".$donnees3["nom"].")</td>\n";
										echo "<td class='column3'><a href='result.php?search=".$donnees["famille"]."'>".$donnees["famille"]."</a></td>\n";
										echo "<td class='column4'><a href='result.php?search=".$donnees["marque"]."'>".$donnees["marque"]."</a></td>\n";
										echo "<td class='column5'><a href='result.php?search=".$donnees["modele"]."'>".$donnees["modele"]."</a></td>\n";
										echo "<td class='column6'>".$donnees["numdeserie"]."</td>\n";
										echo "<td class='column7'>".$donnees["numfacture"]."</td>\n";
										echo "<td class='column8'>".$donnees["montant"]."</td>\n";
									if (isset($_SESSION['id'])) {
										echo "<td class='column9'><a href='".BASESITE."admin/ligne-".$donnees["ean"]."'><img src='".BASESITE."images/modif.png' height='35' width='35'></a>";
										if ($_SESSION['id'] == 2 || $_SESSION['id'] == 3) {
											echo "<a href='".BASESITE."admin/supligne-".$donnees["ean"]."' onClick=\"return confirm('Êtes-vous sûr de vouloir supprimer la ligne ?')\"><img src='".BASESITE."images/corbeille.png' height='35' width='35'></a>";
										}
										echo "</td>";
									}
								}
								echo "</tr>";
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