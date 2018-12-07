<?php 
include 'define.php';

// La fonction de redirection de base
function redirection($url, $time=0) {
	if (!headers_sent()) {
		header("refresh: $time;url=$url"); 
		exit;
	}
	else {
		echo '<meta http-equiv="refresh" content="',$time,';url=',$url,'">';
	}
}

// La classe de connexion a la bdd
class Bdd {
	private static $connexion = NULL;
	
	public static function connectBdd() {
		if(!self::$connexion) {
			self::$connexion = new PDO(DNS, USER, PASS);
			self::$connexion -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		return self::$connexion;
	}
	
} // Fin de la classe de connexion a la bdd

###########################################################################################

// La classe de recuperation de l'ip visiteur
class Ip {
	// function recuperation ip
	public static function get_ip() { 
		if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) { 
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} 
		elseif(isset($_SERVER['HTTP_CLIENT_IP'])) { 
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} 
		else { 
			$ip = $_SERVER['REMOTE_ADDR'];
		} 
		return $ip;
	}
} // Fin de la classe de recuperation de l'ip visiteur

###########################################################################################

// La classe de cryptage
class Cryptage {
	
	// Fonction de cryptage
	public static function crypter($var) {
		$sel = "48@tiOP";
		$Cript = md5($var);
		$crypt = sha1($Cript, $sel);
		return $crypt;
	}
	// creation d'une chaine aleatoire
	public static function chaine($nb_car, $chaine='AZERTYUIOPQSDFGHJKLMWXCVBNazertyuiopqsdfghjklmwxcvbn123456789') {
		$nb_lettres = strlen($chaine)-1;
		$generation = '';
		for($i=0; $i < $nb_car; $i++)
		{
			$pos = mt_rand(0, $nb_lettres);
			$car = $chaine[$pos];
			$generation .= $car;
		}
		return $generation;
	}
	
} // Fin de la classe de cryptage

###########################################################################################

// La classe connexion membre
class Connexion {
	
