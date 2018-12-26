<?php session_start();
include('../../includes/header.php');
include('menu.php');
echo '<div id="principal">
<div id="titre_principal">Changer Votre Mot de Passe : '.Membre::info($_SESSION['id'], 'pseudo').'</div>
<form action="" method="post">
<br />
<table width="70%" align="center">';

if(!empty($_POST['changerPass'])) {
	$id = $_POST["id"];
	extract($_POST);
	echo '<tr>
	<td colspan="2" align="center">
	'.Membre::newPass($id, $newPassUn, $newPassDe).'
	<br /><br />
	</td>
	</tr>';
}
echo '<tr>
<td align="right"><select name="id">';
include "../../includes/connexion.php";
$sql = SELECT."id, pseudo".MEMBRE." ORDER BY pseudo ASC ";
$req = $bdd->query($sql);
while ($donnees = $req->fetch()) {
	echo '<option value="'.$donnees["id"].'">'.$donnees["pseudo"].'</option>';
}
echo '</select></td>
<tr>
<td align="right">Votre nouveau mot de passe : </td>
<td><input type="text" name="newPassUn"></td>
</tr>
<tr>
<td align="right">Saisir &agrave; nouveau le mot de passe : </td>
<td><input type="text" name="newPassDe"></td>
</tr>
<tr>
<td colspan="2" align="center"><br /><input type="submit" value="Valider le Nouveau Mot de Passe" name="changerPass" class="input"><br /><br /></td>
</tr>
</table>
</form>
</div>';
include('../../includes/footer.php');
?>