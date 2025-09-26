document.addEventListener('DOMContentLoaded', () => {

    function initMap() {
        console.log('Initializing the map...');

        // Check if the map container exists
        var mapContainer = document.getElementById('map');
        if (!mapContainer) {
            console.error('Map container not found.');
            return;
        }

        // Center position of the map
        var centerPosition = {
            lat: 46.75631065177212,
            lng: 4.71961105713271

        };
        // Initialize the map with custom styles
        var map = new google.maps.Map(mapContainer, {
            center: centerPosition, // Set center
            mapTypeControl: false, // Disable plan/satellite toggle
            zoomControl: false, // Disable zoom controls
            streetViewControl: false, // Disable Street View control
            fullscreenControl: false,
            zoom: 12,
            styles: [{
                "elementType": "geometry",
                "stylers": [{
                    "color": "#f5f5f5"
                }]
            },
            {
                "elementType": "labels.icon",
                "stylers": [{
                    "visibility": "off"
                }]
            },
            {
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#616161"
                }]
            },
            {
                "elementType": "labels.text.stroke",
                "stylers": [{
                    "color": "#f5f5f5"
                }]
            },
            {
                "featureType": "administrative.land_parcel",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#bdbdbd"
                }]
            },
            {
                "featureType": "poi",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#eeeeee"
                }]
            },
            {
                "featureType": "poi",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#757575"
                }]
            },
            {
                "featureType": "poi.park",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#e5e5e5"
                }]
            },
            {
                "featureType": "poi.park",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#9e9e9e"
                }]
            },
            {
                "featureType": "road",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#ffffff"
                }]
            },
            {
                "featureType": "road.arterial",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#CBAE70"
                }]
            },
            {
                "featureType": "road.highway",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#CBAE70"
                }]
            },
            {
                "featureType": "road.highway",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#616161"
                }]
            },
            {
                "featureType": "road.local",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#9e9e9e"
                }]
            },
            {
                "featureType": "transit.line",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#e5e5e5"
                }]
            },
            {
                "featureType": "transit.station",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#eeeeee"
                }]
            },
            {
                "featureType": "water",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#3D4C51"
                }]
            },
            {
                "featureType": "water",
                "elementType": "geometry.fill",
                "stylers": [{
                    "color": "#3D4C51"
                }]
            },
            {
                "featureType": "water",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#9e9e9e"
                }]
            }
            ]
        });

        // Custom red marker icon
        var markerIcon = {
            scaledSize: new google.maps.Size(40, 40), // Scaled size (optional)
        };

        // Add a marker at the center of the map

        const marker = new google.maps.Marker({
            position: centerPosition,
            map,
            icon: markerIcon, // works here
            title: 'Cabinet dentaire de la côte chalonnaise'
        });

        // --- Re-center control (custom button) ---
        (function addRecenterControl() {
            const initialCenter = { ...centerPosition }; // keep original center
            const initialZoom = 12;                    // change if you want a different default

            const controlDiv = document.createElement('div');
            controlDiv.style.padding = '8px';

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'gm-recenter';
            btn.setAttribute('aria-label', 'Recenter map');
            btn.innerHTML = `
                <span class="icon iconify" data-icon="material-symbols-light:recenter-rounded"></span>
                <span>Recentrer</span>`;
            btn.onclick = () => {
                map.panTo(initialCenter);
                map.setZoom(initialZoom);
                // tiny bounce to draw attention
                marker.setAnimation(google.maps.Animation.BOUNCE);
                setTimeout(() => marker.setAnimation(null), 700);
            };

            controlDiv.appendChild(btn);
            map.controls[google.maps.ControlPosition.TOP_LEFT].push(controlDiv);
        })();


        console.log('Map initialized with center marker:', map);
    }

    // Check if the Google Maps script has loaded and then run initMap
    window.addEventListener('load', function () {
        if (typeof google !== 'undefined' && typeof google.maps !== 'undefined') {
            console.log('Google Maps API loaded successfully.');
            initMap();
        } else {
            console.error('Google Maps API not loaded.');
        }
    });
});