<?php 

session_start();
if (!isset($_SESSION['login'])) {
    header('Location: connexion.php');
    exit();
}

include 'connectbdd.php';

$login = $_SESSION['login'];

$sql = "SELECT * FROM users WHERE login = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$login]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
</head>
<body>
<?php include 'header.php'; ?>
    <h1><?= htmlspecialchars($user['lastname'] . " " . $user['firstname']); ?></h1>
    <p><?=  htmlspecialchars($user['login']) ?></p>
    <a href="logout.php">Se déconnecter</a>
    <hr>
    <h2>Mon Tableau de bord CROUS</h2>

    <h3>Informations</h3>
    <hr>  
    <form action="">
        <p>Modifier mes informations</p>
        <label for="nom">Nom</label>
        <input type="text" id="nom">
        <label for="prenom">Prénom</label>
        <input type="text" id="prenom">
        <p>Changer mon mot de passe</p>
        <label for="ancienmdp">Mot de passe actuel</label>
        <input type="text" id="ancienmdp">
        <label for="nouveaumdp">Nouveau mot de passe</label>
        <input type="text" id="nouveaumdp"> 
        <br>
        <button>Sauvegarder les modifications</button>
    </form>

    <h3>Mon Crous</h3>
    <hr>  
    <form action="">
        <p>Modifier mes informations Izly</p>
        <label for="email">Email</label>
        <input type="text" id="email">
        <label for="ncrous">Numéro carte Crous</label>
        <input type="text" id="ncrous">
        <br>
        <button>Sauvegarder les modifications</button>
    </form>

    <h3>Historique</h3>
    <hr> 


</body>
</html>