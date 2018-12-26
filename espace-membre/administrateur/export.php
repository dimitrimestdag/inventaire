<?php

include('../../includes/connexion.php');

header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=biens.csv');

//SQL Query for Data
$sql = "SELECT * FROM biens;";
//Prepare Query, Bind Parameters, Excute Query
$STH = $bdd->prepare($sql);
$STH->execute();

//Export to .CSV
$fp = fopen('php://output', 'w');

// first set
$first_row = $STH->fetch(PDO::FETCH_ASSOC);
$headers = array_keys($first_row);
fputcsv($fp, $headers); // put the headers
fputcsv($fp, array_values($first_row)); // put the first row

while ($row = $STH->fetch(PDO::FETCH_NUM)) {
    fputcsv($fp, $row); // push the rest
}
fclose($fp);
?>