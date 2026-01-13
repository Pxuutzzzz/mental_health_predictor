<?php
$page = 'professionals';
$pageTitle = 'Cari Psikolog';

ob_start();
?>

<!-- Stats Row -->
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-info border-start border-4 border-info">
            <i class="bi bi-info-circle-fill me-2"></i>
            <strong>Temukan Bantuan Terdekat:</strong> Gunakan alat ini untuk menemukan profesional kesehatan mental di area Anda. Klik "Gunakan Lokasi Saya" atau cari berdasarkan kota/alamat.
        </div>
    </div>
</div>

<!-- Map Card -->
<div class="card mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold">
            <i class="bi bi-geo-alt"></i> Profesional Kesehatan Mental Terdekat
        </h6>
        <div>
            <button class="btn btn-success btn-sm me-2" onclick="setLocationManually()">
                <i class="bi bi-pin-map-fill"></i> Tentukan Lokasi di Peta
            </button>
            <button class="btn btn-primary btn-sm" onclick="getUserLocation()">
                <i class="bi bi-crosshair"></i> GPS Otomatis
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <div id="locationHint" class="alert alert-warning m-3 mb-0" style="display: none;">
            <i class="bi bi-hand-index-thumb"></i> 
            <strong>Klik pada peta</strong> untuk menentukan lokasi Anda yang tepat, lalu klik "Cari di Lokasi Ini"
        </div>
        <div id="map" style="height: 500px; width: 100%;"></div>
    </div>
    <div class="card-footer">
        <div class="d-flex justify-content-between align-items-center">
            <small class="text-muted">
                <i class="bi bi-info-circle"></i> 
                <strong>Koordinat:</strong> <span id="mapCenter">Memuat...</span>
            </small>
            <button id="searchHereBtn" class="btn btn-sm btn-success" onclick="searchCurrentMapCenter()" style="display: none;">
                <i class="bi bi-search"></i> Cari di Lokasi Ini
            </button>
        </div>
    </div>
</div>

<!-- Search Card -->
<div class="card mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold">
            <i class="bi bi-search"></i> Cari Profesional
        </h6>
    </div>
    <div class="card-body">
        <ul class="nav nav-tabs mb-3" id="searchTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="city-tab" data-bs-toggle="tab" data-bs-target="#citySearch" type="button" role="tab">
                    <i class="bi bi-geo-alt"></i> Cari Berdasarkan Kota
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="coords-tab" data-bs-toggle="tab" data-bs-target="#coordsSearch" type="button" role="tab">
                    <i class="bi bi-pin-map"></i> Koordinat Manual
                </button>
            </li>
        </ul>
        
        <div class="tab-content" id="searchTabContent">
            <!-- City Search Tab -->
            <div class="tab-pane fade show active" id="citySearch" role="tabpanel">
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label">Cari Lokasi (Kota atau Alamat)</label>
                        <input type="text" id="searchLocation" class="form-control" placeholder="Contoh: Jakarta Pusat, Surabaya, Bandung">
                        <small class="text-muted">Coba lebih spesifik: "Jl. Sudirman Jakarta" atau "Mall Taman Anggrek"</small>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Radius Pencarian</label>
                        <select id="searchRadius" class="form-select">
                            <option value="1">1 km</option>
                            <option value="2">2 km</option>
                            <option value="5" selected>5 km</option>
                            <option value="10">10 km</option>
                            <option value="20">20 km</option>
                        </select>
                    </div>
                </div>
                <button class="btn btn-primary" onclick="searchByLocation()">
                    <i class="bi bi-search"></i> Cari
                </button>
            </div>
            
            <!-- Manual Coordinates Tab -->
            <div class="tab-pane fade" id="coordsSearch" role="tabpanel">
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Tip:</strong> Dapatkan koordinat tepat Anda dari Google Maps dengan klik kanan lokasi Anda dan pilih koordinat.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <div class="row">
                    <div class="col-md-5 mb-3">
                        <label class="form-label">Lintang (Latitude)</label>
                        <input type="number" id="manualLat" class="form-control" placeholder="-6.2088" step="0.000001">
                        <small class="text-muted">Contoh: -6.2088 (Jakarta)</small>
                    </div>
                    <div class="col-md-5 mb-3">
                        <label class="form-label">Bujur (Longitude)</label>
                        <input type="number" id="manualLng" class="form-control" placeholder="106.8456" step="0.000001">
                        <small class="text-muted">Contoh: 106.8456 (Jakarta)</small>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Radius</label>
                        <select id="manualRadius" class="form-select">
                            <option value="1">1 km</option>
                            <option value="2">2 km</option>
                            <option value="5" selected>5 km</option>
                            <option value="10">10 km</option>
                            <option value="20">20 km</option>
                        </select>
                    </div>
                </div>
                <button class="btn btn-primary" onclick="searchByCoordinates()">
                    <i class="bi bi-pin-map"></i> Cari dengan Koordinat
                </button>
                <button class="btn btn-outline-secondary" onclick="pasteCoordinates()">
                    <i class="bi bi-clipboard"></i> Tempel dari Clipboard
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Results Card -->
<div class="card mb-4" id="resultsCard" style="display: none;">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold">
            <i class="bi bi-list-ul"></i> Hasil Pencarian <span id="resultsCount" class="badge bg-primary ms-2">0</span>
        </h6>
    </div>
    <div class="card-body">
        <div id="resultsList" class="row"></div>
    </div>
