<template>
    <v-row>
        <v-col>
            <h1>Welcome {{ userStore.user.name }}</h1>
    
            <v-row v-if='elections.length > 0'>
                <v-col>
                    <h1>Elections Happening</h1>
                </v-col>
            </v-row>

            <v-row>
                <v-col cols='12' md='6' lg='3' v-for='election of elections' :key='election.id'>
                    <v-card>
                        <v-card-title class='text-center'>
                            Election
                        </v-card-title>

                        <v-card-text>
                            Started on {{ election.started }}<br/>
                            Ending on {{ election.end_date }}
                        </v-card-text>

                        <v-card-actions class="pt-0 text-center">
                            <v-btn 
                                color="primary" 
                                class='mx-auto' 
                                variant="outlined" 
                                @click="router.push('election/'+election.id+'/vote')"
                                block
                            >
                                Vote Now
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-col>
            </v-row>
        </v-col>
    </v-row>
</template>

<script setup>
    import { ref, onMounted } from 'vue'
    import axios from 'axios'
    import { useUserStore } from '@/stores/user'
    import { useSnackbarStore } from '@/stores/snackbar'
    import router from '@/router'

    const userStore = useUserStore()
    const snackbarStore = useSnackbarStore()

    const elections = ref([])
    
    onMounted(() => {
            loadElections()
        })

    function loadElections()
    {
        axios.get('/api/elections/active')
            .then(response => {
                elections.value = response.data
            })
            .catch(error => {
                const message = "Failed to load Elections. Reasons: "+error.response.data.message
                snackbarStore.showSnackBar(message, 'error', 5000)
            })
    }
</script>

<style scoped>

</style>