import './styles/app.css';

// start the Stimulus application
import './bootstrap';
import $ from 'jquery';
import 'bootstrap';
import { toggleActiveClassNav } from './nav';

window.addEventListener('load', () => {
    toggleActiveClassNav();
})

// Toggle Class NavBar
