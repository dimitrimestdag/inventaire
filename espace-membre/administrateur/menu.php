<?php if ($_SESSION['id'] == 2) {
echo '<table>';
echo '	<tr>';
echo '		<td width="70%" valign="top">';
echo '			<br  />';
echo 'Bonjour '.Membre::info($_SESSION['id'], 'nom').' '.Membre::info($_SESSION['id'], 'prenom').', '.ProtectEspace::compteJeton($_SESSION['id']);
echo '			<br />';
echo '		</td>';
echo '	</tr>';
echo '</table>';
}?>