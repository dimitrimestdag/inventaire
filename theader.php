						<thead>
							<tr class="table100-head">
								<th class="column1">EAN</th>
								<th class="column2">Localisation</th>
								<th class="column3">Famille</th>
								<th class="column4">Marque</th>
								<th class="column5">Modele</th>
								<th class="column6">Numero de serie</th>
								<th class="column5">Num Facture</th>
								<th class="column6">Montant</th>
								<?php
								if (isset($_SESSION['id']) ) {
								echo '<th class="column7"></th>';
								}?>
							</tr>
						</thead>