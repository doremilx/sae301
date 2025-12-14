document.addEventListener('DOMContentLoaded', function () {
    const markersMap = new Map();

    // 1. Initialiser la carte Leaflet
    const centerLat = 48.840;
    const centerLon = 2.585;

    const mymap = L.map('map').setView([centerLat, centerLon], 15);

    // 2. Ajouter le fond de carte (OpenStreetMap)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(mymap);

    // 3. Récupérer les données des restaurants via PHP/Ajax
    fetch('get_restaurants.php')
        .then(response => {
            if (!response.ok) {
                throw new Error(`Erreur HTTP: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                console.error("Erreur de BDD:", data.error);
                alert(`Erreur de BDD: ${data.error}`);
                return;
            }

            // 4. Parcourir les données et ajouter un marqueur pour chaque restaurant
            data.forEach(restaurant => {
                const lat = parseFloat(restaurant.latitude || restaurant.lat); // latitude ou lat
                const lon = parseFloat(restaurant.longitude || restaurant.lon); // longitude ou lon

                if (isNaN(lat) || isNaN(lon)) {
                    console.warn(`Coordonnées invalides pour ${restaurant.name}`);
                    return;
                }

                let restaurantId = '';
                let linkParamName = '';
                let targetPage = 'page_restaurant.php'; // Page par défaut (pour les privés)
                let uniqueId = ''; // Clé de la Map

                if (restaurant.id_crous) {
                    restaurantId = restaurant.id_crous;
                    linkParamName = `id_crous`;
                    targetPage = 'page_crous.php';
                    uniqueId = `crous_${restaurant.id_crous}`; // Clé unique
                } else if (restaurant.id_resto) {
                    restaurantId = restaurant.id_resto;
                    linkParamName = `id_resto`;
                    uniqueId = `prive_${restaurant.id_resto}`; // Clé unique
                } else {
                    console.warn(`ID non trouvé pour le restaurant: ${restaurant.name}`);
                    return;
                }

                // Construire le contenu de la popup
                let popupContent = `
                    <a href="${targetPage}?${linkParamName}=${restaurantId}"> 
                        <b>${restaurant.name}</b><br>
                        ${restaurant.type}
                    </a>
                `;

                let marker = L.marker([lat, lon]);
                marker.bindPopup(popupContent).addTo(mymap);

                // 5. Stocker le marqueur dans la Map pour le retrouver par la suite
                markersMap.set(uniqueId, marker);
            });
        })
        .catch(error => {
            console.error('Erreur lors de la récupération ou du traitement des données:', error);
            alert('Impossible de charger les données des restaurants. Vérifiez la connexion BDD et la console.');
        });


    // --- Logique de filtrage des cartes et des marqueurs ---

    const filterButtons = document.querySelectorAll('.filtre-btn');
    const restaurantCards = document.querySelectorAll('.restaurant-carte');

    filterButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();

            // Gestion de la classe active
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            const filterValue = this.getAttribute('data-filter');

            restaurantCards.forEach(card => {
                const cardType = card.getAttribute('data-type');
                const cardPMR = card.getAttribute('data-pmr');
                const cardMarkerId = card.getAttribute('data-marker-id'); // Récupère l'ID unique

                let shouldShow = false;

                if (filterValue === 'all') {
                    shouldShow = true;
                } else if (filterValue === 'crous') {
                    shouldShow = (cardType === 'crous');
                } else if (filterValue === 'pmr') {
                    shouldShow = (cardPMR === '1');
                } else if (filterValue === 'ouvert') {
                    // Utilise la classe is-open ajoutée par PHP si vous avez implémenté la logique d'ouverture
                    shouldShow = card.classList.contains('is-open');
                } else if (filterValue === 'favoris') {
                    // Logique favoris
                    shouldShow = true; // Placeholder
                }

                // 1. Affichage ou masquage de la carte (listes)
                if (shouldShow) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }

                // 2. Affichage ou masquage du marqueur (carte Leaflet)
                const marker = markersMap.get(cardMarkerId);
                if (marker) {
                    // Utiliser setOpacity(0) pour masquer et setOpacity(1) pour afficher
                    if (shouldShow) {
                        marker.setOpacity(1);
                        marker.setZIndexOffset(1000); // S'assurer que les marqueurs visibles sont au-dessus
                    } else {
                        marker.setOpacity(0);
                        marker.setZIndexOffset(0); // Mettre les marqueurs masqués en arrière-plan
                    }
                }
            });
        });
    });

    // Déclencher le filtre 'all' au chargement pour tout afficher par défaut
    const defaultFilter = document.querySelector('.filtre-btn[data-filter="all"]');
    if (defaultFilter) {
        // Un petit délai pour s'assurer que les marqueurs sont bien tous chargés dans la Map
        setTimeout(() => {
            defaultFilter.click();
        }, 500);
    }
});