import { ref, computed, watch } from 'vue'
import { defineStore } from 'pinia'
import axios from 'axios'
import router from '@/router'

export const useUserStore = defineStore('user', () => {
        const user = ref({
            id: null    
        })

        const isGuest = computed(() => {
            return user.value.id === null
        })

        watch(user, (user) => {
            if(user.id === null) {
                axios.defaults.headers.common['Authorization'] = null;
            }
            else {
                axios.defaults.headers.common['Authorization'] = 'Bearer '+user.token;
            }
        })

        function logout() {
            axios.post('/logout', {})
                .then(() => {
                    user.value = {
                            id: null  
                        }
                    router.push({name: 'Login'})
                })
                .catch(error => {
                    console.log(error.response.data)
                })
        }

        return { user, logout, isGuest }
    }, 
    //Make Store persitent when browser refreshes
    {
        persist: true
    }
)