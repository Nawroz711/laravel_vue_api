import './bootstrap';
import { createApp } from 'vue'
import App from './App.vue'
import router from '../js/router';
// import i18n from './src/i18n'
import PrimeVue from 'primevue/config'
// import 'bootstrap' from 'bootstrap'
// import 'primevue/resources/themes/lara-light-indigo/theme.css'
// import 'primevue/resources/primevue.min.css'
// import 'primeicons/primeicons.css'



createApp(App).use(router).use(PrimeVue).mount("#app")