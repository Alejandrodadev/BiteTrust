// resources/js/landing.js

document.addEventListener('alpine:init', () => {
    Alpine.data('landingFilters', () => ({
        openFilters: false,
        showExistsMessage: false,
        existingUrl: '',

        // Geolocalización
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

        // Init de Alpine
        init() {
            const input        = document.getElementById('placeInput');
            const placeIdField = document.getElementById('place_id');
            const nameField    = document.getElementById('place_name');
            const addrField    = document.getElementById('place_address');
            const latField     = document.getElementById('place_lat');
            const lngField     = document.getElementById('place_lng');

            if (!input || !window.google?.maps?.places) return;

            const autocomplete = new google.maps.places.Autocomplete(input);
            autocomplete.addListener('place_changed', () => {
                const place = autocomplete.getPlace();
                if (!place.place_id) return;

                // 1) Comprueba si existe en BD
                fetch(`/restaurants/check?place_id=${place.place_id}`)
                    .then(r => r.json())
                    .then(json => {
                        if (json.exists) {
                            // ya existe → muestra mensaje y link
                            this.showExistsMessage = true;
                            this.existingUrl       = json.url;
                            // limpia campos
                            placeIdField.value = '';
                            nameField.value    = '';
                            addrField.value    = '';
                            latField.value     = '';
                            lngField.value     = '';
                        } else {
                            // no existe → oculta mensaje y vuelca datos
                            this.showExistsMessage = false;
                            this.existingUrl       = '';
                            placeIdField.value     = place.place_id;
                            nameField.value        = place.name || '';
                            addrField.value        = place.formatted_address || '';
                            latField.value         = place.geometry.location.lat().toFixed(7);
                            lngField.value         = place.geometry.location.lng().toFixed(7);
                        }
                    })
                    .catch(() => {
                        // fallo de red: al menos pon el place_id
                        this.showExistsMessage = false;
                        placeIdField.value     = place.place_id;
                    });
            });
        }
    }));
});
