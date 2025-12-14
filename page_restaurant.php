<?php 
require_once ('connectbdd.php'); 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Restaurant Privé</title>
    <link rel="stylesheet" href="style/styles.css" />
</head>
<body>
<?php
// 2. Récupérer l'ID de manière sécurisée depuis l'URL
$resto_id = filter_input(INPUT_GET, 'id_resto', FILTER_VALIDATE_INT);

// 3. Vérifier si l'ID est valide
if ($resto_id) {
    
    // Requête pour la table des restaurants privés
    $sql = "SELECT * FROM restaurant_1 WHERE id_resto = ?"; 
    $stmt = $pdo->prepare($sql);

    $stmt->execute([$resto_id]); 
    $restaurant_detail = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // 4. Afficher les détails du restaurant
    if ($restaurant_detail) {
        
        echo "<h1>" . htmlspecialchars($restaurant_detail['name']) . "</h1>";
        echo "<p>Type de cuisine : " . htmlspecialchars($restaurant_detail['Type_Cuisine']) . "</p>";
        echo "<p>Adresse : " . htmlspecialchars($restaurant_detail['Adresse']) . "</p>";
        echo "<p>Note : " . htmlspecialchars($restaurant_detail['Note']) . "/5</p>";

    } else {
        echo "<h1>Erreur</h1>";
        echo "<p>Restaurant privé avec l'ID $resto_id non trouvé. (Vérifiez si l'ID existe en base de données)</p>";
    }
} else {
    echo "<h1>Erreur</h1>";
    echo "<p>ID de restaurant invalide ou manquant dans l'URL.</p>";
}
?>
</body>
</html>