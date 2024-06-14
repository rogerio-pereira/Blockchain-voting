import { createRouter, createWebHistory } from 'vue-router'
import { useUserStore } from '@/stores/user'
import Home from '../views/Home.vue'
import Register from '../views/Auth/Register.vue'
import Login from '../views/Auth/Login.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'Home',
      component: Home
    },
    {
        path: '/register',
        name: 'Register',
        component: Register
    },
    {
        path: '/login',
        name: 'Login',
        component: Login
    },
  ]
})

router.beforeEach(async (to, from) => {
        const userStore = useUserStore()
        const unprotectedRoutes = [
            'Login',
            'Register'
        ]
        const routeName = to.name

        if (userStore.isGuest && !unprotectedRoutes.includes(routeName)) {
            return { name: 'Login' }
        }
    })

export default router
