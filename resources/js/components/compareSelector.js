export default function compareSelector(selectedInitial) {
    return {
        query: '',
        suggestions: [],
        selected: selectedInitial,
        add(r) {
            if (this.selected.length >= 3) return;
            if (!this.selected.find(x => x.id === r.id)) {
                this.selected.push(r);
            }
            this.query = '';
            this.suggestions = [];
        },
        remove(i) {
            this.selected.splice(i, 1);
        },
        search() {
            if (this.query.length < 2) {
                this.suggestions = [];
                return;
            }
            fetch(`/restaurants/search?q=${encodeURIComponent(this.query)}`)
                .then(res => res.json())
                .then(js => this.suggestions = js);
        }
    }
}
