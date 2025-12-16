<?php
require_once('connectbdd.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (
        isset($_POST['nom']) && $_POST['nom'] !== "" &&
        isset($_POST['prenom']) && $_POST['prenom'] !== "" &&
        isset($_POST['login']) && $_POST['login'] !== "" &&
        isset($_POST['motdepasse']) && $_POST['motdepasse'] !== "" 
    ) {

        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $login = $_POST['login'];
        $motdepasse = $_POST['motdepasse'];
        $motdepassehash = password_hash($motdepasse, PASSWORD_DEFAULT);

        //Vérifier si le mail existe déjà
        $sql = "SELECT * FROM users WHERE login = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$login]);
        $existant = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existant) {
            echo "Erreur : cet email est déjà utilisé.";
            echo "<br><a href='login.php'>Retour</a>";
            exit;
        }

        // Insertion en base
        $sql = "INSERT INTO users (login, motdepasse, lastname, firstname)
                VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$login, $motdepassehash, $nom, $prenom]);

        echo "Compte créé avec succès";
        echo "<br><a href='login.php'>Retour à la page de connexion</a>";
        exit;
    }

    echo "Erreur : tous les champs doivent être remplis.";
    echo "<br><a href='javascript:history.back()'>Retour</a>";
}
?>
