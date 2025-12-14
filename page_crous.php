<?php 
// Assurez-vous d'inclure la connexion ici aussi !
require_once ('connectbdd.php'); 

// Assurez-vous que la variable $pdo est définie dans connectbdd.php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Restaurant CROUS</title>
    <link rel="stylesheet" href="style/styles.css" />
</head>
<body>
<?php
// 1. Récupérer l'ID de manière sécurisée (Le paramètre est bien 'id_crous' dans l'index.php corrigé)
$crous_id = filter_input(INPUT_GET, 'id_crous', FILTER_VALIDATE_INT);

// 2. Vérifier si l'ID est valide
if ($crous_id) {
    
    // 3. Préparer la requête
    $sql = "SELECT * FROM crous WHERE id_crous = ?"; 
    $stmt = $pdo->prepare($sql);

    // 4. Exécuter la requête
    $stmt->execute([$crous_id]);

    // 5. Récupérer le résultat
    $crous_detail = $stmt->fetch(PDO::FETCH_ASSOC); 

    // 6. Afficher les détails du CROUS
    if ($crous_detail) {
        
        echo "<h1>Détails pour " . htmlspecialchars($crous_detail['name']) . "</h1>";
        
        // Affichage des autres détails disponibles dans la table crous
        echo "<p>Type : " . htmlspecialchars($crous_detail['type']) . "</p>";
        echo "<p>Adresse : " . htmlspecialchars($crous_detail['adresse']) . "</p>";
        echo "<p>Accessibilité PMR : " . ($crous_detail['PMR'] ? 'Oui' : 'Non') . "</p>";
        echo "<p>Moyens de paiement : " . htmlspecialchars($crous_detail['paiement']) . "</p>";
        

    } else {
        echo "<h1>Erreur</h1>";
        echo "<p>Restaurant CROUS non trouvé.</p>";
    }
} else {
    echo "<h1>Erreur</h1>";
    echo "<p>ID de restaurant invalide ou manquant dans l'URL.</p>";
}
?>
</body>
</html>