	// fonction de deconnexion
	// ecrasement des session dans un tableau
	// destruction du tableau
	// 		Si une page de redirection est choisi
	//			redirection vers la page
	public static function deconnexion($redirection) {
		$_SESSION = array();
		session_destroy();
		if(!empty($redirection)) {
			redirection(BASESITE.$redirection);
		}
	}
	// fonction de connexion des membres
	// Si verification du captcha => ok, et que l'identifiant et le mot de passe sont postes
	// 		Si login existe 
	//			Si mot de passe est ok 
	//				Creation de la session
	//				Enregistrement du jeton de connexion
	//				Redirection vers page au choix
	//					-> membre, moderateur, administrateur
	//			Si mot de passe faux => retourne faux
	// 		Si login existe pas => retourne faux
	// Si le captcha est faux => retourne faux
	public static function connexionCreate() {
		if(!empty($_POST['login']) AND !empty($_POST['pass'])) {
			if(Connexion::verifLogin($_POST['login'])) {
				if(Connexion::verifPass($_POST['pass'], $_POST['login'])) {
					$_SESSION['id'] = Membre::recupId($_POST['login']);
					$_SESSION['jeton'] = Connexion::jeton($_POST['login']);
					Connexion::niveau($_POST['login']);
				}
				else {
					return false;
				}
			}
			else {
				return false;
			}
		}
		else {
			return false;
		}
	}
	// Fonction de verification que l'identifiant existe dans la bdd
	public static function verifLogin($login) {
		$resultat = Bdd::connectBdd()->prepare(SELECT.ALL.MEMBRE.LOGIN);
		$resultat -> bindParam(':login', $login, PDO::PARAM_STR, 50);
		$resultat -> execute();
		if($resultat -> rowCount() === 1) {
			return true;
		}
		else {
			return false;
		}
	}
	// Function de verification du mot de passe
	public static function verifPass($pass, $login) {
		$resultat = Bdd::connectBdd()->prepare(SELECT.ALL.MEMBRE.LOGIN);
		$resultat -> bindParam(':login', $login, PDO::PARAM_STR, 50);
		$resultat -> execute();
		$donnee = $resultat -> fetch(PDO::FETCH_ASSOC);
		if(Cryptage::crypter($pass) === $donnee['password']) {
			return true;
		}
		else {
			return false;
		}
	}
	// La fonction de gestion des jetons de connexion lors de la connexion d'un membre
	// Si il esiste un jeton de connexion appertenant au membre qui se connecte avec la meme adresse ip
	// 	-> mise a jour de la date de connexion dans la table des jetons de connexion
	//  -> retourne le jeton
	// Si il n'existe pas 
	// 	-> creation d'un jeton de connexion
	// 	-> enregistrement du jeton
	//  -> retourne le jeton
	public static function jeton($login) {
		$id = Membre::recupId($login);
		$ip = Ip::get_ip();
		$date = time();
		$resultat = Bdd::connectBdd()->prepare(SELECT.ALL.JETON.JETONCONNEXION);
		$resultat -> bindParam(':id', $id, PDO::PARAM_INT, 11);
		$resultat -> bindParam(':ip', $ip);
		$resultat -> execute();
		if($resultat -> rowCount() === 1) {
			$donnee = $resultat->fetch(PDO::FETCH_ASSOC);
			$id = Membre::recupId($login);
			$maj = Bdd::connectBdd()->prepare(UPDATE.JETONZ.JETONDATE.JETONMEMBRE);
			$maj -> bindParam(':id', $id);
			$maj -> bindParam(':date', $date);
			$maj -> execute();
			return $donnee['jeton'];
		}
		else {
			$jeton = Cryptage::crypter(Cryptage::chaine(10));
			$insert = Bdd::connectBdd()->prepare(INSERT.JETONZ.JETONVALUES);
			$insert -> bindParam(':id', $id) ; 
			$insert -> bindParam(':jeton', $jeton) ;
			$insert -> bindParam(':ip', $ip);
			$insert -> bindParam(':date', $date);
			$insert -> execute();
			return $jeton;
		}
	}
	// Fonction de recuperation du niveau du membre
	// 	3 possibilite -> Membre, moderateur, administrateur
	//                ****************
	// Verification que le membre est actif
	// Si actif -> verification du niveau du membre
	// 		Redirection -> Membre, moderateur, administrateur
	// Si banni
	// 		-> redirection vers page d'information
	// Si pas actif
	// 		Recherche de la methode d'activation des membres
	//		-> activation auto -> retourne la fonction au debut
	// 		-> activation par mail -> envoie le mail d'activation puis redirige vers une page d'information
	// 		-> activation maunel -> redirige vers une page d'information
	public static function niveau($login) {
		$resultat = Bdd::connectBdd()->prepare(SELECT.ALL.MEMBRE.LOGIN);
		$resultat -> bindParam(':login', $login, PDO::PARAM_STR, 50);
		$resultat -> execute();
		$donnee = $resultat->fetch(PDO::FETCH_ASSOC);
		if($donnee['activation'] === '1') {
			switch($donnee['niveau']) {
				case 1 :
				$_SESSION['niveau'] = '1'; 
				$redirect = redirection(URLSITE.'/membre/index.php');
				break;
				
				case 2 :
				$_SESSION['niveau'] = '2';
				$redirect = redirection(URLSITE.'/moderateur/index.php');
				break;
				
				case 3 :
				$_SESSION['niveau'] = '3';
				$redirect = redirection(BASESITE.'admin');
				break;
			}
		}
		elseif($donnee['activation'] === '5') {
			$redirect = redirection(URLSITE.'/banni.php');
		}
		else {
			$activation = Bdd::connectBdd()->prepare(SELECT.ALL.ACTIVATION.METHODEACTIV);
			$activation -> execute();
			$methode = $activation->fetch(PDO::FETCH_ASSOC);
			switch($methode['id']) {
				case 1 :
				Activation::activationAuto($login);
				return Connexion::niveau($login);
				break;
				
				case 2 :
				Activation::activationMail($login);
				$redirect = redirection(URLSITE.'/activationMail.php');
				break;	
				
				case 3 :
				$redirect = redirection(URLSITE.'/activationAdmin.php');
				break;	
			}
		}
		return $redirect;
	}
	
} // Fin de la classe de connexion membre

