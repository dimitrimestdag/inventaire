<?php include('header.php'); ?>

<body>
	<div class="limiter">
		<div class="container-table100">
			<div class="wrap-table100">
				<div class="table100">
					<table>
						<?php include('theader.php');
						include('tbody.php');
						?>
					</table>
					<table>
						<thead>
							<tr class="table100-head">
								<th class="column1">
									<?php echo barre_navigation($nb_total, $nb_affichage_par_page, $_GET['debut'], 3); ?>
								</th>
							</tr>
						</thead>
					</table>

<div class="row justify-content-center">
    <div class="col-12 col-md-10 col-lg-8">
        <form class="card card-sm" action ="resultat" method="post">
            <div class="card-body row no-gutters align-items-center">
               
                <!--end of col-->
                <div class="col">
                    <input class="form-control form-control-lg form-control-borderless" type="search" placeholder="Rechercher un nom ou autre" name='search'>
                </div>
                <!--end of col-->
                <div class="col-auto">
                    <button class="btn btn-lg btn-success" type="submit">Rechercher</button>
                </div>
                <!--end of col-->
            </div>
        </form>
    </div>
    <!--end of col-->
</div>

				</div>
			</div>
			<a href="connexion"><img src="<?php echo BASESITE; ?>images/login.png" class="boutonindex" title="Connexion"></a>
		</div>
	</div>




	<!--===============================================================================================-->
	<script src="<?php echo BASESITE; ?>vendor/jquery/jquery-3.2.1.min.js"></script>
	<!--===============================================================================================-->
	<script src="<?php echo BASESITE; ?>vendor/bootstrap/js/popper.js"></script>
	<script src="<?php echo BASESITE; ?>vendor/bootstrap/js/bootstrap.min.js"></script>
	<!--===============================================================================================-->
	<script src="<?php echo BASESITE; ?>vendor/select2/select2.min.js"></script>
	<!--===============================================================================================-->
	<script src="<?php echo BASESITE; ?>js/main.js"></script>

</body>

</html>