<?php
session_start();
require_once('connectbdd.php'); // Assure-toi que c'est le bon nom de fichier

// 1. Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    die("Vous devez être connecté pour envoyer un message.");
}

// 2. Récupérer les données du formulaire
$message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_SPECIAL_CHARS);
$id_resto = filter_input(INPUT_POST, 'id_resto', FILTER_VALIDATE_INT);
$id_user = $_SESSION['id'];

if ($message && $id_resto) {
    // 3. Préparer la requête avec les bons noms de colonnes de ta table message_crous
    $sql = "INSERT INTO message_resto (contenu_resto, user_id, resto_id, date) VALUES (?, ?, ?, NOW())";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([$message, $id_user, $id_resto])) {
        // 4. Redirection vers la page du restaurant (attention au nom du paramètre id_resto)
        header("Location: page_restaurant.php?id_resto=" . $id_resto);
        exit();
    } else {
        echo "Erreur lors de l'envoi du message.";
    }
} else {
    header("Location: index.php"); // Redirection si accès direct sans données
}
?>