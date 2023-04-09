import 'vuetify/styles'
import { createRouter, createWebHistory } from 'vue-router';
import { createApp } from 'vue';
import App from './components/App.vue';
import Fruits from './components/Fruits';
import FavoriteFruits from './components/FavoriteFruits';
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-icons/font/bootstrap-icons.css';
import '@mdi/font/css/materialdesignicons.css'

const vuetify = createVuetify({
  components,
  directives,
})
const routes = [
    {
      path: '/',
      component: Fruits,
    },
    {
      path: '/favorites',
      component: FavoriteFruits,
    },
  ];
  
  const router = createRouter({
    history: createWebHistory(),
    routes,
  });

createApp(App)
    .use(vuetify)
    .use(router)
    .mount('#app')