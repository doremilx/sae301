<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('Location: connexion.php');
    exit();
}

include 'connectbdd.php';
$login = $_SESSION['login'];
$error = null;
$success = null;

$stmt = $pdo->prepare("SELECT * FROM users WHERE login = ?");
$stmt->execute([$login]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_pass'])) {
    $ancienMdp = $_POST['ancienmdp'];
    $nouveauMdp = $_POST['nouveaumdp'];

    if (!empty($ancienMdp) && !empty($nouveauMdp)) {

        if (password_verify($ancienMdp, $user['motdepasse'])) {

            $nouveauMdpHash = password_hash($nouveauMdp, PASSWORD_BCRYPT);

            $update = $pdo->prepare("UPDATE users SET motdepasse = ? WHERE login = ?");
            if ($update->execute([$nouveauMdpHash, $login])) {
                $success = "Mot de passe modifié avec succès !";
            } else {
                $error = "Erreur lors de la mise à jour.";
            }
        } else {
            $error = "L'ancien mot de passe est incorrect.";
        }
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style/style_profile.css">
    <title>Mon Profil - Crousty Map</title>
</head>

<body>
    <?php include 'header.php'; ?>

    <div class="container">
        <div class="profile-header">
            <div class="user-info">
                <div class="avatar-circle">
                    <?= strtoupper(substr($user['firstname'], 0, 1) . substr($user['lastname'], 0, 1)); ?>
                </div>
                <div>
                    <h2 style="margin:0"><?= htmlspecialchars($user['lastname'] . " " . $user['firstname']); ?></h2>
                    <span style="opacity:0.6"><?= htmlspecialchars($user['login']) ?></span>
                </div>
            </div>
            <a href="logout.php" class="btn-logout">Se déconnecter</a>
        </div>

        <h1>Mon Tableau de Bord CROUS</h1>

        <div class="stats-container">
            <div class="card">
                <h4>Solde Izly disponible</h4>
                <p>13,70 €</p>
            </div>
            <div class="card">
                <h4>Réservations CROUS</h4>
                <p>28</p>
            </div>
            <div class="card">
                <h4>Réservations actives</h4>
                <p>1 Repas</p>
            </div>
        </div>

        <div class="tabs">
            <div class="tab-link active" onclick="openTab(event, 'tab-info')">Informations</div>
            <div class="tab-link" onclick="openTab(event, 'tab-crous')">Mon Crous</div>
            <div class="tab-link" onclick="openTab(event, 'tab-history')">Historique</div>
        </div>

        <div id="tab-info" class="tab-content active">
            <?php if ($error): ?>
                <div style="color: #ff4d4d; margin-bottom: 15px;"><?= $error ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div style="color: #2ecc71; margin-bottom: 15px;"><?= $success ?></div>
            <?php endif; ?>

            <form action="profile.php" method="POST">
                <h3 class="full-width">Modifier mes informations</h3>
                <div>
                    <label>Nom</label>
                    <input type="text" name="nom" value="<?= $user['lastname'] ?>">
                </div>
                <div>
                    <label>Prénom</label>
                    <input type="text" name="prenom" value="<?= $user['firstname'] ?>">
                </div>

                <h3 class="full-width">Changer mon mot de passe</h3>
                <div>
                    <label>Mot de passe actuel</label>
                    <input type="password" name="ancienmdp">
                </div>
                <div>
                    <label>Nouveau mot de passe</label>
                    <input type="password" name="nouveaumdp">
                </div>
                <div class="full-width">
                    <button type="submit" name="update_pass" class="save-btn">Sauvegarder les modifications</button>
                </div>
            </form>
        </div>

        <div id="tab-crous" class="tab-content">
            <form action="" method="POST">
                <h3 class="full-width">Modifier mes informations Izly</h3>
                <div>
                    <label>Email</label>
                    <input type="email" name="email_izly" placeholder="exemple@mail.com">
                </div>
                <div>
                    <label>Numéro Carte CROUS</label>
                    <input type="text" name="ncrous" placeholder="123456789">
                </div>
                <div class="full-width">
                    <button type="submit" class="save-btn">Sauvegarder les modifications</button>
                </div>
            </form>
        </div>

        <div id="tab-history" class="tab-content">
            <h3>Historique des Réservations</h3>
            <div class="history-item">
                <span>CROUS Cafet' IUT</span>
                <span>28/11/2025 à 12:45</span>
                <span class="status-recupere">Récupéré</span>
            </div>
            <div class="history-item">
                <span>CROUS ESIEE</span>
                <span>27/11/2025 à 12:10</span>
                <span class="status-annule">Annulé</span>
            </div>
        </div>
    </div>

    <script>
        function openTab(evt, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tab-content");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].classList.remove("active");
            }
            tablinks = document.getElementsByClassName("tab-link");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].classList.remove("active");
            }
            document.getElementById(tabName).classList.add("active");
            evt.currentTarget.classList.add("active");
        }
    </script>
</body>

</html>