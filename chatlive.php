<?php
session_start();
require_once('connectbdd.php'); // Assure-toi que c'est le bon nom de fichier

// 1. Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    die("Vous devez être connecté pour envoyer un message.");
}

// 2. Récupérer les données du formulaire
$message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_SPECIAL_CHARS);
$id_crous = filter_input(INPUT_POST, 'id_crous', FILTER_VALIDATE_INT);
$id_user = $_SESSION['id'];

if ($message && $id_crous) {
    // 3. Préparer la requête avec les bons noms de colonnes de ta table message_crous
    $sql = "INSERT INTO message_crous (contenu_crous, user_id, crous_id, date) VALUES (?, ?, ?, NOW())";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([$message, $id_user, $id_crous])) {
        // 4. Redirection vers la page du restaurant (attention au nom du paramètre id_crous)
        header("Location: page_crous.php?id_crous=" . $id_crous);
        exit();
    } else {
        echo "Erreur lors de l'envoi du message.";
    }
} else {
    header("Location: index.php"); // Redirection si accès direct sans données
}
?>