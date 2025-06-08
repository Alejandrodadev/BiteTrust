document.addEventListener('alpine:init', () => {
    Alpine.data('iaAnalysis', (url, isGuest) => ({
        loadingIA: false,
        ia: null,
        showInfo: false,

        analizar() {
            if (isGuest) {
                this.showInfo = true;
                return;
            }

            this.loadingIA = true;
            this.ia = null;

            fetch(url)
                .then(r => r.json())
                .then(data => this.ia = data)
                .catch(() => this.showInfo = true)
                .finally(() => this.loadingIA = false);
        }
    }));
});
