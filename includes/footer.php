				</div>
			</div>
			<?php 
			//$export = shell_exec('mysqldump --opt biens > fichier_de_sauvegarde.sql');
			if (isset($_SESSION['id'])) {
				echo "<a href='".URLSITE."/administrateur/inventaire.php'><img src='".BASESITE."images/tab.png' class='boutonindex' title='Inventaire' ></a>\n ";
				echo "<a href='".URLSITE."/administrateur/inventairemodif.php'><img src='".BASESITE."images/modifligne.png' class='boutonindex2' title='Ajout' ></a>\n";
				echo "<a href='".URLSITE."/administrateur/export.php'><img src='".BASESITE."images/excel.png' class='boutonindex3' title='Export' ></a>\n";
				echo "<a href='".URLSITE."/administrateur/info.php'><img src='".BASESITE."images/infouser.png' class='boutonindex4' title='Infos Users' ></a>\n";
				echo "<a href='".BASESITE."espace-membre/deconnexion.php'><img src='".BASESITE."images/deco.png' class='boutonindex5' title='Déconnexion' ></a>\n";
				if ($_SESSION['id'] == 2) {
					echo "<a href='".BASESITE."espace-membre/administrateur/change_pass.php'><img src='".BASESITE."images/password.png' class='boutonindex6' title='change Pass' ></a>\n";
				}
			}
			?>
		</div>
	</div>
</body>

<!--===============================================================================================-->	
	<script src="<?php echo BASESITE; ?>vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="<?php echo BASESITE; ?>vendor/bootstrap/js/popper.js"></script>
	<script src="<?php echo BASESITE; ?>vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="<?php echo BASESITE; ?>vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="<?php echo BASESITE; ?>js/main.js"></script>

</html>