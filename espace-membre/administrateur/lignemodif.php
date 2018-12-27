<tbody>
<script>
function enableSample() {
document.getElementById('sample').disabled=false;
}
</script>
<?php
    include "../../includes/connexion.php";
    session_start();
    include('../../includes/header.php');
    include('menu.php');
    $ean = $_GET['ean'];
    $sql_bienean = 'SELECT * FROM biens WHERE ean = '.$ean;
    $sql_familleASC = 'SELECT * FROM famille ORDER BY famille ASC';
    $sql_marqueASC = 'SELECT * FROM marque ORDER BY marque ASC';
    $sql_adASC = "SELECT DISTINCT active_directory_lastname, active_directory_firstname, active_directory_uid_number from tbl_import_active_directory ORDER BY active_directory_lastname ASC ";
    $req_marque = $bdd->query($sql_marqueASC);
    $req_famille = $bdd->query($sql_familleASC);
    $req_user = $bdd->query($sql_bienean);
    $req_loc2 = $bdd2->query($sql_adASC);
    echo "<table>\n";
    include "../../theader.php";
    while ($donnees = $req_user->fetch()) {
        $loc = $donnees["localisation"];
        $sql2 = "SELECT DISTINCT nom, prenom from localisation  WHERE localisation LIKE '$loc' ";
        $req_loc = $bdd->query($sql2);
        $donnees2 = $req_loc->fetch();
        echo "<tbody>\n";
        echo "<form action='insertion' method='post' onsubmit='enableSample();'>\n";
        echo "<tr>\n";
        echo "<td class='column1'><input type='text' name='edit_ean' value='".$donnees["ean"]."' readonly='readonly' size='7'/></td>\n";
        echo "<td class='column2'>";
        if ($_SESSION['id'] == 4) {
            while ($donnees3 = $req_loc2->fetch()) {
                if ($donnees["localisation"] == $donnees3["active_directory_uid_number"]) {
                    echo "<input type='text' name='edit_localisation' size='10' value='".$donnees["localisation"]."' readonly='readonly' size='7'/></td>\n";
                }
            }
        } else {
            echo "<select name='edit_localisation' style='width:150px;'>";
            while ($donnees3 = $req_loc2->fetch()) {
                if ($donnees["localisation"] == $donnees3["active_directory_uid_number"]) {
                    echo '<option value="'.$donnees["localisation"].'" selected >'.$donnees3["active_directory_lastname"].' '.$donnees3["active_directory_firstname"].'</option>';
                } else {
                    echo '<option value="'.$donnees["localisation"].'">'.$donnees3["active_directory_lastname"].' '.$donnees3["active_directory_firstname"].'</option>';
                }
            }
            echo "</select></td>\n";
        }
        echo "<td class='column3'>";
        if ($_SESSION['id'] == 4) {
            while ($donnees3 = $req_famille->fetch()) {
                if ($donnees["famille"] == $donnees3["famille"]) {
                    echo "<input type='text' name='edit_famille' size='10' value='".$donnees["famille"]."' readonly='readonly' /></td>\n";
                }
            }
        } else {
            echo "<select name='edit_famille' style='width:80px;'>";
            while ($donnees3 = $req_famille->fetch()) {
                if ($donnees["famille"] == $donnees3["famille"]) {
                    echo '<option value="'.$donnees3["famille"].'" selected >'.$donnees3["famille"].'</option>';
                } else {
                    echo '<option value="'.$donnees3["famille"].'">'.$donnees3["famille"].'</option>';
                }
            }
            echo "</select></td>\n";
        }
        echo "<td class='column4'>";
        if ($_SESSION['id'] == 4) {
            while ($donnees3 = $req_marque->fetch()) {
                if ($donnees["marque"] == $donnees3["marque"]) {
                    echo "<input type='text' name='edit_marque' size='10' value='".$donnees["marque"]."' readonly='readonly' /></td>\n";
                }
            }
        } else {
            echo "<select name='edit_marque' style='width:70px;'>";
            while ($donnees3 = $req_marque->fetch()) {
                if ($donnees["marque"] == $donnees3["marque"]) {
                    echo '<option value="'.$donnees3["marque"].'" selected >'.$donnees3["marque"].'</option>';
                } else {
                    echo "<option value='".$donnees3["marque"]."'>".$donnees3["marque"]."</option>\n";
                }
            }
            echo "</select></td>\n";
        }
        echo "<td class='column5'><input type='text' name='edit_modele' size='10' value='".$donnees["modele"]."'";
        if ($_SESSION['id'] == 4) {
            echo "readonly='readonly'";
        }
        echo" /></td>\n";
        echo "<td class='column6'><input type='text' name='edit_numdeserie' size='15' value='".$donnees["numdeserie"]."'";
        if ($_SESSION['id'] == 4) {
            echo "readonly='readonly'";
        }
        echo" /></td>\n";
        echo "<td class='column7'><input type='text' name='edit_numfacture' size='15' value='".$donnees["numfacture"]."'/></td>\n";
        echo "<td class='column8'><input type='text' name='edit_montant' size='6' value='".$donnees["montant"]."'/></td>\n";
        echo "<td class='column9'><button type='submit' name='btnEnvoiForm' title='Ok !'><img src='".BASESITE."images/ok.png' style='width:30px; height:30px' alt='' /></button></td>\n";
        
        echo "</tr>\n";
        echo "</form>\n";
        echo "</tbody>\n";
    }
?>
</tbody>
<?php include "../../includes/footer.php"; ?>