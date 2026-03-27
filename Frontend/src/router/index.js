import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    redirect: '/login'
  },
  {
    path: '/login',
    name: 'Login',
    component: () => import('@/views/LoginView.vue')
  },
  {
    path: '/dashboard',
    name: 'Dashboard',
    component: () => import('@/views/DashboardView.vue')
  },
  {
    path: '/kendaraan',
    name: 'Kendaraan',
    component: () => import('@/views/KendaraanView.vue')
  },
  {
    path: '/monitoring-kendaraan',
    name: 'MonitoringKendaraan',
    component: () => import('@/views/MonitoringKendaraanView.vue')
  },
  {
    path: '/monitoring-kendaraan/:id',
    name: 'VehicleDetail',
    component: () => import('@/views/VehicleDetailView.vue')
  },
  {
    path: '*',
    redirect: '/dashboard'
  }
]

const router = new VueRouter({
  mode: 'history',
  base: '/',
  routes
})

export default router
