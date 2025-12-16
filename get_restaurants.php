<?php 
header('Content-Type: application/json');

// --- 1. Établir la connexion PDO (identique à index.php) ---
// Note : Le script a besoin de la connexion car il est appelé directement.

$db = new PDO('mysql:host=localhost;dbname=lin_atable;port=3306;charset=utf8', 'root', '');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$data = []; // Tableau pour stocker toutes les données des restaurants

// --- 2. Récupération des données CROUS ---
$sql_crous = "
    SELECT * FROM crous";
$stmt_crous = $db->query($sql_crous); 
$result_crous = $stmt_crous->fetchAll(PDO::FETCH_ASSOC); 

foreach($result_crous as $row) {
    $data[] = [
        'id_crous' => $row['id_crous'],
        'name' => $row['name'],
        'type' => 'CROUS - ' . $row['type'],
        'lat' => floatval($row['latitude']), // On s'assure que c'est bien un nombre float
        'lon' => floatval($row['longitude'])
    ];
}


// --- 3. Récupération des données Restaurant_1 ---
$sql_resto = "
    SELECT * FROM restaurant";
$stmt_resto = $db->query($sql_resto); 
$result_resto = $stmt_resto->fetchAll(PDO::FETCH_ASSOC);

foreach($result_resto as $row) {
    $data[] = [
        'id_resto' => $row['id_resto'],
        'name' => $row['name'],
        'type' => 'Privé - ' . $row['Type_Cuisine'],
        'lat' => floatval($row['latitude']), // On s'assure que c'est bien un nombre float
        'lon' => floatval($row['longitude'])
    ];
}

// --- 4. Retourner les données en JSON, donc le fichier actuel et_restaurant.php c'est un fichier .json en gros, on oublie le php pour un moment haha---
echo json_encode($data);
?>