<?php 
// Assurez-vous d'inclure la connexion ici aussi !
require_once ('connectbdd.php'); 

// 1. Récupérer l'ID (maintenant 'id_crous' fonctionne car le lien a été corrigé)
$resto_id = filter_input(INPUT_GET, 'id_crous', FILTER_VALIDATE_INT);

// 2. Vérifier si l'ID est valide
if ($resto_id) {
    // 3. Préparer la requête (Assurez-vous que la colonne est 'id_crous' ou 'id')
    $sql = "SELECT * FROM crous WHERE id_crous = ?"; 
    $stmt = $pdo->prepare($sql);

    // 4. Exécuter la requête
    // ❗ Le point-virgule est nécessaire ici !
    $stmt->execute([$resto_id]); 

    // 5. Récupérer le résultat
    $crous = $stmt->fetch(PDO::FETCH_ASSOC); 

    // 6. Afficher
    if ($crous) {
        echo "<h1>Détails pour " . $crous['Nom'] . "</h1>";
        // Vous pouvez ajouter le reste de l'affichage ici
    } else {
        echo "Restaurant non trouvé.";
    }
} else {
    echo "ID de restaurant invalide.";
}
?>