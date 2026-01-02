<?php
session_start();
include '../connectbdd.php';
if ($_SESSION['login'] !== "admin@mail.com") exit;

$id = $_GET['id'];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $pdo->prepare("UPDATE crous SET name=?, adresse=?, latitude=?, longitude=?, PMR=?, photo=? WHERE id_crous=?");
    $stmt->execute([$_POST['name'], $_POST['adresse'], $_POST['lat'], $_POST['lon'], isset($_POST['pmr']) ? 1 : 0, $_POST['photo'], $id]);
    header("Location: admin_dashboard.php");
}
$crous = $pdo->prepare("SELECT * FROM crous WHERE id_crous = ?");
$crous->execute([$id]);
$data = $crous->fetch();
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
            <h2>Modifier CROUS : <?= $data['name'] ?></h2>
            <form method="POST">
                <div class="form-group">
                    <label>Nom</label>
                    <input type="text" name="name" value="<?= $data['name'] ?>">
                </div>
                <div class="form-group">
                    <label>Adresse</label>
                    <textarea name="adresse"><?= $data['adresse'] ?></textarea>
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label>Latitude</label>
                        <input type="text" name="lat" value="<?= $data['latitude'] ?>">
                    </div>
                    <div class="form-group">
                        <label>Longitude</label>
                        <input type="text" name="lon" value="<?= $data['longitude'] ?>">
                    </div>
                </div>
                <button type="submit" class="btn btn-save">Enregistrer</button>
            </form>
        </div>
    </div>
</body>

</html>