<?php 

session_start();
if (!isset($_SESSION['login'])) {
    header('Location: connexion.php');
    exit();
}

$user = $_SESSION['login'];

include 'connectbdd.php';

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
    <h1><?= htmlspecialchars($user['lastname'] . " " . $user['firstname']); ?></h1>
    <p>Voici les informations de votre profil.</p>

    <strong>Nom :</strong> <?php echo htmlspecialchars($user['lastname']); ?></li>
<strong>Prénom :</strong> <?php echo htmlspecialchars($user['firstname']); ?></li>

    </ul>

    <a href="logout.php">Se déconnecter</a>
</body>
</html>