</div>

<!-- Emergency Contacts Card -->
<div class="card mb-4">
    <div class="card-header py-3 bg-danger text-white">
        <h6 class="m-0 font-weight-bold">
            <i class="bi bi-telephone-fill"></i> Kontak Darurat Kesehatan Mental
        </h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="d-flex align-items-center p-3 bg-light rounded">
                    <i class="bi bi-headset fs-3 text-danger me-3"></i>
                    <div>
                        <div class="fw-bold">Hotline Krisis Kesehatan Mental</div>
                        <div class="text-muted small">Tersedia 24/7</div>
                        <a href="tel:500454" class="text-danger fw-bold">500-454</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="d-flex align-items-center p-3 bg-light rounded">
                    <i class="bi bi-hospital fs-3 text-danger me-3"></i>
                    <div>
                        <div class="fw-bold">Darurat Nasional</div>
                        <div class="text-muted small">Darurat Medis</div>
                        <a href="tel:119" class="text-danger fw-bold">119</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="d-flex align-items-center p-3 bg-light rounded">
                    <i class="bi bi-chat-dots fs-3 text-info me-3"></i>
                    <div>
                        <div class="fw-bold">Into The Light Indonesia</div>
                        <div class="text-muted small">Pencegahan Bunuh Diri</div>
                        <a href="tel:+6281287877788" class="text-info fw-bold">+62 812-8787-7788</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="d-flex align-items-center p-3 bg-light rounded">
                    <i class="bi bi-heart-pulse fs-3 text-success me-3"></i>
                    <div>
                        <div class="fw-bold">Yayasan Pulih</div>
                        <div class="text-muted small">Pemulihan Trauma</div>
                        <a href="tel:+62217880015" class="text-success fw-bold">+62 21 788-0015</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<style>
.leaflet-popup-content {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.professional-card {
    transition: transform 0.2s, box-shadow 0.2s;
    cursor: pointer;
    height: 100%;
}

.professional-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.rating-stars {
    color: #ffc107;
}

.status-badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

.loading-message {
    padding: 2rem;
    text-align: center;
    color: #6c757d;
}
</style>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
let map;
let userMarker;
let markersLayer;
let professionals = [];

// Initialize map
function initMap() {
    // Center on Jakarta by default
    map = L.map('map').setView([-6.2088, 106.8456], 12);
    
    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);
    
    // Initialize markers layer
    markersLayer = L.layerGroup().addTo(map);
}

