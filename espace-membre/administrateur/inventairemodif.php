<?php session_start();
include('../../includes/header.php');
include('menu.php');
include "../../includes/connexion.php";
$sql = 'SELECT * FROM famille ORDER BY famille ASC';
$req_famille = $bdd->query($sql);
$sql2 = 'SELECT * FROM marque ORDER BY marque ASC';
$req_marque = $bdd->query($sql2);
$sql3 = "SELECT DISTINCT active_directory_lastname, active_directory_firstname, active_directory_uid_number from tbl_import_active_directory ORDER BY active_directory_lastname ASC ";
$req_loc = $bdd2->query($sql3);	
$sql4 = "SELECT MAX(ean) FROM biens";
$req_max_ean = $bdd->query($sql4);	
while ($donnees = $req_max_ean->fetch()){
	$max_ean = str_pad($donnees['MAX(ean)']+ 1, 6, "0", STR_PAD_LEFT) ;
}

?>	
	
					<table>
						<?php include "../../theader.php"; ?>
						<tbody>
							<form action="insertion.php" method='post'>
								<tr>
									<td class='column1'><input type="text" name="ean" maxlength="6" size="7" <?php echo "value = '".$max_ean."'" ; ?>></td>
									<td class='column2'><select name="localisation" style="width:160px;" >
									<?php while ($donnees = $req_loc->fetch()) {
										echo "<option value='".$donnees["active_directory_uid_number"]."'>".$donnees["active_directory_lastname"]." ".$donnees["active_directory_firstname"]."</option>\n";
									}
									?>	
									</select></td>
								
									<td class='column3'><select name="famille" style="width:100px;">
									<?php while ($donnees = $req_famille->fetch()) {
										echo "<option value='".$donnees["famille"]."'>".$donnees["famille"]."</option>\n";
									}
									?>	
									</select></td>
									<td class='column4'><select name="marque" style="width:90px;">
									<?php while ($donnees = $req_marque->fetch()) {
										echo "<option value='".$donnees["marque"].">".$donnees["marque"]."</option>\n";
									}
									?>	
									</select></td>
									<td class='column5'><input type="text" name="modele" maxlength="250" size="10"></td>
									<td class='column6'><input type="text" name="numdeserie" maxlength="15" size="15"></td>
									<td class='column5'><input type="text" name="numfacture" maxlength="250" size="10"></td>
									<td class='column6'><input type="text" name="montant" maxlength="10" size="6"></td>
									<td class='column9'><button type='submit' name='btnEnvoiForm' title='Ok !'><img src='<?php echo BASESITE;?>images/ok.png' style='width:30px; height:30px' alt='' /></button></td>
								</tr>
							</form>
						</thead>
					</table>
<?php include "../../includes/footer.php"; ?>