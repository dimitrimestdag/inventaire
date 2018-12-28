<?php
include "../../includes/connexion.php";
session_start();
include('../../includes/header.php');
include('menu.php');
$num = $_GET['num'];

echo $num ;
?>

<form method="post" enctype="multipart/form-data">
 <div>
   <input type="file" id="file" name="file" multiple>
 </div>
 <div>
   <button>Envoyer</button>
 </div>
</form>