###########################################################################################

// La classe de protection des espaces -> membre, moderateur et administrateur
class ProtectEspace {
	
	// protection de l'espace membre
	// Verification que les parametres de sessions existent
	// $id => $_session['id']
	// $captcha => $_session['captcha']
	// $jeton=> $_session['jeton']
	// $niveau => $_session['niveau']
	// verification que le niveau n'est pas different de 1
	// Si il y a une erreur
	// 		redirection vers la page de deconnexion
	// Sinon
	//		Verification que le jeton de connexion appartient bien au membre connecte
	//		Si ce n'est pas le cas
	//			redirection vers la deconnexion
	// 		Sinon 
	//			Si le membre est banni
	//				Redirection vers la page d'information de bannissement
	//			Sinon
	//				Retourne Vrai
	//...
	// protection de l'espace administrateur
	// Verification que les parametres de sessions existent
	// $id => $_session['id']
	// $captcha => $_session['captcha']
	// $jeton=> $_session['jeton']
	// $niveau => $_session['niveau']
	// verification que le niveau n'est pas different de 3
	// Si il y a une erreur
	// 		redirection vers la page de deconnexion
	// Sinon
	//		Verification que le jeton de connexion appartient bien a l'administrateur connecte
	//		Si ce n'est pas le cas
	//			redirection vers la deconnexion
	// 		Sinon 
	//			Si le membre est banni
	//				Redirection vers la page d'information de bannissement
	//			Sinon
	//				Retourne Vrai
	public static function administrateur($id, $jeton, $niveau) {
		if(empty($id) OR empty($jeton)) {
			redirection(URLSITE.'/deconnexion.php');
		}
		else {
			if($niveau !== '3') {
				redirection(URLSITE.'/deconnexion.php');
			}
			$resultat = Bdd::connectBdd()->prepare(SELECT.ALL.JETON.JETONSESSION);
			$resultat -> bindParam(':id', $id, PDO::PARAM_INT, 11);
			$resultat -> bindParam(':jeton', $jeton);
			$resultat -> execute();
			if($resultat -> rowCount() !== 1) {
				redirection(URLSITE.'/deconnexion.php');
			}
			else {
				if(Membre::info($id, 'activation') === '5') {
					redirection(URLSITE.'/banni.php');
				}
				return true;
			}
		}
	}
	// compte le nombre de jeton de connexion pour le membre
	public static function compteJeton($id) {
		$resultat = Bdd::connectBdd()->prepare(SELECT.ALL.JETON.JETONMEMBRE);
		$resultat -> bindParam(':id', $id, PDO::PARAM_INT, 11);
		$resultat -> execute();
		return '<a href="ip">il y a '.$resultat -> rowCount().' adresse(s) ip qui se connecte(nt) &agrave; votre espace membre.</a>';
	}
	// Liste des jeton de connexion du membre
	public static function listeJeton($id) {
		$liste = '';
		$resultat = Bdd::connectBdd()->prepare(SELECT.ALL.JETON.JETONMEMBRE);
		$resultat -> bindParam(':id', $id, PDO::PARAM_INT, 11);
		$resultat -> execute();
		while($jeton = $resultat -> fetch(PDO::FETCH_ASSOC)) {
			$liste .= '<tr>
					<td align="center">Le '.date('d/m/Y', $jeton['date']).' &agrave; '.date('H:i:s', $jeton['date']).'</td>
					<td align="center">'.$jeton['ip_connexion'].'</td>
					<td align="center">
					<form method="post" action="">
					<input type="hidden" value="'.$jeton['id'].'" name="id_jeton">
					<input type="submit" value="Supprimer" name="supprime_connexion" class="input" />
					</form>
					</td>
				</tr>';
		}
		return $liste;
	}
	// effacer un jeton de connexion
	public static function deleteJeton($id) {
		$resultat = Bdd::connectBdd()->prepare(DELETE.JETON.ID);
		$resultat -> bindParam(':id', $id, PDO::PARAM_INT, 11);
		$resultat -> execute();
	}
	
}

