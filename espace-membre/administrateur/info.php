<?php session_start();
include('../../includes/header.php');
include('menu.php');
include "../../includes/connexion.php";
?>
<table>
    <thead>
        <tr class="table100-head">
            <th class="column1">Info Perso</th>
            <th class="column2"></th>
            <th class="column3"></th>
            <th class="column4"></th>
            <th class="column5"></th>
            <th class="column6"></th>
        </tr>
    </thead>
</table>

<div class="row justify-content-center">
    <div class="col-12 col-md-10 col-lg-8">
        <form class="card card-sm" action ="" method="post">
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
<?php
if (isset($_GET['search'])) {
    $chainesearch = addslashes($_GET['search']);
} elseif (isset($_POST['search'])) {
    $chainesearch = addslashes($_POST['search']);
} else {
    $chainesearch = "";
}
    if ($chainesearch <> "") {
        include "../../includes/connexion.php";
        $sql_searchAD = "SELECT * from tbl_import_active_directory WHERE active_directory_lastname LIKE '". $chainesearch
            ."%' OR active_directory_firstname LIKE '". $chainesearch
            ."%' OR active_directory_id_sage_compagny LIKE '". $chainesearch
            ."%' OR active_directory_id_sage_employee LIKE '". $chainesearch
            ."%' OR active_directory_id_sage_manager LIKE '". $chainesearch
            ."%' OR active_directory_uid_number LIKE '". $chainesearch ."%'";
        $resultat = $bdd2->query($sql_searchAD) or die(print_r($bdd->errorInfo()));
        echo '<table>';
        echo '<thead>';
        echo '<tr class="table100-head">';
        echo '<th class="column1">Nom</th>';
        echo '<th class="column2">Prenom</th>';
        echo '<th class="column3">idsagecompany</th>';
        echo '<th class="column4">idsageemployee</th>';
        echo '<th class="column5">idsagemanager</th>';
        echo '<th class="column6">localisation</th>';
        if ($_SESSION['id'] == 2 || $_SESSION['id'] == 3) {
            echo '<th class="column7"></th>';
        }
        echo '</tr>';
        echo '</thead>';
       
        while ($donnees = $resultat->fetch()) {
            echo "<tr>";
            
            echo "<td class='column1'>".$donnees["active_directory_lastname"]."</td>\n";
           
            echo "<td class='column2'>".$donnees["active_directory_firstname"]."</td>\n";
           
            echo "<td class='column3'>".$donnees["active_directory_id_sage_compagny"]."</td>\n";
           
            echo "<td class='column4'>".$donnees["active_directory_id_sage_employee"]."</td>\n";
            
            echo "<td class='column5'>".$donnees["active_directory_id_sage_manager"]."</td>\n";
            
            echo "<td class='column6'>".$donnees["active_directory_uid_number"]."</td>\n";
            if ($_SESSION['id'] == 2 || $_SESSION['id'] == 3) {
                echo "<td class='column7'><a href='".BASESITE."admin/sortie-".$donnees["active_directory_uid_number"]."' onClick=\"return confirm('Êtes-vous sûr de vouloir supprimer touts les biens de cette personne ?')\"><img src='".BASESITE."images/sortie-user.png' height='35' width='35'></a>";
            }
           
            echo "</tr>";
        }
        echo '</table>';
    }
include "../../includes/footer.php";
?>