<?php
session_start();


require_once ('connectbdd.php');

if( isset($_POST['login']) && isset($_POST['password']) ) {

    $login = $_POST['login'];
    $password = $_POST['password'];


    $sql = "SELECT * FROM users WHERE login = ?"; 
    $stmt = $pdo->prepare($sql);
    
    $stmt->execute([$login]);
    $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);


    if( $utilisateur && password_verify($password, $utilisateur['motdepasse']) ) {
        
        $_SESSION['login'] = $utilisateur['login'];
        $_SESSION['id'] = $utilisateur['id'];
        $_SESSION['nom'] = $utilisateur['lastname'];    
        $_SESSION['prenom'] = $utilisateur['firstname']; 


echo "Connexion réussie ! Bienvenue " . htmlspecialchars($utilisateur['firstname']) . " " . htmlspecialchars($utilisateur['lastname']);
        echo "<br><a href='index.php'>Retour à l'accueil</a>";
        exit();
    } else {

        echo "Login ou mot de passe incorrect.";
    }
} else {

    echo "Veuillez remplir tous les champs.";
} 


echo "<br><a href='javascript:history.back()'>Retour</a>";
?>