###########################################################################################

// La classe Membre
class Membre {
	//Fonction de recuperation de l'id d'un membre
	public static function recupId($login) {
		$resultat = Bdd::connectBdd()->prepare(SELECT.ALL.MEMBRE.LOGIN);
		$resultat -> bindParam(':login', $login, PDO::PARAM_STR, 50);
		$resultat -> execute();
		$donnee = $resultat -> fetch(PDO::FETCH_ASSOC);
		return $donnee['id'];
	}
	// Fonction de recuperation des infos membre
	// $id => id du membre
	// $info => information qu l'on veux
	public static function info($id, $info) {
		$resultat = Bdd::connectBdd()->prepare(SELECT.ALL.MEMBRE.ID);
		$resultat -> bindParam(':id', $id, PDO::PARAM_INT, 11);
		$resultat -> execute();
		$infoMembre = $resultat -> fetch(PDO::FETCH_ASSOC);
		return $infoMembre[$info];
	}
	// protection affichage information membre
	public static function protectInfo($id, $info) {
		$resultat = Bdd::connectBdd()->prepare(SELECT.ALL.ACCESPROFIL.IDMEMBRE);
		$resultat -> bindParam(':id', $id, PDO::PARAM_INT, 11);
		$resultat -> execute();
		$donnee = $resultat -> fetch(PDO::FETCH_ASSOC);
		if($donnee[$info] === '1') {
			$affiche = Membre::info($id, $info);
		}
		else {
			$affiche = 'Non disponible';
		}
		return $affiche;
	}
	// changer autorisation d'une information du profil
	// deux choix :
	// 		-> Rendre visible aux autres membres
	//		-> Cacher l'information aux autres membres
	//  		***************************
	public static function profilVisibilite($id, $info) {
		$resultat = Bdd::connectBdd()->prepare(SELECT.ALL.ACCESPROFIL.IDMEMBRE);
		$resultat -> bindParam(':id', $id, PDO::PARAM_INT, 11);
		$resultat -> execute();
		$donnee = $resultat -> fetch(PDO::FETCH_ASSOC);
		if($donnee[$info] === '1') {
			$maj = '0';
		}
		else {
			$maj = '1';
		}
		$update = Bdd::connectBdd()->prepare(UPDATE.ACCESPROFILZ.' SET '.$info.MAJACCESPROFIL.IDMEMBRE);
		$update -> bindParam(':maj', $maj);
		$update -> bindParam(':id', $id, PDO::PARAM_INT, 11);
		$update -> execute();
		redirection('profil.php');
	}
	// visibilite d'une information d'un membre
	public static function visibilite($id, $info) {
		$resultat = Bdd::connectBdd()->prepare(SELECT.ALL.ACCESPROFIL.IDMEMBRE);
		$resultat -> bindParam(':id', $id, PDO::PARAM_INT, 11);
		$resultat -> execute();
		$donnee = $resultat -> fetch(PDO::FETCH_ASSOC);
		if($donnee[$info] === '1') {
			return 'Cacher';
		}
		else {
			return 'Rendre Visible';
		}
	}
	// Mise a jour du profil du membre
	public static function majProfil($id, $naissance, $genre, $nom, $prenom, $email, $facebook, $twister, $site, $tel, $adresse, $cp, $ville, $mailing, $description) {
		$description = filter_var($description, FILTER_SANITIZE_STRING);
		$description = nl2br($description);
		$resultat = Bdd::connectBdd()->prepare(UPDATE.MEMBREZ.MAJPROFIL);
		$resultat -> bindParam(':email', $email);
		$resultat -> bindParam(':tel', $tel);
		$resultat -> bindParam(':adresse', $adresse);
		$resultat -> bindParam(':cp', $cp);
		$resultat -> bindParam(':ville', $ville);
		$resultat -> bindParam(':genre', $genre);
		$resultat -> bindParam(':naissance', $naissance);
		$resultat -> bindParam(':nom', $nom);
		$resultat -> bindParam(':prenom', $prenom);
		$resultat -> bindParam(':facebook', $facebook);
		$resultat -> bindParam(':twister', $twister);
		$resultat -> bindParam(':site', $site);
		$resultat -> bindParam(':description', $description);
		$resultat -> bindParam(':mailing', $mailing);
		$resultat -> bindParam(':id', $id, PDO::PARAM_INT, 11);
		$resultat -> execute();
		redirection('profil.php');
	}
	// changer de mot de passe
	public static function newPass($id/*, $passActuel*/, $newPassUn, $newPassDe) {
		if(/*!empty($passActuel) AND*/ !empty($newPassUn) AND !empty($newPassDe)) {
			if($newPassUn === $newPassDe) {
				$verifPass = Bdd::connectBdd()->prepare(SELECT.ALL.MEMBRE.ID);
				$verifPass -> bindParam(':id', $id, PDO::PARAM_INT, 11);
				$verifPass -> execute();
				$dataPass = $verifPass -> fetch(PDO::FETCH_ASSOC);
				//if($dataPass['password'] === Cryptage::crypter($passActuel)) {
					$newPass = Cryptage::crypter($newPassUn);
					$majPass = Bdd::connectBdd()->prepare(UPDATE.MEMBREZ.MAJPASS.ID);
					$majPass -> bindParam(':newPass', $newPass);
					$majPass -> bindParam(':id', $id, PDO::PARAM_INT, 11);
					if($majPass -> execute()) {
						$resultat = 'Votre mot de passe a &eacute;t&eacute; chang&eacute; avec succ&egrave;s.';
					}
					else {
						$resultat = 'Une erreur est survenue pendant la mise &agrave; jour de votre mot de passe.';
					}
				//}
				//else {
				//	$resultat = 'Vous n\'avez pas saisi correctement votre mot de passe actuel,<br />veuillez recommencer.';
				//}
			}
			else {
				$resultat = 'Les champs &quot;Votre nouveau mot de passe&quot; et &quot;Saisir &agrave; nouveau le mot de passe&quot; doivent &ecirc;tre identiques,<br />veuillez recommencer.';
			}
		}
		else {
			$resultat = 'Pour changer de mot de passe vous devez remplir tout les champs,<br />veuillez recommencer.';
		}
		return $resultat;
	}
	
} // Fin de la classe Membre


