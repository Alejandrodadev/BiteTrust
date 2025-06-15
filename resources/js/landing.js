
// Carga primero Alpine y el DOM
document.addEventListener('alpine:init', () => {
    Alpine.data('landingFilters', () => ({
        openFilters: false,

        // Método que lanza la geolocalización
        geolocate() {
            if (!navigator.geolocation) {
                alert('Tu navegador no soporta geolocalización');
                return;
            }
            navigator.geolocation.getCurrentPosition(
                pos => {
                    const lat = pos.coords.latitude.toFixed(7);
                    const lng = pos.coords.longitude.toFixed(7);
                    const params = new URLSearchParams(window.location.search);
                    params.set('lat', lat);
                    params.set('lng', lng);
                    window.location.search = params.toString();
                },
                () => {
                    alert('Necesitamos permiso para obtener tu ubicación.');
                }
            );
        },

        init() {
            // Inicializamos Google Places Autocomplete
            const input = document.getElementById('placeInput');
            const placeIdField = document.getElementById('place_id');
            if (input && window.google?.maps?.places) {
                const autocomplete = new google.maps.places.Autocomplete(input);
                autocomplete.addListener('place_changed', () => {
                    const place = autocomplete.getPlace();
                    if (place.place_id) {
                        placeIdField.value = place.place_id;
                    }
                });
            }
        }
    }));
});
