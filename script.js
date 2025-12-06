document.addEventListener('DOMContentLoaded', function () {
    // 1. Initialiser la carte Leaflet
    // Correction ici : utiliser 'map' (l'ID) au lieu de '#map' (sélecteur CSS)
    const centerLat = 48.840;
    const centerLon = 2.585;

    const mymap = L.map('map').setView([centerLat, centerLon], 15); // Ligne corrigée

    // 2. Ajouter le fond de carte (OpenStreetMap)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(mymap);

    // 3. Récupérer les données des restaurants via PHP/Ajax
    fetch('get_restaurants.php')
        .then(response => {
            // Vérifier si la réponse HTTP est OK
            if (!response.ok) {
                throw new Error(`Erreur HTTP: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            // Vérifier si le JSON contient une erreur de BDD (gérée dans le PHP corrigé)
            if (data.error) {
                console.error("Erreur de BDD:", data.error);
                return;
            }

            // 4. Parcourir les données et ajouter un marqueur pour chaque restaurant
            data.forEach(restaurant => {
                const lat = parseFloat(restaurant.lat);
                const lon = parseFloat(restaurant.lon);

                if (isNaN(lat) || isNaN(lon)) {
                    console.warn(`Coordonnées invalides pour ${restaurant.name}`);
                    return;
                }

                let popupContent = `<b>${restaurant.name}</b><br>${restaurant.type}`;
                let marker = L.marker([lat, lon]);

                // Ici, vous pouvez ajouter une icône spécifique si vous voulez
                // Exemple : if (restaurant.type.startsWith('CROUS')) { ... }

                marker.bindPopup(popupContent).addTo(mymap);
            });
        })
        .catch(error => {
            console.error('Erreur lors de la récupération des données:', error);
            // Afficher un message à l'utilisateur si possible (facultatif)
            alert('Impossible de charger les données des restaurants. Vérifiez la connexion BDD.');
        });
});