<?php session_start();
$connect = '../includes/config.php';
if(!file_exists($connect)) {
	header('Location: install/');
}
include('header.php');
if(!empty($_POST['connect'])) {
	if(!Connexion::connexionCreate()) {
		echo '<center>
		<br />
		Redirection en cours ...
		<br />
		<img src="'.URLSITE.'/chargement.gif" width="150" height="30" />
		</center>';
		redirection(URLSITE.'/index.php', $time=3);
	}
}
else
	$captcha = new Captcha;
?>
<table>
	<thead>
		<form action="" method="post">
				<tr>
					<td colspan="3" style="color:white;">Connexion</td>
				</tr>
				<tr>
					<td style="color:white;">Identifiant : </td>
					<td><input type="text" name="login" /></td>
				</tr>
				<tr>
					<td style="color:white;">Mot de passe : </td>
					<td><input type="password" name="pass" /></td>
				</tr>
				<!-- <tr>
					<td style="color:white;"><?php //$captcha->captcha() ?></td>
					<td><input type="text" name="captcha" /></td>
				</tr> -->
				<tr>
					<td colspan="3" align="center"><input type="submit" name="connect" value="Se Connecter" class="input" /></td>
				</tr>
		</form>
		 
	</thead>
</table> 
<?php
include('../../includes/footer.php');
?>