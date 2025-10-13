// Kantor Map JavaScript
function initKantorMap(mapData) {
    if (mapData.latitude && mapData.longitude) {
        // Initialize map
        const map = L.map('map').setView([mapData.latitude, mapData.longitude], 15);

        // Add tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Add marker
        const marker = L.marker([mapData.latitude, mapData.longitude]).addTo(map);

        // Create popup content
        const popupContent = '<div class="text-center">' +
            '<h6 class="mb-2">' + mapData.nama_kantor + '</h6>' +
            '<p class="mb-2 text-muted">' + mapData.alamat + '</p>' +
            '<a href="https://www.google.com/maps?q=' + mapData.latitude + ',' + mapData.longitude + '" ' +
            'target="_blank" class="btn btn-sm btn-primary">' +
            '<i class="fas fa-directions me-1"></i>' +
            'Petunjuk Arah' +
            '</a>' +
            '</div>';

        marker.bindPopup(popupContent).openPopup();
    }
}
