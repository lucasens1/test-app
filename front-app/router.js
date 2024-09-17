import { createRouter, createWebHistory } from 'vue-router';

import UserPage from './src/components/Pages/User.vue';
import Board from './src/components/Pages/Board.vue';
import Home from './src/components/Pages/LandingPage.vue';
import Register from './src/components/Pages/Register.vue';
import Login from './src/components/Pages/Login.vue';
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
  },
  {
    path: '/Board',
    name: 'Board',
    component: Board
  },
  {
    path: '/Register',
    name: 'Register',
    component: Register
  },
  {
    path: '/Login',
    name: 'Login',
    component: Login
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;