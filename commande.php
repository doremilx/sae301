<?php
require_once 'connectbdd.php';
session_start();

setlocale(LC_TIME, 'fr_FR.utf8', 'fra');
$date_du_jour = date('Y-m-d');
$date_affichage = date('d/m/Y');
$id_crous_url = filter_input(INPUT_GET, 'id_crous', FILTER_VALIDATE_INT);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commande Crous</title>
</head>
<body>
    
    <h1>Réserver mon repas</h1>
    <h1>Formule Crous</h1>
    <h2>Comprend 1 Plat Principal, 1 Accompagnement, et 1 Dessert</h2>
    <form action="" method="POST">
        <select name="id_plat" id="select_plat">
            <option value="">Plat Principal</option>
<?php
$stmtPlat = $pdo->query("SELECT * FROM produits_crous WHERE categorie = 'Sandwichs' OR categorie = 'Salades'");
                $produit = $stmtPlat->fetchAll(PDO::FETCH_ASSOC);
                foreach ($produit as $row): 
                    echo "<option value='" . htmlspecialchars($row['id_produit']) . "'>" . htmlspecialchars($row['nom_produit']) . "</option>";
                endforeach;
?>
        </select>
        <br><br>
                <select name="id_accompagnement" id="select_acc">
            <option value="">Accompagnement</option>
<?php
$stmtAccompagnement = $pdo->query("SELECT * FROM produits_crous WHERE categorie = 'Accompagnement'");
                $produit = $stmtAccompagnement->fetchAll(PDO::FETCH_ASSOC);
                foreach ($produit as $row): 
                    echo "<option value='" . htmlspecialchars($row['id_produit']) . "'>" . htmlspecialchars($row['nom_produit']) . "</option>";
                endforeach;
?>
        </select>

        <br><br>
                <select name="id_dessert" id="select_dessert">
            <option value="">Dessert</option>
<?php
$stmtDessert = $pdo->query("SELECT * FROM produits_crous WHERE categorie = 'Pâtisseries' OR categorie = 'Desserts'");
                $produit = $stmtDessert->fetchAll(PDO::FETCH_ASSOC);
                foreach ($produit as $row): 
                    echo "<option value='" . htmlspecialchars($row['id_produit']) . "'>" . htmlspecialchars($row['nom_produit']) . "</option>";
                endforeach;
?>
        </select>
<br><br>

<!-- Système avec la date du jour  -->
<label for="date_repas">Date du repas :</label>
<input type="date" 
       id="date_repas" 
       name="date_repas" 
       value="<?php echo date('Y-m-d'); ?>" 
       min="<?php echo date('Y-m-d'); ?>">


<!-- Système du lieu par defaut etc -->
<label for="lieu">Lieu de retrait :</label>
<select name="id_crous" id="lieu" required>
    <?php

    $stmtRestos = $pdo->query("SELECT * FROM crous");
    $restos = $stmtRestos->fetchAll(PDO::FETCH_ASSOC);
    foreach ($restos as $row) {

        $selected = ($row['id_crous'] == $id_crous_url) ? "selected" : "";
        
        echo "<option value='" . $row['id_crous'] . "' " . $selected . ">";
        echo htmlspecialchars($row['name']);
        echo "</option>";
    }
    ?>
</select>
<div id="ma-commande">
    <h3>Ma commande</h3>
    <div id="recap-formule">
        <p><strong>Formule :</strong> <span id="display-formule">Aucune</span></p>
        <ul>
            <li>Plat : <span id="res-plat">-</span></li>
            <li>Accompagnement : <span id="res-acc">-</span></li>
            <li>Dessert : <span id="res-dessert">-</span></li>
        </ul>
    </div>
    <hr>
    <p>Total à payer : <span id="total-prix">0.00</span>€</p>
</div>
    </form>

    <script>

const selectPlat = document.querySelector('select[name="id_plat"]');
const selectAcc = document.querySelector('select[name="id_accompagnement"]');
const selectDessert = document.querySelector('select[name="id_dessert"]');


const resPlat = document.getElementById('res-plat');
const resAcc = document.getElementById('res-acc');
const resDessert = document.getElementById('res-dessert');


function updateRecap() {

    resPlat.textContent = selectPlat.options[selectPlat.selectedIndex].text;
    resAcc.textContent = selectAcc.options[selectAcc.selectedIndex].text;
    resDessert.textContent = selectDessert.options[selectDessert.selectedIndex].text;
    

    if(selectPlat.value && selectAcc.value && selectDessert.value) {
        document.getElementById('display-formule').textContent = "Formule CROUS complète";
        document.getElementById('total-prix').textContent = "3.30"; 
    }
}


selectPlat.addEventListener('change', updateRecap);
selectAcc.addEventListener('change', updateRecap);
selectDessert.addEventListener('change', updateRecap);
</script>
</body>
</html>