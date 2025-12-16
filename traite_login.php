<?php
session_start();

// 1. Correction du nom du fichier de connexion. 

require_once ('connectbdd.php');

// Vérifier si les données POST sont présentes
if( isset($_POST['login']) && isset($_POST['password']) ) {

    $login = $_POST['login'];
    $password = $_POST['password'];

    // 2. Recherche optimisée de l'utilisateur par son login

    $sql = "SELECT * FROM users WHERE login = ?"; 
    $stmt = $pdo->prepare($sql);
    
    $stmt->execute([$login]);
    $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

    // 3. Vérification de l'utilisateur et du mot de passe

    if( $utilisateur && password_verify($password, $utilisateur['motdepasse']) ) {
        
        // Connexion réussie : enregistrement des variables de session
        $_SESSION['login'] = $utilisateur['login'];
        $_SESSION['id'] = $utilisateur['id'];
        $_SESSION['nom'] = $utilisateur['lastname'];    
        $_SESSION['prenom'] = $utilisateur['firstname']; 

// L'affichage doit aussi utiliser les bonnes clés (firstname et lastname)
echo "Connexion réussie ! Bienvenue " . htmlspecialchars($utilisateur['firstname']) . " " . htmlspecialchars($utilisateur['lastname']);
        echo "<br><a href='index.php'>Retour à l'accueil</a>";
        exit();
    } else {
        // Utilisateur non trouvé ou mot de passe incorrect
        echo "Login ou mot de passe incorrect.";
    }
} else {
    // Champs manquants
    echo "Veuillez remplir tous les champs.";
} 

// Lien de retour affiché en cas d'erreur ou si les champs sont incomplets
echo "<br><a href='javascript:history.back()'>Retour</a>";
?>