import { createRouter, createWebHistory } from 'vue-router';

import UserPage from './src/components/Pages/User.vue';
import Board from './src/components/Pages/Board.vue';
import Home from './src/components/Pages/LandingPage.vue';

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home
  },
  {
    path: '/User',
    name: 'User',
    component: UserPage
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;