###########################################################################################

// La classe Info sur le site
class InfoSite {
	
	// Nombre de membres
	public static function membreNb() {
		$resultat = Bdd::connectBdd()->prepare(SELECT.ALL.MEMBRE);
		$resultat -> execute();
		if($resultat -> rowCount() === 0) {
			return 'Il y a aucun membre inscrit';
		}
		else {
			return 'Il y a '.$resultat -> rowCount().' membres inscrits';
		}
	}
	// Liste des activations possible
	public static function listeActivation() {
		$liste = '';
		$resultat = Bdd::connectBdd()->prepare(SELECT.ALL.ACTIVATION);
		$resultat -> execute();
		while($option = $resultat -> fetch(PDO::FETCH_ASSOC)) {
			if($option['activation'] === '1') {
				$liste .= '<option value="'.$option['id'].'" selected="selected">'.$option['mode'].'</option>';
			}
			else {
				$liste .= '<option value="'.$option['id'].'">'.$option['mode'].'</option>';
			}
		}
		return $liste;
	}
	// changer le mode d'activation
	public static function activationChange($id) {
		$activ = '1';
		$oui = Bdd::connectBdd()->prepare(UPDATE.ACTIVATIONZ.CHANGEMETOD.ID);
		$oui -> bindParam(':activ', $activ);
		$oui -> bindParam(':id', $id, PDO::PARAM_INT, 11);
		if($oui -> execute()) {
			$desac = '0';
			$non = Bdd::connectBdd()->prepare(UPDATE.ACTIVATIONZ.CHANGEMETOD.NOI);
			$non -> bindParam(':activ', $desac);
			$non -> bindParam(':id', $id, PDO::PARAM_INT, 11);
			$non -> execute();
		}
		redirection(URLSITE.'/administrateur/activation.php');
	}
	// Liste des membres pour les administrateurs
	public static function listeMembre($id) {
		$liste = '';
		$resultat = Bdd::connectBdd()->prepare(SELECT.ALL.MEMBRE.' ORDER BY activation ASC');
		$resultat -> bindParam(':id', $id, PDO::PARAM_INT, 11);
		$resultat -> execute();
		while($donnee = $resultat -> fetch(PDO::FETCH_ASSOC)) {
			$idMembre = $donnee['id'];
			$pseudo = $donnee['pseudo'];
			if($donnee['activation'] === '5') {
				$niveau = 'Banni';
				$action = '<input type="hidden" value="'.$donnee['id'].'" name="id"> <input type="submit" name="debannir" value="D&eacute;bannir" class="input"> <input type="submit" name="supprim" value="Supprimer" class="input">';
			}
			elseif($donnee['activation'] === '0') {
				$niveau = 'Nouvel(le) inscrit(e)';
				$action = '<input type="hidden" value="'.$donnee['id'].'" name="id"><input type="submit" name="inscription" value="Valider l\'inscription" class="input">';
			}
			else {
				switch($donnee['niveau']) {
					case 1 :
					$niveau = 'Membre';
					$action = '<input type="hidden" value="'.$donnee['id'].'" name="id"> <input type="submit" name="bannir" value="Bannir" class="input"> <input type="submit" name="moderateur" value="Passer Mod&eacute;rateur" class="input">';
					break;
					case 2 :
					$niveau = 'Mod&eacute;rateur';
					$action = '<input type="hidden" value="'.$donnee['id'].'" name="id"> <input type="submit" name="bannir" value="Bannir" class="input"> <input type="submit" name="membre" value="Repasser Membre" class="input">';
					break;
					case 3 :
					$niveau = 'Administrateur';
					$action = '';
					break;
					case 4 :
					$niveau = 'Cr&eacute;ateur';
					$action = '<input type="hidden" value="'.$donnee['id'].'" name="id"> <input type="submit" name="supprim" value="Supprimer" class="input">';
					break;
				}
			}
			$liste .= '<tr>
					<td align="center"><a href="profil_membre.php?id='.$idMembre.'">'.$pseudo.'</a></td>
					<td align="center">'.$niveau.'</td>
					<td align="center"><form action="" method="post">'.$action.'</form></td>
				</tr>';
		}
		return $liste;
	}
	// Liste des membres pour les moderateurs
	public static function listeMembreModo($id) {
		$liste = '';
		$resultat = Bdd::connectBdd()->prepare(SELECT.ALL.MEMBRE.' ORDER BY activation ASC');
		$resultat -> bindParam(':id', $id, PDO::PARAM_INT, 11);
		$resultat -> execute();
		while($donnee = $resultat -> fetch(PDO::FETCH_ASSOC)) {
			$idMembre = $donnee['id'];
			$pseudo = $donnee['pseudo'];
			if($donnee['activation'] === '5') {
				$niveau = 'Banni';
				$action = '<input type="hidden" value="'.$donnee['id'].'" name="id"> <input type="submit" name="debannir" value="D&eacute;bannir" class="input">';
			}
			elseif($donnee['activation'] === '0') {
				$niveau = 'Nouvel(le) inscrit(e)';
				$action = '<input type="hidden" value="'.$donnee['id'].'" name="id"><input type="submit" name="inscription" value="Valider l\'inscription" class="input">';
			}
			else {
				switch($donnee['niveau']) {
					case 1 :
					$niveau = 'Membre';
					$action = '<input type="hidden" value="'.$donnee['id'].'" name="id"> <input type="submit" name="bannir" value="Bannir" class="input">';
					break;
					case 2 :
					$niveau = 'Mod&eacute;rateur';
					$action = '';
					break;
					case 3 :
					$niveau = 'Administrateur';
					$action = '';
					break;
					case 4 :
					$niveau = 'Cr&eacute;ateur';
					$action = '';
					break;
				}
			}
			$liste .= '<tr>
					<td align="center"><a href="profil_membre.php?id='.$idMembre.'">'.$pseudo.'</a></td>
					<td align="center">'.$niveau.'</td>
					<td align="center"><form action="" method="post">'.$action.'</form></td>
				</tr>';
		}
		return $liste;
	}
	
} // Fin de la classe Info sur le site

