<?php 
require_once ('connectbdd.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carte restaurants</title>

    <link rel="stylesheet" href="style/styles.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        #map { height: 100vh; }
    </style>
</head>
<body>
<nav>
    <div class="right">
        <ul>
            <li><a href="">Explorer</a></li>
            <li> <a href="">Mon profil</a></li>
            <li><a href="" class='izlyButton'>Mon izly</a></li> 
        </ul>
    </div>
</nav>

<div class='filtre'>
    <h1>Explorer</h1>
    <div class='filtreButtons'></div>
    <div class='filtreCartes'>

       

        <?php
require_once ('connectbdd.php'); 

$sql = "SELECT * FROM crous";
$stmt = $pdo->query($sql);
$crous = $stmt->fetchAll(PDO::FETCH_ASSOC); ?>
<!-- Coucou Timothy, je te mens pas je me suis perdu car j'ai rajouté les balises a apèrs, donc niveau positionnement tout ca ca marche pas trop, mais quand y'avait pas de a, ca marchait très bien au niveau des positionnements haha  -->
 <?php foreach ($crous as $carteCrous): ?> 
    <a href="page_crous.php?id_crous=<?php echo $carteCrous['id_crous'];?>">
   <div class='CarteTest'>
            <div class='carteImg'></div>
            <div class='Info'>
            <h2> <?php echo $carteCrous['Nom'] ?> </h2>
            <div class='petiteInfo'>
                <p>ouvert - ferme à 23h30</p>
                <p>beaucoup de monde</p>
                <p> <?php  echo $carteCrous['adresse']  ?> </p>
            </div>
            </div>
        </div>
        </a>
<?php endforeach; ?>

<?php
$sql2 = "SELECT * FROM restaurant_1";
$stmt = $pdo->query($sql2);
$restaurant = $stmt->fetchAll(PDO::FETCH_ASSOC); ?>


<?php foreach ($restaurant as $carteRestaurant): ?> 
    <a href="page_restaurant.php?id_restaurant=<?php echo $carteRestaurant['Position'];?>">
   <div class='CarteTest'>
            <div class='carteImg'></div>
            <div class='Info'>
            <h2> <?php echo $carteRestaurant['Nom_Restaurant'] ?> </h2>
            <div class='petiteInfo'>
                <p>ouvert - ferme à 23h30</p>
                <p>beaucoup de monde</p>
                <p> <?php  echo $carteRestaurant['Adresse']  ?> </p>
            </div>
            </div>
        </div>
        </a>
<?php endforeach; ?>


    </div>
</div>

<div id="map"></div>



<script src="script.js"> </script>

</body>
</html>