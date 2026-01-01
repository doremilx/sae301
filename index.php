<?php require_once('connectbdd.php'); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crousty Map - Explorer</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="style/style_accueil.css" />
</head>

<body>

    <?php include 'header.php'; ?>

    <div class="main-wrapper">
        <aside class="sidebar">
            <h1>Explorer</h1>

            <div class='filtreButtons'>
                <button class="filtre-btn active" data-filter="all">Tri automatique</button>
                <button class="filtre-btn" data-filter="crous">Resto Crous</button>
                <button class="filtre-btn" data-filter="ouvert">Ouvert</button>
                <button class="filtre-btn" data-filter="pmr">Accès PMR</button>
                <button class="filtre-btn" data-filter="favoris">Mes favoris</button>
            </div>

            <div class='filtreCartes'>
                <?php
                // On récupère tout pour générer la liste
                $stmtC = $pdo->query("SELECT * FROM crous");
                $crous = $stmtC->fetchAll(PDO::FETCH_ASSOC);
                foreach ($crous as $row): ?>
                    <a href="page_crous.php?id_crous=<?= $row['id_crous'] ?>"
                        class="restaurant-carte"
                        data-type="crous"
                        data-pmr="<?= $row['PMR'] ?>"
                        data-marker-id="crous_<?= $row['id_crous'] ?>">
                        <div class='CarteTest'>
                            <div class='carteImg' style="background-image: url('<?php echo $row['photo']; ?>');" >
                            </div>
                            <div class='Info'>
                                <h2><?= htmlspecialchars($row['name']) ?></h2>
                                <p><?= htmlspecialchars($row['adresse']) ?></p>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>

                <?php
                $stmtR = $pdo->query("SELECT * FROM restaurant");
                $restos = $stmtR->fetchAll(PDO::FETCH_ASSOC);
                foreach ($restos as $row): ?>
                    <a href="page_restaurant.php?id_resto=<?= $row['id_resto'] ?>"
                        class="restaurant-carte"
                        data-type="prive"
                        data-pmr="<?= $row['PMR'] ?>"
                        data-marker-id="prive_<?= $row['id_resto'] ?>">
                        <div class='CarteTest'>
                            <div class='carteImg' style="background-image: url('<?php echo $row['photo_facade']; ?>');" ></div>
                            <div class='Info'>
                                <h2><?= htmlspecialchars($row['name']) ?></h2>
                                <p><?= htmlspecialchars($row['Adresse']) ?></p>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
            <div class="footer-sidebar">
                <a href="#">Mentions légales</a>
            </div>
        </aside>

        <div id="map"></div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="script.js"></script>
</body>

</html>