// Get user's current location
function getUserLocation() {
    if (!navigator.geolocation) {
        alert('Geolocation tidak didukung oleh browser Anda. Silakan cari berdasarkan nama kota.');
        return;
    }
    
    showLoading();
    
    // Request high accuracy location with longer timeout
    const options = {
        enableHighAccuracy: true,
        timeout: 30000, // Increase timeout to 30 seconds
        maximumAge: 0 // Don't use cached position
    };
    
    // Show info to user
    console.log('Mendeteksi lokasi Anda... Pastikan GPS aktif dan izin lokasi diaktifkan.');
    
    navigator.geolocation.getCurrentPosition(
        position => {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            const accuracy = position.coords.accuracy;
            const altitude = position.coords.altitude;
            const heading = position.coords.heading;
            const speed = position.coords.speed;
            
            console.log('Lokasi terdeteksi:', { 
                lat, 
                lng, 
                accuracy: Math.round(accuracy) + 'm',
                altitude,
                heading,
                speed,
                timestamp: new Date(position.timestamp).toLocaleString()
            });
            
            // Warn if accuracy is poor (> 100m)
            if (accuracy > 100) {
                console.warn('⚠️ Akurasi rendah:', Math.round(accuracy) + 'm. Pertimbangkan menggunakan koordinat manual untuk hasil lebih akurat.');
            }
            
            // Center map on user location
            map.setView([lat, lng], 16); // Zoom closer
            
            // Add user marker
            if (userMarker) {
                map.removeLayer(userMarker);
            }
            
            userMarker = L.marker([lat, lng], {
                icon: L.icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowSize: [41, 41]
                })
            }).addTo(map);
            
            // Show accuracy circle
            L.circle([lat, lng], {
                radius: accuracy,
                color: accuracy > 100 ? '#f6c23e' : '#1cc88a', // Yellow if poor accuracy
                fillColor: accuracy > 100 ? '#f6c23e' : '#1cc88a',
                fillOpacity: 0.1,
                weight: 2
            }).addTo(map);
            
            const popupText = `<b>Lokasi Anda</b><br>
                <small>Lat: ${lat.toFixed(6)}<br>
                Lng: ${lng.toFixed(6)}<br>
                Akurasi: ±${Math.round(accuracy)}m</small><br>
                ${accuracy > 100 ? '<span style="color: #f6c23e;">⚠️ Akurasi rendah, gunakan zoom untuk verifikasi</span>' : '<span style="color: #1cc88a;">✓ Akurasi baik</span>'}`;
            
            userMarker.bindPopup(popupText).openPopup();
            
            // If accuracy is poor, suggest manual input
            if (accuracy > 100) {
                setTimeout(() => {
                    if (confirm('Akurasi lokasi GPS kurang baik (±' + Math.round(accuracy) + 'm).\n\n' +
                               'Untuk hasil lebih akurat, gunakan:\n' +
                               '1. Tab "Koordinat Manual" dengan koordinat tepat dari Google Maps\n' +
                               '2. Atau "Cari Berdasarkan Kota" dengan nama lokasi spesifik\n\n' +
                               'Lanjutkan dengan lokasi saat ini?')) {
                        searchNearby(lat, lng);
                    }
                    hideLoading();
                }, 500);
            } else {
                // Good accuracy, proceed with search
                searchNearby(lat, lng);
                hideLoading();
            }
        },
        error => {
            hideLoading();
            
            let errorMessage = '';
            let suggestion = '';
            
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    errorMessage = "Akses lokasi ditolak.";
                    suggestion = "\n\nCara mengaktifkan:\n" +
                               "1. Klik ikon gembok/info di address bar\n" +
                               "2. Ubah izin 'Lokasi' menjadi 'Izinkan'\n" +
                               "3. Refresh halaman dan coba lagi";
                    break;
                case error.POSITION_UNAVAILABLE:
                    errorMessage = "Informasi lokasi tidak tersedia.";
                    suggestion = "\n\nPastikan:\n" +
                               "1. GPS/Location Services aktif di perangkat\n" +
                               "2. Anda berada di area dengan sinyal GPS baik\n" +
                               "3. Tidak menggunakan VPN yang memblokir lokasi";
                    break;
                case error.TIMEOUT:
                    errorMessage = "Permintaan lokasi timeout (30 detik).";
                    suggestion = "\n\nSaran:\n" +
                               "1. Pastikan GPS aktif\n" +
                               "2. Pindah ke area terbuka untuk sinyal GPS lebih baik\n" +
                               "3. Coba lagi atau gunakan pencarian manual";
                    break;
                default:
                    errorMessage = "Tidak dapat mendapatkan lokasi Anda.";
                    suggestion = "\n\nGunakan alternatif pencarian.";
            }
            
            console.error('Geolocation error:', error);
            console.error('Error details:', {
                code: error.code,
                message: error.message,
                PERMISSION_DENIED: error.PERMISSION_DENIED,
                POSITION_UNAVAILABLE: error.POSITION_UNAVAILABLE,
                TIMEOUT: error.TIMEOUT
            });
            
            alert(errorMessage + suggestion + "\n\nAtau gunakan metode pencarian alternatif.");
            
            // Suggest manual input directly
            if (confirm('Ingin menggunakan koordinat manual?\n\n' +
                       'Cara mendapatkan koordinat dari Google Maps:\n' +
                       '1. Buka Google Maps\n' +
                       '2. Klik kanan pada lokasi Anda\n' +
                       '3. Klik koordinat untuk menyalin\n' +
                       '4. Tempel di tab "Koordinat Manual"')) {
                // Switch to manual coordinates tab
                document.getElementById('coords-tab').click();
                document.getElementById('manualLat').focus();
            }
        },
        options
    );
}

