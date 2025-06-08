document.addEventListener('alpine:init', () => {
    Alpine.data('toggleReview', (comment) => ({
        open: false,
        short: comment.length > 250 ? comment.substring(0, 250) + '...' : comment,
        full: comment,
        hasMore: comment.length > 250,
        toggle() {
            this.open = !this.open;
        }
    }));
});
