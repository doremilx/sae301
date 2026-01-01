document.addEventListener('DOMContentLoaded', function () {
    const markersMap = new Map();

    // 1. Initialiser la carte avec un style sombre
    const mymap = L.map('map', { zoomControl: false }).setView([48.840, 2.585], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
}).addTo(mymap);

    L.control.zoom({ position: 'topright' }).addTo(mymap);

    // 2. Icône personnalisée (goutte blanche de la maquette)
    const whiteIcon = L.divIcon({
        className: 'custom-dot',
        iconSize: [15, 15]
    });

    // 3. Charger les marqueurs
    fetch('get_restaurants.php')
        .then(response => response.json())
        .then(data => {
            data.forEach(res => {
                const lat = parseFloat(res.latitude || res.lat);
                const lon = parseFloat(res.longitude || res.lon);
                if (isNaN(lat) || isNaN(lon)) return;

                const id = res.id_crous ? `crous_${res.id_crous}` : `prive_${res.id_resto}`;
                const marker = L.marker([lat, lon], { icon: whiteIcon }).addTo(mymap);
                
                marker.bindPopup(`<b>${res.name}</b>`);
                markersMap.set(id, marker);
            });
        });

    // 4. Filtrage dynamique
    const buttons = document.querySelectorAll('.filtre-btn');
    const cards = document.querySelectorAll('.restaurant-carte');

    buttons.forEach(btn => {
        btn.addEventListener('click', function() {
            buttons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            const filter = this.dataset.filter;

            cards.forEach(card => {
                const type = card.dataset.type;
                const pmr = card.dataset.pmr;
                const mId = card.dataset.markerId;
                const marker = markersMap.get(mId);

                let show = (filter === 'all') || 
                           (filter === 'crous' && type === 'crous') || 
                           (filter === 'pmr' && pmr === '1');

                card.style.display = show ? 'block' : 'none';
                if (marker) {
                    if (show) mymap.addLayer(marker); 
                    else mymap.removeLayer(marker);
                }
            });
        });
    });
});