###########################################################################################

// La classe administrateur
class Admin {
	
	// bannir un membre
	public static function bannir($id, $messagePost) {
		$activ = '5';
		$resultat = Bdd::connectBdd()->prepare(UPDATE.MEMBREZ.ACTIVMEMBRE.ID);
		$resultat -> bindParam(':activer', $activ);
		$resultat -> bindParam(':id', $id, PDO::PARAM_INT, 11);
		$resultat -> execute();
		//				*******************				//
		$headers ='From: "'.Membre::info($login, 'nom').' '.Membre::info($login, 'prenom').'"'.Membre::info($login, 'email').''."\n";
		$headers .='Reply-To: '.MAILSITE.''."\n";
		$headers .='Content-Type: text/plain; charset="iso-8859-1"'."\n";
		$headers .='Content-Transfer-Encoding: 8bit'; 
		$sujet = "Bannissement l'espace membre ".NOMSITE;
		$message = 'Bonjour '.Membre::info($login, 'pseudo').','."\n\n";
		$message .= 'L\'administarteur du site '.NOMSITE.' vous a banni de l\'espace membre.'."\n\n";
		$message .= 'Voici la raison : '."\n";
		$message .= $messagePost."\n";
		$message .= 'Cordialement,'."\n";
		$message .= NOM_SITE.'.'."\n";
		mail(MAIL_SITE, $sujet, $message, $headers);
		//				*******************				//
		redirection(URLSITE.'/administrateur/listeMembre.php');
	}
	// debannir un membre
	public static function debannir($id) {
		$activ = '1';
		$resultat = Bdd::connectBdd()->prepare(UPDATE.MEMBREZ.ACTIVMEMBRE.ID);
		$resultat -> bindParam(':activer', $activ);
		$resultat -> bindParam(':id', $id, PDO::PARAM_INT, 11);
		$resultat -> execute();
		redirection(URLSITE.'/administrateur/listeMembre.php');
	}
	// Passer un membre -> moderateur
	public static function passeModo($id) {
		$niveau = '2';
		$resultat = Bdd::connectBdd()->prepare(UPDATE.MEMBREZ.NIVEAU.ID);
		$resultat -> bindParam(':niveau', $niveau);
		$resultat -> bindParam(':id', $id, PDO::PARAM_INT, 11);
		$resultat -> execute();
		redirection(URLSITE.'/administrateur/listeMembre.php');
	}
	// passer un moderateur -> membre
	public static function passeMembre($id) {
		$niveau = '1';
		$resultat = Bdd::connectBdd()->prepare(UPDATE.MEMBREZ.NIVEAU.ID);
		$resultat -> bindParam(':niveau', $niveau);
		$resultat -> bindParam(':id', $id, PDO::PARAM_INT, 11);
		$resultat -> execute();
		redirection(URLSITE.'/administrateur/listeMembre.php');
	}
		
} // Fin de la classe administrateur
?>






