// Fallback: Get location by IP address
function getLocationByIP() {
    showLoading();
    
    // Try multiple IP geolocation services
    Promise.race([
        fetch('https://ipapi.co/json/').then(r => r.json()),
        fetch('http://ip-api.com/json/').then(r => r.json())
    ])
        .then(data => {
            let lat, lng, city, region;
            
            // Handle different API response formats
            if (data.latitude && data.longitude) {
                // ipapi.co format
                lat = data.latitude;
                lng = data.longitude;
                city = data.city || 'Unknown';
                region = data.region || '';
            } else if (data.lat && data.lon) {
                // ip-api.com format
                lat = data.lat;
                lng = data.lon;
                city = data.city || 'Unknown';
                region = data.regionName || '';
            } else {
                throw new Error('Invalid response format');
            }
            
            console.log('IP-based location:', { lat, lng, city, region });
            
            // Center map on location
            map.setView([lat, lng], 12);
            
            // Add marker
            if (userMarker) {
                map.removeLayer(userMarker);
            }
            
            userMarker = L.marker([lat, lng], {
                icon: L.icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowSize: [41, 41]
                })
            }).addTo(map);
            
            userMarker.bindPopup(`<b>Lokasi Anda (perkiraan)</b><br><small>${city}${region ? ', ' + region : ''}</small>`).openPopup();
            
            // Search nearby professionals
            searchNearby(lat, lng);
            
            hideLoading();
        })
        .catch(error => {
            hideLoading();
            console.error('IP geolocation error:', error);
            
            // Show manual input as alternative
            alert('Tidak dapat mendeteksi lokasi Anda secara otomatis.\n\nSilakan gunakan salah satu metode:\n1. Cari berdasarkan nama kota (contoh: "Jakarta Pusat")\n2. Gunakan tab Koordinat Manual dengan koordinat tepat dari Google Maps');
        });
}

