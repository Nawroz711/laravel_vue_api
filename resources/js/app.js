import './bootstrap';
import { createApp } from 'vue'
import App from './App.vue'
import router from '../js/router';
// import i18n from './src/i18n'
import PrimeVue from 'primevue/config'
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import 'primevue/resources/themes/lara-light-indigo/theme.css'
import 'primevue/resources/primevue.min.css'



createApp(App).use(router).use(PrimeVue).mount("#app")