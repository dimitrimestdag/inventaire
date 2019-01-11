<?php
include "../../includes/connexion.php";
session_start();
include('../../includes/header.php');
$num_facture = $_GET['num'];
$sql_extensionfichier= "SELECT extension FROM upload WHERE nom LIKE 'facture-".$num_facture."'";
$req_extension = $bdd->query($sql_extensionfichier);
while ($donnees = $req_extension->fetch()) {
    $ext = $donnees['extension'];
}
$full_path = 'factures/facture-'.$num_facture.'.'.$ext; // chemin système (local) vers le fichier
$file_name = basename($full_path);
ini_set('zlib.output_compression', 0);
$date = gmdate(DATE_RFC1123);
switch($ext) {
    case "gz": $type = "multipart/x-gzip"; break;
    case "gzip": $type = "multipart/x-gzip"; break;
    case "tgz": $type = "multipart/x-gzip"; break;
    case "zip": $type = "multipart/x-zip"; break;
    case "doc": $type = "application/msword"; break;
    case "docx": $type = "application/msword"; break;
    case "xls": $type = "application/vnd.ms-excel"; break;
    case "xlsx": $type = "application/vnd.ms-excel"; break;
    case "pdf": $type = "application/pdf"; break;
    case "xml": $type = "application/xml"; break;
    case "png": $type = "image/png"; break;
    case "gif": $type = "image/gif"; break;
    case "jpg": $type = "image/jpeg"; break;
    case "jpeg": $type = "image/jpeg"; break;
    case "jpe": $type = "image/jpeg"; break;
    case "rtx": $type = "text/richtext"; break;
    case "rtf": $type = "text/richtext"; break;
    case "txt": $type = "text/plain"; break;
    case "htm": $type = "text/html"; break;
    case "html": $type = "text/html"; break;
    case "csv": $type = "text/csv"; break;
    case "odb": $type = "application/vnd.oasis.opendocument.database"; break;
    case "odc": $type = "application/vnd.oasis.opendocument.chart"; break;
    case "odf": $type = "application/vnd.oasis.opendocument.formula"; break;
    case "odg": $type = "application/vnd.oasis.opendocument.graphics"; break;
    case "odi": $type = "application/vnd.oasis.opendocument.image"; break;
    case "odm": $type = "application/vnd.oasis.opendocument.text-master"; break;
    case "odp": $type = "application/vnd.oasis.opendocument.presentation"; break;
    case "ods": $type = "application/vnd.oasis.opendocument.spreadsheet"; break;
    case "odt": $type = "application/vnd.oasis.opendocument.text"; break;
    case "otg": $type = "application/vnd.oasis.opendocument.graphics-template"; break;
    case "oth": $type = "application/vnd.oasis.opendocument.text-web"; break;
    case "otp": $type = "application/vnd.oasis.opendocument.presentation-template"; break;
    case "ots": $type = "application/vnd.oasis.opendocument.spreadsheet-template"; break;
    case "ott": $type = "application/vnd.oasis.opendocument.text-template"; break;

    default: $type = "application/octet-stream"; 
}
header('Content-Disposition: attachment; filename="'.$file_name.'"');
header('Content-Type: application/force-download');
header("Content-Transfer-Encoding: application/octet-stream");
header('Content-Length: '.filesize($full_path));
header("Pragma: no-cache");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0, public");
header('Expires: '.gmdate(DATE_RFC1123, time()+1));
//header('Content-MD5: '.base64_encode(md5_file($full_path)));
//header('Date: '.$date);
//header('Last-Modified: '.gmdate(DATE_RFC1123, filemtime($full_path)));
readfile($full_path);
$sql_log_download= "INSERT INTO `log` (`id`, `date`, `commentaire`) VALUES ('".$_SESSION['id']."', NOW(), 'A telechargé facture-".$num_facture."')";
$bdd->query($sql_log_download);
exit; // nécessaire pour être certain de ne pas envoyer de fichier corrompu

?>