// Search by location name
function searchByLocation() {
    const location = document.getElementById('searchLocation').value.trim();
    
    if (!location) {
        alert('Please enter a location');
        return;
    }
    
    showLoading();
    
    // Geocode the location using Nominatim
    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(location)}&countrycodes=id&limit=1`)
        .then(response => response.json())
        .then(data => {
            if (data.length === 0) {
                hideLoading();
                alert('Lokasi tidak ditemukan. Silakan coba kata kunci pencarian lain.');
                return;
            }
            
            const result = data[0];
            const lat = parseFloat(result.lat);
            const lng = parseFloat(result.lon);
            
            // Center map on location
            map.setView([lat, lng], 14);
            
            // Add marker
            if (userMarker) {
                map.removeLayer(userMarker);
            }
            
            userMarker = L.marker([lat, lng], {
                icon: L.icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowSize: [41, 41]
                })
            }).addTo(map);
            
            userMarker.bindPopup(`<b>${result.display_name}</b>`).openPopup();
            
            // Search nearby professionals
            searchNearby(lat, lng);
            
            hideLoading();
        })
        .catch(error => {
            hideLoading();
            alert('Kesalahan saat mencari lokasi. Silakan coba lagi.');
            console.error(error);
        });
}

// Search nearby professionals using Overpass API (OpenStreetMap data)
function searchNearby(lat, lng) {
    console.log('Searching nearby for coordinates:', lat, lng);
    
    const radius = document.getElementById('searchRadius').value || document.getElementById('manualRadius').value || 5;
    const radiusMeters = radius * 1000;
    
    console.log('Search radius:', radius + ' km');
    
    showLoading();
    
    // Clear previous markers
    markersLayer.clearLayers();
    professionals = [];
    
    // Overpass API query for hospitals, clinics, and doctors
    const query = `
        [out:json][timeout:25];
        (
            node["amenity"="hospital"](around:${radiusMeters},${lat},${lng});
            node["amenity"="clinic"](around:${radiusMeters},${lat},${lng});
            node["amenity"="doctors"](around:${radiusMeters},${lat},${lng});
            node["healthcare"="psychologist"](around:${radiusMeters},${lat},${lng});
            node["healthcare"="psychiatrist"](around:${radiusMeters},${lat},${lng});
            way["amenity"="hospital"](around:${radiusMeters},${lat},${lng});
            way["amenity"="clinic"](around:${radiusMeters},${lat},${lng});
            way["amenity"="doctors"](around:${radiusMeters},${lat},${lng});
        );
        out center;
    `;
    
    fetch('https://overpass-api.de/api/interpreter', {
        method: 'POST',
        body: query
    })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            
            if (!data.elements || data.elements.length === 0) {
                alert('Tidak ada fasilitas medis ditemukan di area ini. Coba tingkatkan radius pencarian.');
                return;
            }
            
            // Process results
            data.elements.forEach(element => {
                const name = element.tags.name || 'Unnamed Facility';
                const elementLat = element.lat || element.center?.lat;
                const elementLng = element.lon || element.center?.lon;
                
                if (!elementLat || !elementLng) return;
                
                const type = element.tags.amenity || element.tags.healthcare || 'medical';
                const address = element.tags['addr:street'] || element.tags['addr:city'] || 'Alamat tidak tersedia';
                const phone = element.tags.phone || element.tags['contact:phone'] || 'Telepon tidak tersedia';
                const website = element.tags.website || element.tags['contact:website'] || null;
                
                professionals.push({
                    name: name,
                    lat: elementLat,
                    lng: elementLng,
                    type: type,
                    address: address,
                    phone: phone,
                    website: website,
                    distance: calculateDistance(lat, lng, elementLat, elementLng)
                });
                
                // Add marker
                const marker = L.marker([elementLat, elementLng], {
                    icon: L.icon({
                        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
                        iconSize: [25, 41],
                        iconAnchor: [12, 41],
                        popupAnchor: [1, -34],
                        shadowSize: [41, 41]
                    })
                }).addTo(markersLayer);
                
                const popupContent = `
                    <div style="min-width: 200px;">
                        <h6 class="mb-2"><strong>${name}</strong></h6>
                        <p class="mb-1 small"><i class="bi bi-geo-alt"></i> ${address}</p>
                        <p class="mb-1 small"><i class="bi bi-telephone"></i> ${phone}</p>
                        ${website ? `<p class="mb-1 small"><i class="bi bi-globe"></i> <a href="${website}" target="_blank">Website</a></p>` : ''}
                        <a href="https://www.google.com/maps/dir/?api=1&destination=${elementLat},${elementLng}" 
                           target="_blank" class="btn btn-sm btn-primary mt-2">
                            <i class="bi bi-arrow-right-circle"></i> Dapatkan Arah
                        </a>
                    </div>
                `;
                
                marker.bindPopup(popupContent);
            });
            
            // Sort by distance
            professionals.sort((a, b) => a.distance - b.distance);
            
            // Display results
            displayResults();
        })
        .catch(error => {
            hideLoading();
            alert('Kesalahan saat mencari profesional. Silakan coba lagi.');
            console.error(error);
        });
}

// Calculate distance between two points (Haversine formula)
function calculateDistance(lat1, lng1, lat2, lng2) {
    const R = 6371; // Earth's radius in km
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLng = (lng2 - lng1) * Math.PI / 180;
    const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
              Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
              Math.sin(dLng/2) * Math.sin(dLng/2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return R * c;
}

// Display results list
function displayResults() {
    const resultsCard = document.getElementById('resultsCard');
    const resultsList = document.getElementById('resultsList');
    const resultsCount = document.getElementById('resultsCount');
    
    if (professionals.length === 0) {
        resultsCard.style.display = 'none';
        return;
    }
    
    resultsCard.style.display = 'block';
    resultsCount.textContent = professionals.length;
    resultsList.innerHTML = '';
    
    // Show top 12 results
    const displayProfessionals = professionals.slice(0, 12);
    
    displayProfessionals.forEach((prof, index) => {
        const card = document.createElement('div');
        card.className = 'col-md-6 col-lg-4 mb-3';
        
        const typeIcon = prof.type === 'hospital' ? 'hospital' : 
                        prof.type === 'clinic' ? 'building' : 
                        prof.type === 'doctors' ? 'person-badge' : 'heart-pulse';
        
        card.innerHTML = `
            <div class="card professional-card shadow-sm h-100" onclick="focusOnMarker(${prof.lat}, ${prof.lng})">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="card-title mb-0 flex-grow-1">
                            <i class="bi bi-${typeIcon} text-primary me-1"></i>
                            ${prof.name}
                        </h6>
                    </div>
                    
                    <p class="card-text small text-muted mb-2">
                        <i class="bi bi-geo-alt"></i> ${prof.address}
                    </p>
                    
                    <p class="card-text small mb-2">
                        <i class="bi bi-telephone text-success"></i> ${prof.phone}
                    </p>
                    
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="badge bg-primary">
                            <i class="bi bi-pin-map"></i> ${prof.distance.toFixed(1)} km
                        </span>
                        <a href="https://www.google.com/maps/dir/?api=1&destination=${prof.lat},${prof.lng}" 
                           target="_blank" class="btn btn-sm btn-outline-primary" onclick="event.stopPropagation()">
                            <i class="bi bi-arrow-right-circle"></i> Arah
                        </a>
                    </div>
                    
                    ${prof.website ? `
                    <div class="mt-2">
                        <a href="${prof.website}" target="_blank" class="btn btn-sm btn-outline-info w-100" onclick="event.stopPropagation()">
                            <i class="bi bi-globe"></i> Kunjungi Website
                        </a>
                    </div>
                    ` : ''}
                </div>
            </div>
        `;
        
        resultsList.appendChild(card);
    });
}

// Focus on marker when card is clicked
function focusOnMarker(lat, lng) {
    map.setView([lat, lng], 16);
    
    // Find and open the popup for this marker
    markersLayer.eachLayer(layer => {
        if (layer.getLatLng().lat === lat && layer.getLatLng().lng === lng) {
            layer.openPopup();
        }
    });
}

// Search by manual coordinates
function searchByCoordinates() {
    const lat = parseFloat(document.getElementById('manualLat').value);
    const lng = parseFloat(document.getElementById('manualLng').value);
    
    if (isNaN(lat) || isNaN(lng)) {
        alert('Silakan masukkan nilai latitude dan longitude yang valid');
        return;
    }
    
    if (lat < -90 || lat > 90) {
        alert('Latitude harus antara -90 dan 90');
        return;
    }
    
    if (lng < -180 || lng > 180) {
        alert('Longitude harus antara -180 dan 180');
        return;
    }
    
    // Update radius from manual tab
    const radius = document.getElementById('manualRadius').value;
    document.getElementById('searchRadius').value = radius;
    
    // Center map on coordinates
    map.setView([lat, lng], 15);
    
    // Add marker
    if (userMarker) {
        map.removeLayer(userMarker);
    }
    
    userMarker = L.marker([lat, lng], {
        icon: L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        })
    }).addTo(map);
    
    userMarker.bindPopup(`<b>Lokasi Anda</b><br><small>Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}</small>`).openPopup();
    
    // Search nearby professionals
    searchNearby(lat, lng);
}

// Paste coordinates from clipboard
async function pasteCoordinates() {
    try {
        const text = await navigator.clipboard.readText();
        
        // Try to parse different coordinate formats
        // Format 1: "-6.2088, 106.8456"
        // Format 2: "-6.2088,106.8456"
        // Format 3: "lat: -6.2088, lng: 106.8456"
        
        let lat, lng;
        
        // Remove common prefixes
        let cleanText = text.replace(/lat(itude)?:\s*/gi, '').replace(/lng|lon(gitude)?:\s*/gi, ',');
        
        // Split by comma
        const parts = cleanText.split(',').map(p => p.trim());
        
        if (parts.length >= 2) {
            lat = parseFloat(parts[0]);
            lng = parseFloat(parts[1]);
            
            if (!isNaN(lat) && !isNaN(lng)) {
                document.getElementById('manualLat').value = lat;
                document.getElementById('manualLng').value = lng;
                alert('Koordinat berhasil ditempel!');
            } else {
                alert('Tidak dapat membaca koordinat dari clipboard. Silakan tempel dalam format: "-6.2088, 106.8456"');
            }
        } else {
            alert('Tidak dapat membaca koordinat. Silakan tempel dalam format: "-6.2088, 106.8456"');
        }
    } catch (err) {
        alert('Gagal membaca dari clipboard. Silakan tempel secara manual atau periksa izin browser.');
        console.error('Clipboard error:', err);
    }
}

// Test location detection
function testLocation() {
    console.clear();
    console.log('%c=== LOCATION TEST ===', 'color: #e74a3b; font-weight: bold; font-size: 16px;');
    
    // Test 1: Check geolocation support
    console.log('\n%c[Test 1] Geolocation Support:', 'color: #4e73df; font-weight: bold;');
    if (navigator.geolocation) {
        console.log('✅ Geolocation is SUPPORTED');
    } else {
        console.log('❌ Geolocation is NOT SUPPORTED');
    }
    
    // Test 2: Check HTTPS/localhost
    console.log('\n%c[Test 2] Protocol:', 'color: #4e73df; font-weight: bold;');
    const protocol = window.location.protocol;
    if (protocol === 'https:' || window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
        console.log('✅ Protocol is safe for geolocation:', protocol);
    } else {
        console.log('⚠️ Protocol might block geolocation:', protocol);
        console.log('   Geolocation requires HTTPS or localhost');
    }
    
    // Test 3: Try getting position
    console.log('\n%c[Test 3] Getting Current Position:', 'color: #4e73df; font-weight: bold;');
    console.log('Requesting location... (Please allow if browser asks)');
    
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            position => {
                console.log('✅ SUCCESS! Location detected:');
                console.log('   Latitude:', position.coords.latitude);
                console.log('   Longitude:', position.coords.longitude);
                console.log('   Accuracy:', Math.round(position.coords.accuracy), 'meters');
                console.log('   Timestamp:', new Date(position.timestamp).toLocaleString());
                
                // Show on map
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                map.setView([lat, lng], 15);
                
                if (userMarker) {
                    map.removeLayer(userMarker);
                }
                
                userMarker = L.marker([lat, lng], {
                    icon: L.icon({
                        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
                        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
                        iconSize: [25, 41],
                        iconAnchor: [12, 41],
                        popupAnchor: [1, -34],
                        shadowSize: [41, 41]
                    })
                }).addTo(map);
                
                userMarker.bindPopup(`<b>✅ Test Successful!</b><br>
                    Lat: ${lat.toFixed(6)}<br>
                    Lng: ${lng.toFixed(6)}<br>
                    Accuracy: ±${Math.round(position.coords.accuracy)}m`).openPopup();
                
                alert('✅ Location test successful!\n\nYour coordinates:\n' + 
                      'Latitude: ' + lat.toFixed(6) + '\n' +
                      'Longitude: ' + lng.toFixed(6) + '\n' +
                      'Accuracy: ±' + Math.round(position.coords.accuracy) + 'm\n\n' +
                      'Check browser console for details (F12)');
            },
            error => {
                console.log('❌ ERROR getting location:');
                console.log('   Error Code:', error.code);
                console.log('   Error Message:', error.message);
                
                let reason = '';
                switch(error.code) {
                    case 1:
                        reason = 'PERMISSION_DENIED - User denied location access';
                        console.log('   💡 Solution: Click the location icon in address bar and allow location access');
                        break;
                    case 2:
                        reason = 'POSITION_UNAVAILABLE - Location information unavailable';
                        console.log('   💡 Solution: Check your device GPS/location services are enabled');
                        break;
                    case 3:
                        reason = 'TIMEOUT - Request timed out';
                        console.log('   💡 Solution: Try again or check internet connection');
                        break;
                }
                
                console.log('%c\n=== TROUBLESHOOTING ===', 'color: #f6c23e; font-weight: bold;');
                console.log('1. Click the location/lock icon in your browser address bar');
                console.log('2. Make sure "Location" permission is set to "Allow"');
                console.log('3. Check your device location services are enabled');
                console.log('4. Try refreshing the page');
                console.log('5. If still failing, use "Manual Coordinates" or "Search by City"');
                
                alert('❌ Location test failed!\n\n' + reason + '\n\n' +
                      'Please:\n' +
                      '1. Enable location permission in browser\n' +
                      '2. Enable device GPS/location services\n' +
                      '3. Use "Manual Coordinates" or "Search by City" as alternative\n\n' +
                      'Check browser console (F12) for more details');
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    }
    
    // Test 4: Current map state
    console.log('\n%c[Test 4] Current Map State:', 'color: #4e73df; font-weight: bold;');
    const center = map.getCenter();
    console.log('   Map Center:', center.lat.toFixed(6), ',', center.lng.toFixed(6));
    console.log('   Zoom Level:', map.getZoom());
    
    console.log('\n%c=== END TEST ===\n', 'color: #e74a3b; font-weight: bold; font-size: 16px;');
}

// Update map center display
function updateMapCenter() {
    const center = map.getCenter();
    const centerText = `${center.lat.toFixed(4)}, ${center.lng.toFixed(4)} (Zoom: ${map.getZoom()})`;
    document.getElementById('mapCenter').textContent = centerText;
}

// Set location manually by clicking on map
let manualLocationMode = false;
let tempMarker = null;

function setLocationManually() {
    manualLocationMode = true;
    document.getElementById('locationHint').style.display = 'block';
    document.getElementById('searchHereBtn').style.display = 'inline-block';
    
    // Change cursor
    document.getElementById('map').style.cursor = 'crosshair';
    
    alert('Mode Penentuan Lokasi Manual:\n\n' +
          '1. Klik pada peta di lokasi Anda yang tepat\n' +
          '2. Marker biru akan muncul di posisi yang Anda klik\n' +
          '3. Klik tombol "Cari di Lokasi Ini" untuk mencari\n\n' +
          'Tips: Gunakan zoom (scroll) untuk melihat lokasi lebih detail');
}

function searchCurrentMapCenter() {
    const center = map.getCenter();
    const lat = center.lat;
    const lng = center.lng;
    
    // Add/update user marker at center
    if (userMarker) {
        map.removeLayer(userMarker);
    }
    
    userMarker = L.marker([lat, lng], {
        icon: L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        })
    }).addTo(map);
    
    userMarker.bindPopup(`<b>Lokasi Anda</b><br><small>Lat: ${lat.toFixed(6)}<br>Lng: ${lng.toFixed(6)}</small>`).openPopup();
    
    // Search nearby
    searchNearby(lat, lng);
    
    // Reset manual mode
    manualLocationMode = false;
    document.getElementById('locationHint').style.display = 'none';
    document.getElementById('map').style.cursor = '';
}

// Initialize map when page loads
document.addEventListener('DOMContentLoaded', function() {
    initMap();
    
    // Add click handler for manual location
    map.on('click', function(e) {
        if (manualLocationMode) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;
            
            // Remove temporary marker if exists
            if (tempMarker) {
                map.removeLayer(tempMarker);
            }
            
            // Add temporary marker
            tempMarker = L.marker([lat, lng], {
                icon: L.icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowSize: [41, 41]
                })
            }).addTo(map);
            
            tempMarker.bindPopup(`<b>Lokasi yang Dipilih</b><br><small>Lat: ${lat.toFixed(6)}<br>Lng: ${lng.toFixed(6)}</small><br><br><button class="btn btn-sm btn-success mt-2" onclick="confirmManualLocation(${lat}, ${lng})">Konfirmasi & Cari</button>`).openPopup();
            
            // Center map on clicked location
            map.setView([lat, lng], map.getZoom());
            updateMapCenter();
        }
    });
    
    // Update map center when moved
    map.on('moveend', updateMapCenter);
    updateMapCenter();
    
    // Show tips
    console.log('%c=== Pencari Profesional Kesehatan Mental ===', 'color: #4e73df; font-weight: bold; font-size: 14px;');
    console.log('%c3 Cara Menentukan Lokasi:', 'color: #1cc88a; font-weight: bold; font-size: 12px;');
    console.log('1. GPS Otomatis - Klik "GPS Otomatis"');
    console.log('2. Klik Peta - Klik "Tentukan Lokasi di Peta", lalu klik pada peta');
    console.log('3. Koordinat Manual - Gunakan tab "Koordinat Manual"');
    console.log('%c========================================', 'color: #4e73df;');
});

function confirmManualLocation(lat, lng) {
    // Remove temp marker
    if (tempMarker) {
        map.removeLayer(tempMarker);
        tempMarker = null;
    }
    
    // Add user marker
    if (userMarker) {
        map.removeLayer(userMarker);
    }
    
    userMarker = L.marker([lat, lng], {
        icon: L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        })
    }).addTo(map);
    
    userMarker.bindPopup(`<b>Lokasi Anda</b><br><small>Lat: ${lat.toFixed(6)}<br>Lng: ${lng.toFixed(6)}</small>`).openPopup();
    
    // Search nearby
    searchNearby(lat, lng);
    
    // Reset manual mode
    manualLocationMode = false;
    document.getElementById('locationHint').style.display = 'none';
    document.getElementById('map').style.cursor = '';
}
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
?>
