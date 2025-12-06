<?php 
$db = new PDO('mysql:host=localhost;dbname=lin_atable;port=3306;charset=utf8', 'root', '');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carte restaurants</title>

    <link rel="stylesheet" href="styles.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        #map { height: 100vh; }
    </style>
</head>
<body>

<div id="map"></div>



<script src="script.js"> </script>

</body>
</html>
