import Alpine from 'alpinejs';
import './bootstrap';
import 'photoswipe/style.css';
import './components/lightbox.js';
import './components/analysisButton.js';
import './components/reviewToggle.js';
import './landing.js';
import compareSelector from './components/compareSelector';

window.Alpine = Alpine;
Alpine.data('compareSelector', compareSelector);
Alpine.start();
