<?php
session_start();
include '../connectbdd.php';
$id_stock = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $pdo->prepare("UPDATE stock_crous SET stock = ? WHERE id_stock_crous = ?");
    $stmt->execute([$_POST['stock'], $id_stock]);
    // Optionnel : Mise à jour du prix dans produits_crous via l'ID produit
}

$sql = "SELECT s.*, p.nom_produit, p.prix_produit, c.name FROM stock_crous s 
        JOIN produits_crous p ON s.produit_id = p.id_produit 
        JOIN crous c ON s.crous_id = c.id_crous WHERE id_stock_crous = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id_stock]);
$s = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <link rel="stylesheet" href="../style/style_dashboard.css">
</head>

<body>
    <div class="content" style="margin:auto">
        <div class="form-edit">
            <a href="admin_dashboard.php" class="back-link">← Retour</a>
            <h2>Gérer Stock : <?= $s['nom_produit'] ?> (<?= $s['name'] ?>)</h2>
            <form method="POST">
                <div class="form-group">
                    <label>Quantité en Stock</label>
                    <input type="number" name="stock" value="<?= $s['stock'] ?>">
                </div>
                <div class="form-group">
                    <label>Prix actuel</label>
                    <input type="text" value="<?= $s['prix_produit'] ?> €" disabled>
                </div>
                <button type="submit" class="btn btn-save">Mettre à jour le stock</button>
            </form>
        </div>
    </div>
</body>

</html>