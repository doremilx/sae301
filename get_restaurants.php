<?php 
header('Content-Type: application/json');

// --- 1. Établir la connexion PDO (identique à index.php) ---
// Note : Le script a besoin de la connexion car il est appelé directement.

$db = new PDO('mysql:host=localhost;dbname=lin_atable;port=3306;charset=utf8', 'root', '');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$data = [];

// --- 2. Récupération des données CROUS ---
$sql_crous = "SELECT Nom, Type, Géolocalisation FROM crous";
$stmt_crous = $db->query($sql_crous); // Exécution simple de la requête

// PDO::FETCH_ASSOC permet de récupérer les résultats sous forme de tableau associatif
$result_crous = $stmt_crous->fetchAll(PDO::FETCH_ASSOC); 

foreach($result_crous as $row) {
    // Le séparateur est souvent une virgule avec un espace (ou juste une virgule)
    $coords = explode(',', $row['Géolocalisation']);
    if (count($coords) == 2) {
        $data[] = [
            'name' => $row['Nom'],
            'type' => 'CROUS - ' . $row['Type'],
            'lat' => trim($coords[0]),
            'lon' => trim($coords[1])
        ];
    }
}


// --- 3. Récupération des données Restaurant_1 ---
$sql_resto = "SELECT Nom_Restaurant, Type_Cuisine, Latitude, Longitude FROM restaurant_1";
$stmt_resto = $db->query($sql_resto); // Exécution simple de la requête
$result_resto = $stmt_resto->fetchAll(PDO::FETCH_ASSOC);

foreach($result_resto as $row) {
    $data[] = [
        'name' => $row['Nom_Restaurant'],
        'type' => 'Privé - ' . $row['Type_Cuisine'],
        'lat' => $row['Latitude'],
        'lon' => $row['Longitude']
    ];
}

// 4. Retourner les données en JSON
echo json_encode($data);

// Pas besoin de fermer la connexion PDO, elle se ferme automatiquement.
?>