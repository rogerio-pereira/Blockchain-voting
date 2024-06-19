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
                    <!-- Guest -->
                    <span v-if='userStore.isGuest'>
                        <RouterLink to="/register" class='menuItem text-blue-grey-lighten-2'>
                            Register
                        </RouterLink>
                        
                        <RouterLink to="/login" class='menuItem text-blue-grey-lighten-2'>
                            Login
                        </RouterLink>
                    </span>
                    
                    <!-- Logged in -->
                    <span v-if='!userStore.isGuest'>
                        <RouterLink to="/vote/verify" class='menuItem text-blue-grey-lighten-2'>
                            Verify Vote
                        </RouterLink>

                        <span v-if="userStore.user.role.toLowerCase() == 'admin'">
                            <admin-menu />
                        </span>
                        <span v-else>
                            <voter-menu />
                        </span>

                        <RouterLink to="/logout" @click.prevent='logout' class='menuItem text-blue-grey-lighten-2'>
                            Logout
                        </RouterLink>
                    </span>
                </template>
            </v-app-bar>

            <v-main>
                <v-container>
                    <RouterView />
                </v-container>
            </v-main>
        </v-app>
    </v-responsive>

    <snackbar />
</template>

<script setup>
    import { RouterLink, RouterView } from 'vue-router'
    import { useUserStore } from '@/stores/user'
    import AdminMenu from '@/components/layout/menu/AdminMenu.vue'
    import VoterMenu from '@/components/layout/menu/VoterMenu.vue'
    import Snackbar from '@/components/layout/Snackbar.vue'

    const userStore = useUserStore()
    
    function logout()
    {
        userStore.logout()
    }
</script>

<style>
    .menuItem
    {
        margin-right: 1rem !important;
        text-decoration: none !important;
    }

    ul.validationErrors
    {
        margin: 0px;
        padding: 0px;
        list-style-type: none;
    }

    ul.validationErrors li
    {
        margin: 0px;
        padding: 0px;
        margin-left: 1em;
    }
</style>
