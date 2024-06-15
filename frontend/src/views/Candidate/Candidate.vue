<template>
    <v-card :loading="loading ? 'info' : null" :key='renderKey'>
        <v-card-item>
            <v-card-title class='text-center'>
                Candidates<br/>
            </v-card-title>

           <v-card-text>
                <v-row>
                    <v-col class='text-center'>
                        <RouterLink to="/candidate/form" class='mt-4'>
                            <v-btn color='primary'>
                                Create
                            </v-btn>
                        </RouterLink>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col>
                        <v-data-table 
                            :items="candidates"
                            :headers="headers"
                        >
                            <template v-slot:item.actions="{ item }">

                                <RouterLink :to="'/candidate/form/'+item.id" class='mt-4'>
                                    <v-btn 
                                        density="compact"
                                        color='primary' 
                                        variant="text"
                                        icon="mdi-pencil" 
                                        title='Edit'
                                    />
                                </RouterLink>

                                <v-btn 
                                    density="compact"
                                    color='error' 
                                    variant="text"
                                    icon="mdi-trash-can-outline" 
                                    title='Delete'
                                    @click='deleteItem(item.id)'
                                />
                            </template>
                        </v-data-table>
                    </v-col>
                </v-row>
            </v-card-text>
        </v-card-item>
    </v-card>
</template>

<script setup>
    import { ref, onMounted, computed } from 'vue'
    import axios from 'axios'
    import { useSnackbarStore } from '@/stores/snackbar'

    const snackbarStore = useSnackbarStore()  

    const candidates = ref([])
    const loading = ref(false)
    const renderKey = ref(0)

    const headers = [
            { title: 'Id', value: 'id' },    
            { title: 'Name', value: 'name' },    
            { title: 'Political Party', value: 'political_party' },    
            { title: 'Actions', value: 'actions', width: '100' },    
        ]

    onMounted(() => {
            loadDistricts()
        })

    function loadDistricts() 
    {
        loading.value = true

        axios.get('/api/candidates')
            .then(response => {
                candidates.value = response.data
            })
            .catch(error => {

            })
            .finally(() => {
                loading.value = false
                renderKey.value++
            })
    }

    function deleteItem(id)
    {
        loading.value = true
        snackbarStore.showSnackBar('Deleting, please wait.', 'info', -1)

        axios.delete('/api/candidates/'+id)
            .then(response => {
                snackbarStore.hideSnackBar()
                loadDistricts()
            })
            .catch(error => {
                const message = "Failed to delete. Reasons: "+error.response.data.message
                snackbarStore.showSnackBar(message, 'error', 5000)
            })
            .finally(() => {
                loading.value = false
            })
    }
</script>

<style scoped>

</style>