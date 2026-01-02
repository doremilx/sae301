<?php
session_start();
include '../connectbdd.php';

// Sécurité Admin
if (!isset($_SESSION['login']) || $_SESSION['login'] !== "admin@mail.com") { exit; }

$id_user = $_GET['id'];

// Traitement du changement de statut
if (isset($_POST['update_status'])) {
    $stmt = $pdo->prepare("UPDATE commandes SET statut = ? WHERE id_commande = ?");
    $stmt->execute([$_POST['statut'], $_POST['id_commande']]);
}

// Récupération des infos utilisateur
$user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$user->execute([$id_user]);
$u = $user->fetch();

// Récupération des commandes de l'utilisateur avec le nom du CROUS
$sql = "SELECT c.*, cr.name as crous_name 
        FROM commandes c 
        JOIN crous cr ON c.crous_id = cr.id_crous 
        WHERE c.user_id = ? 
        ORDER BY c.date_commande DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id_user]);
$commandes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../style/style_dashboard.css">
    <title>Commandes de <?= htmlspecialchars($u['firstname']) ?></title>
</head>
<body>
    <div class="content" style="margin:auto">
        <section class="section-card">
            <a href="admin_dashboard.php" class="back-link">← Retour</a>
            <h2>Historique des commandes : <?= htmlspecialchars($u['firstname'] . " " . $u['lastname']) ?></h2>
            
            <?php if (empty($commandes)): ?>
                <p>Aucune commande enregistrée pour cet utilisateur.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Lieu</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Statut</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($commandes as $com): ?>
                        <tr>
                            <td>#<?= $com['id_commande'] ?></td>
                            <td><?= htmlspecialchars($com['crous_name']) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($com['date_commande'])) ?></td>
                            <td><?= $com['total'] ?> €</td>
                            <td>
                                <span class="status-badge" style="background: <?= ($com['statut'] == 'Annulée') ? 'var(--danger)' : '#10b981' ?>;">
                                    <?= $com['statut'] ?>
                                </span>
                            </td>
                            <td>
                                <form method="POST" style="display:flex; gap:5px;">
                                    <input type="hidden" name="id_commande" value="<?= $com['id_commande'] ?>">
                                    <select name="statut" style="width: auto; padding: 5px;">
                                        <option value="En attente">En attente</option>
                                        <option value="Préparée">Préparée</option>
                                        <option value="Récupérée">Récupérée</option>
                                        <option value="Annulée">Annulée</option>
                                    </select>
                                    <button type="submit" name="update_status" class="btn btn-edit">OK</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>
    </div>
</body>
</html>