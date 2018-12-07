<?php
if (!isset($_SESSION)) {
    session_start();
}
include('function.php');
ProtectEspace::administrateur($_SESSION['id']/*, $_SESSION['captcha']*/, $_SESSION['jeton'], $_SESSION['niveau']);
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
	<title>Inventaire</title>
	<meta charset="UTF-8">
	<meta name="author" content="Dimitri Mestdag" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--===============================================================================================-->
	<link rel="icon" type="image/png" href="<?php echo BASESITE; ?>images/icons/favicon.ico" />
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo BASESITE; ?>vendor/bootstrap/css/bootstrap.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo BASESITE; ?>fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo BASESITE; ?>vendor/animate/animate.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo BASESITE; ?>vendor/css-hamburgers/hamburgers.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo BASESITE; ?>vendor/select2/select2.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo BASESITE; ?>vendor/perfect-scrollbar/perfect-scrollbar.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo BASESITE; ?>css/util.css">
	<link rel="stylesheet" type="text/css" href="<?php echo BASESITE; ?>css/main.css">
	<link rel="stylesheet" type="text/css" href="<?php echo BASESITE; ?>css/mainlogin.css">
	<!--===============================================================================================-->

</head>
	<body>
	<div class="limiter">
		<div class="container-table100">
			<div class="wrap-table100">
				<div class="table100">