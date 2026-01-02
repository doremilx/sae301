<?php
session_start();
include("../connectbdd.php");

// Sécurité
$admin_email = "admin@mail.com";
if (!isset($_SESSION['login']) || $_SESSION['login'] !== $admin_email) {
    header('Location: connexion.php');
    exit();
}

// Suppression message chat CROUS
if (isset($_GET['action'])) {
    if ($_GET['action'] == 'delete_msg_crous' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("DELETE FROM message_crous WHERE id_crous = ?");
        $stmt->execute([$_GET['id']]);
        header('Location: admin_dashboard.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../style/style_dashboard.css">
</head>
<body>

<aside class="sidebar">
    <h2>Back-Office</h2>
    <nav>
        <a href="#users"><i class="fa-solid fa-users"></i> Utilisateurs</a>
        <a href="#places"><i class="fa-solid fa-shop"></i> Restos & Crous</a>
        <a href="#stocks"><i class="fa-solid fa-cubes"></i> Stocks Produits</a>
        <a href="#chat"><i class="fa-solid fa-comment-dots"></i> Modération Chat</a>
        <a href="logout.php" style="color: var(--danger);">
            <i class="fa-solid fa-right-from-bracket"></i> Déconnexion
        </a>
    </nav>
</aside>

<main class="content">
    
    <section id="users" class="section-card">
        <h2><i class="fa-solid fa-user-gear"></i> Gestion des Comptes</h2>
        <table>
            <thead>
                <tr>
                    <th>Nom / Prénom</th>
                    <th>Email</th>
                    <th>Statut Boursier</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $users = $pdo->query("SELECT * FROM users")->fetchAll();
                foreach($users as $u): ?>
                <tr>
                    <td><?= htmlspecialchars($u['lastname'] . " " . $u['firstname']) ?></td>
                    <td><?= htmlspecialchars($u['login']) ?></td>
                    <td><span class="status-badge"><?= $u['boursier'] ? 'Oui' : 'Non' ?></span></td>
                    <td>
                        <a href="users_commandes.php?id=<?= $u['id'] ?>" class="btn btn-edit">
                             Commandes
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <section id="places" class="section-card">
        <h2><i class="fa-solid fa-location-dot"></i> Établissements</h2>
        <div class="places-grid">
            <div>
                <h3>CROUS</h3>
                <?php
                $crous = $pdo->query("SELECT id_crous, name FROM crous")->fetchAll();
                foreach($crous as $c): ?>
                    <div class="place-item">
                        <span><?= htmlspecialchars($c['name']) ?></span>
                        <a href="edit_crous.php?id=<?= $c['id_crous'] ?>" class="btn btn-edit">Modifier</a>
                    </div>
                <?php endforeach; ?>
            </div>
            <div>
                <h3>RESTAURANTS</h3>
                <?php
                $restos = $pdo->query("SELECT id_resto, name FROM restaurant")->fetchAll();
                foreach($restos as $r): ?>
                    <div class="place-item">
                        <span><?= htmlspecialchars($r['name']) ?></span>
                        <a href="edit_resto.php?id=<?= $r['id_resto'] ?>" class="btn btn-edit">Modifier</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section id="stocks" class="section-card">
        <h2><i class="fa-solid fa-boxes-stacked"></i> Stocks & Prix (CROUS)</h2>
        <table>
            <thead>
                <tr>
                    <th>Lieu</th>
                    <th>Produit</th>
                    <th>Prix Unitaire</th>
                    <th>Quantité</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stocks = $pdo->query("SELECT s.*, p.nom_produit, p.prix_produit, c.name as c_name 
                                       FROM stock_crous s 
                                       JOIN produits_crous p ON s.produit_id = p.id_produit 
                                       JOIN crous c ON s.crous_id = c.id_crous")->fetchAll();
                foreach($stocks as $s): ?>
                <tr>
                    <td><?= htmlspecialchars($s['c_name']) ?></td>
                    <td><?= htmlspecialchars($s['nom_produit']) ?></td>
                    <td><?= $s['prix_produit'] ?> €</td>
                    <td style="font-weight: bold; color: var(--accent);"><?= $s['stock'] ?></td>
                    <td>
                        <a href="edit_produits.php?id=<?= $s['id_stock_crous'] ?>" class="btn btn-edit">Gérer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <section id="chat" class="section-card">
        <h2><i class="fa-solid fa-shield-halved"></i> Modération Chat Live</h2>
        <?php
        $messages = $pdo->query("SELECT m.*, u.login, c.name FROM message_crous m 
                                 JOIN users u ON m.user_id = u.id 
                                 JOIN crous c ON m.crous_id = c.id_crous 
                                 ORDER BY date DESC LIMIT 8")->fetchAll();
        foreach($messages as $m): ?>
            <div class="msg-container">
                <div class="msg-info">
                    <small><?= $m['date'] ?> | <?= htmlspecialchars($m['name']) ?></small>
                    <div class="msg-text">
                        <strong><?= htmlspecialchars($m['login']) ?> :</strong> <?= htmlspecialchars($m['contenu_crous']) ?>
                    </div>
                </div>
                <a href="admin_dashboard.php?action=delete_msg_crous&id=<?= $m['id_crous'] ?>" 
                   onclick="return confirm('Supprimer ce message définitivement ?')" 
                   class="btn btn-delete">
                    <i class="fa-solid fa-trash"></i>
                </a>
            </div>
        <?php endforeach; ?>
    </section>

</main>

</body>
</html>