import { createRouter, createWebHistory } from 'vue-router'
import { useUserStore } from '@/stores/user'
import Home from '../views/Home.vue'
import Register from '../views/Auth/Register.vue'
import Login from '../views/Auth/Login.vue'
import VotingDistrict from '../views/VotingDistrict/VotingDistrict.vue'
import VotingDistrictForm from '../views/VotingDistrict/VotingDistrictForm.vue'
import Election from '../views/Election/Election.vue'
import ElectionForm from '../views/Election/ElectionForm.vue'
import Candidate from '../views/Candidate/Candidate.vue'
import CandidateForm from '../views/Candidate/CandidateForm.vue'
import VoteForm from '../views/Vote/VoteForm.vue'
import VoteVerify from '../views/Vote/VoteVerify.vue'

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
    {
        path: '/voting-district',
        name: 'VotingDistrict',
        component: VotingDistrict
    },
    {
        path: '/voting-district/form/:id?',
        name: 'VotingDistrictForm',
        component: VotingDistrictForm
    },
    {
        path: '/candidate',
        name: 'Candidate',
        component: Candidate
    },
    {
        path: '/candidate/form/:id?',
        name: 'CandidateForm',
        component: CandidateForm
    },
    {
        path: '/election',
        name: 'Election',
        component: Election
    },
    {
        path: '/election/form/:id?',
        name: 'ElectionForm',
        component: ElectionForm
    },
    {
        path: '/election/:id/vote',
        name: 'VoteForm',
        component: VoteForm
    },
    {
        path: '/vote/verify/:id?',
        name: 'VoteVerify',
        component: VoteVerify
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
