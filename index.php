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
    <div class='filtreButtons'>
        <button class="filtre-btn active" data-filter="all">Tri automatique <span class="dropdown-arrow"></span></button>
        
        <button class="filtre-btn" data-filter="crous">Resto Crous</button>
        
        <button class="filtre-btn" data-filter="ouvert">Ouvert en ce moment</button>
        
        <button class="filtre-btn" data-filter="pmr">Accessibilité PMR</button>
        
        <button class="filtre-btn" data-filter="favoris">Mes favoris</button> 
    </div>
    <div class='filtreCartes'>
        
        <?php
        // 2. Récupération des données CROUS
        $sql = "SELECT * FROM crous";
        $stmt = $pdo->query($sql);
        $crous = $stmt->fetchAll(PDO::FETCH_ASSOC); 
        ?>
        
        <?php foreach ($crous as $carteCrous): ?> 
            <a href="page_crous.php?id_crous=<?php echo $carteCrous['id_crous'];?>"
                class="restaurant-carte" 
               data-type="crous" 
               data-pmr="<?php echo $carteCrous['PMR']; ?>"
               data-marker-id="crous_<?php echo $carteCrous['id_crous'];?>"
               >
                <div class='CarteTest'>
                    <div class='carteImg'></div>
                    <div class='Info'>
                        <h2> <?php echo $carteCrous['name'] ?> </h2> <div class='petiteInfo'>

                            <p> <?php  echo $carteCrous['adresse']  ?> </p>
                        </div>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>

        <?php
        // 3. Récupération des données restaurant
        $sql2 = "SELECT * FROM restaurant";
        $stmt = $pdo->query($sql2);
        $restaurant = $stmt->fetchAll(PDO::FETCH_ASSOC); 
        ?>

        <?php foreach ($restaurant as $carteRestaurant): ?> 
            <a href="page_restaurant.php?id_resto=<?php echo $carteRestaurant['id_resto'];?>" class="restaurant-carte" 
               data-type="prive" 
               data-pmr="<?php echo $carteRestaurant['PMR']; ?>"
               data-marker-id="prive_<?php echo $carteRestaurant['id_resto'];?>">


                <div class='CarteTest'>
                    <div class='carteImg'></div>
                    <div class='Info'>
                        <h2> <?php echo $carteRestaurant['name'] ?> </h2> <div class='petiteInfo'>

                            <p> <?php  echo $carteRestaurant['Adresse']  ?> </p>
                        </div>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>

    </div>
    <a href="">Mentions légales</a>
</div>

<div id="map"></div>

<script src="script.js"> </script>

</body>
</html>