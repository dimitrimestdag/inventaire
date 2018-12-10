<?php session_start();
include('../../includes/header.php');
include('menu.php');
?>	
	
					<table>
						<?php include('../../theader.php');
							include('../../tbody.php'); ?>	
						
					</table>
					<table>
						<thead>
							<tr class="table100-head">
								<th class="column1"><?php echo barre_navigation($nb_total, $nb_affichage_par_page, $_GET['debut'], 3); ?></th>
							</tr>
						</thead>
					</table>
					<div class="row justify-content-center">
						<div class="col-12 col-md-10 col-lg-8">
							<form class="card card-sm" action ="../resultats" method="post">
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
	
<?php include "../../includes/footer.php"; ?>