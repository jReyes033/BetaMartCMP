import { createRouter, createWebHashHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import ProductView from '@/views/ProductView.vue'
import UserView from '@/views/UserView.vue'  // Import the UserView component

const routes = [
  {
    path: '/',
    name: 'home',
    component: HomeView
  },
  {
    path: '/about',
    name: 'about',
    component: () => import(/* webpackChunkName: "about" */ '../views/AboutView.vue')
  },
  {
    path: '/products',
    name: 'Product List',
    component: ProductView
  },
  {
    path: '/user',
    name: 'User List',
    component: UserView  // Use the imported UserView component
  }
]

const router = createRouter({
  history: createWebHashHistory(),
  routes
})

export default router