<?php require_once ('connectbdd.php'); ?>
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
       
        echo "<img src='" . htmlspecialchars($crous_detail['photo']) . "' alt='Image de " . htmlspecialchars($crous_detail['name']) . "' style='max-width:300px;' />";


/* Système des horaires DEBUT*/

// 1. Définir la correspondance Jour (basé sur votre BDD : 0=Lundi, 1=Mardi, etc.)
$jours_semaine = [
    0 => 'Lundi', 
    1 => 'Mardi', 
    2 => 'Mercredi', 
    3 => 'Jeudi', 
    4 => 'Vendredi',
    5 => 'Samedi', // Non utilisé dans votre échantillon, mais bonne pratique
    6 => 'Dimanche' // Non utilisé dans votre échantillon
];

// 2. Requête simplifiée pour récupérer tous les horaires de ce CROUS
$sql2 = "SELECT * FROM horaires_crous WHERE crous_id = ? ORDER BY jour ASC"; 
$stmt2 = $pdo->prepare($sql2);

// 3. Exécuter la requête avec le bon statement
$stmt2->execute([$crous_id]);
$horaires = $stmt2->fetchAll(PDO::FETCH_ASSOC);

echo "<h2>Horaires d'ouverture :</h2>";
echo "<ul>";

if ($horaires) {
    // 4. Boucler sur tous les résultats (un résultat = un jour)
    foreach ($horaires as $horaire) {

        $heure_ouverture = substr($horaire['ouvert_a'], 0, 5); 
        $heure_fermeture = substr($horaire['ferme_a'], 0, 5);
        // Récupérer le nom du jour, sinon affiche 'Jour Inconnu'
        $nom_jour = $jours_semaine[$horaire['jour']] ?? 'Jour Inconnu';
        
        echo "<li>$nom_jour : " . htmlspecialchars($heure_ouverture) . " - " . htmlspecialchars($heure_fermeture) . "</li>";
    }
} else {
    echo "<li>Aucun horaire trouvé pour ce restaurant.</li>";
}

echo "</ul>";

/* Système horaires FIN */


/* Système de chat live, je vais pleurer -rémi */

echo '<div id="chat-container">';
echo '<h2>Chat Live</h2>';
echo '<div id="chat-messages">';

// Charger les messages existants depuis la base de données
$sql3 = "SELECT * FROM messages WHERE crous_id = ? ORDER BY timestamp ASC";
$stmt3 = $pdo->prepare($sql3);
$stmt3->execute([$crous_id]);
$messages = $stmt3->fetchAll(PDO::FETCH_ASSOC);
foreach ($messages as $message) {
    echo '<div class="chat-message">';
    echo '<strong>' . htmlspecialchars($message['username']) . ':</strong> ';
    echo '<span>' . htmlspecialchars($message['message']) . '</span>';
    echo '</div>';
}
echo '</div>';
echo '<input type="text" id="user-input" placeholder="Tapez votre message..." />';
echo '<button onclick="sendMessage()">Envoyer</button>';
echo '</div>';

/* FIN Système de chat live FIN, je vais pleurer -rémi */




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