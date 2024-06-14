<template>
    <v-responsive class="border rounded" min-height="100vh">
        <v-app>
            <v-app-bar>
                <v-app-bar-title class='text-blue-grey-lighten-2'>
                    <RouterLink to="/" class='menuItem text-blue-grey-lighten-2'>
                        <strong>
                            Blockchain Voting
                        </strong>
                    </RouterLink>
                </v-app-bar-title>

                <template v-slot:append class='menuWraper'>
                    <RouterLink to="/register" v-if='userStore.isGuest' class='menuItem text-blue-grey-lighten-2'>
                        Register
                    </RouterLink>
                    
                    <RouterLink to="/login" v-if='userStore.isGuest' class='menuItem text-blue-grey-lighten-2'>
                        Login
                    </RouterLink>
                    
                    <RouterLink to="/logout" @click.prevent='logout' v-if='!userStore.isGuest' class='menuItem text-blue-grey-lighten-2'>
                        Logout
                    </RouterLink>
                </template>
            </v-app-bar>

            <v-main>
                <v-container>
                    <RouterView />
                </v-container>
            </v-main>
        </v-app>
  </v-responsive>

</template>

<script setup>
    import { RouterLink, RouterView } from 'vue-router'
    import { useUserStore } from '@/stores/user'

    const userStore = useUserStore()
    
    function logout()
    {
        userStore.logout()
    }
</script>

<style scoped>
    .menuItem
    {
        margin-right: 1rem;
    }
</style>
