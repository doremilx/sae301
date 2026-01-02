<?php
session_start();
include '../connectbdd.php';
if ($_SESSION['login'] !== "admin@mail.com") exit;

$id = $_GET['id'];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $pdo->prepare("UPDATE restaurant SET name=?, Type_Cuisine=?, Adresse=?, latitude=?, longitude=?, PMR=?, photo_facade=? WHERE id_resto=?");
    $stmt->execute([$_POST['name'], $_POST['type'], $_POST['adresse'], $_POST['lat'], $_POST['lon'], isset($_POST['pmr']) ? 1 : 0, $_POST['photo'], $id]);
    header("Location: admin_dashboard.php");
}
$resto = $pdo->prepare("SELECT * FROM restaurant WHERE id_resto = ?");
$resto->execute([$id]);
$data = $resto->fetch();
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
            <h2>Modifier Restaurant : <?= $data['name'] ?></h2>
            <form method="POST">
                <div class="form-group">
                    <label>Nom</label>
                    <input type="text" name="name" value="<?= $data['name'] ?>">
                </div>
                <div class="form-group">
                    <label>Cuisine</label>
                    <input type="text" name="type" value="<?= $data['Type_Cuisine'] ?>">
                </div>
                <div class="form-group">
                    <label>Adresse</label>
                    <textarea name="adresse"><?= $data['Adresse'] ?></textarea>
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
                <div class="form-group">
                    <label>Photo Façade (chemin)</label>
                    <input type="text" name="photo" value="<?= $data['photo_facade'] ?>">
                </div>
                <div class="form-group">
                    <label>Photo Menu (chemin)</label>
                    <input type="text" name="photo_menu" value="<?= $data['photo_menu'] ?>">
                </div>
                <button type="submit" class="btn btn-save">Enregistrer</button>
            </form>
        </div>
    </div>
</body>

</html>