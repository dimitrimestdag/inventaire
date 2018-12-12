<?php
// connexion bdd    
		$BDD_hote = 'localhost';
		$BDD_bd = 'inventaire';
		$BDD_utilisateur = 'root';
		$BDD_mot_passe = '';
	
	try{
				$bdd = new PDO('mysql:host='.$BDD_hote.';dbname='.$BDD_bd, $BDD_utilisateur, $BDD_mot_passe, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

				$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
			}
				catch(PDOException $e){
				echo 'Erreur : '.$e->getMessage();
				echo 'N° : '.$e->getCode();
			}      
		$BDD_hote2 = 'db-toutankama';
		$BDD_bd2 = 'toutankama';
		$BDD_utilisateur2 = 'dmestdag';
		$BDD_mot_passe2 = '';

	try{
				$bdd2 = new PDO('mysql:host='.$BDD_hote2.';dbname='.$BDD_bd2, $BDD_utilisateur2, $BDD_mot_passe2, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

				$bdd2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
			}
				catch(PDOException $e){
				echo 'Erreur : '.$e->getMessage();
				echo 'N° : '.$e->getCode();
			}      
?>