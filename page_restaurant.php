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
<?php include 'header.php'; ?>
<?php
// 2. Récupérer l'ID de manière sécurisée depuis l'URL
$resto_id = filter_input(INPUT_GET, 'id_resto', FILTER_VALIDATE_INT);

// 3. Vérifier si l'ID est valide
if ($resto_id) {
    
    // Requête pour la table des restaurants privés
    $sql = "SELECT * FROM restaurant WHERE id_resto = ?"; 
    $stmt = $pdo->prepare($sql);

    $stmt->execute([$resto_id]); 
    $restaurant_detail = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // 4. Afficher les détails du restaurant
    if ($restaurant_detail) {
        
        echo "<h1>" . htmlspecialchars($restaurant_detail['name']) . "</h1>";
        echo "<p>Type de cuisine : " . htmlspecialchars($restaurant_detail['Type_Cuisine']) . "</p>";
        echo "<p>Adresse : " . htmlspecialchars($restaurant_detail['Adresse']) . "</p>";
        echo "<p>Note : " . htmlspecialchars($restaurant_detail['Note']) . "/5</p>";
        echo "<p>Numéro de téléphone : ". $restaurant_detail['Telephone'] . "</p>";
        echo "<p>Gamme de prix: ". $restaurant_detail['Gamme_Prix'] . " </p>";
        echo "<p>Accessibilité PMR : " . ($restaurant_detail['PMR'] ? 'Oui' : 'Non') . "</p>";
         echo "<p>Sur place : " . ($restaurant_detail['Sur_Place'] ? 'Oui' : 'Non') . "</p>";
          echo "<p>A emporter : " . ($restaurant_detail['A_Emporter'] ? 'Oui' : 'Non') . "</p>";
           echo "<p>Livraison : " . ($restaurant_detail['Livraison'] ? 'Oui' : 'Non') . "</p>";
        echo "<p> Site Web :</p> <a href='" . htmlspecialchars($restaurant_detail['Site_Web']) . "' target='_blank'> ". $restaurant_detail['Site_Web'] . " </a> <br><br>"; ;

        
        echo "<img src='" . htmlspecialchars($restaurant_detail['photo_facade']) . "' alt='Image de " . htmlspecialchars($restaurant_detail['name']) . "' style='max-width:300px;' />";

        if (!empty($restaurant_detail['photo_menu'])) {
        echo "<img src='" . htmlspecialchars($restaurant_detail['photo_menu']) . "' alt='Menu de " . htmlspecialchars($restaurant_detail['name']) . "' style='max-width:300px;' />";}



/* Système de chat live, je vais pleurer -rémi */



session_start(); 

echo '<div id="chat-container" style="margin-top: 30px; border: 1px solid #ccc; padding: 15px;">';
    echo '<h2>Chat Live</h2>';
    
    // Affichage des messages existants
    echo '<div id="display-messages" style="height: 300px; overflow-y: scroll; margin-bottom: 10px; background: #f9f9f9; padding: 10px;">';
    
    // Jointure pour avoir le prénom de l'auteur du message
    $sql3 = "SELECT m.*, u.* FROM message_resto m 
             JOIN users u ON m.user_id = u.id 
             WHERE m.resto_id = ? 
             ORDER BY m.date ASC";
    $stmt3 = $pdo->prepare($sql3);
    $stmt3->execute([$resto_id]);
    $messages = $stmt3->fetchAll(PDO::FETCH_ASSOC);

    foreach ($messages as $message) {
        echo '<div class="chat-message" style="margin-bottom: 10px;">';
        echo '<strong>' . htmlspecialchars($message['firstname']) . ' ' . htmlspecialchars($message['lastname']). ' :</strong> ';
        echo '<span>' . htmlspecialchars($message['contenu_resto']) . '</span>';
        echo '<small style="display:block; color:gray; font-size:0.8em;">' . $message['date'] . '</small>';
        echo '</div>';
    }
    echo '</div>'; 

    // Formulaire d'envoi
    if (isset($_SESSION['id'])) {
        echo '<form action="chatlive_resto.php" method="post">';
            // Champ caché pour envoyer l'ID du CROUS
            echo '<input type="hidden" name="id_resto" value="' . $resto_id . '" />';
            echo '<input type="text" name="message" placeholder="Tapez votre message..." required style="width: 80%;" />';
            echo '<button type="submit">Envoyer</button>';
        echo '</form>';
    } else {
        echo '<p><i>Veuillez vous <a href="profile.php">connecter</a> pour participer au chat.</i></p>';
    }
echo '</div>';

/* FIN Système de chat live FIN, je vais pleurer -rémi */






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