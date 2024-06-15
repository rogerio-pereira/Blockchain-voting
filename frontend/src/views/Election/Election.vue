<template>
    <v-card :loading="loading ? 'info' : null" :key='renderKey'>
        <v-card-item>
            <v-card-title class='text-center'>
                Elections<br/>
            </v-card-title>

           <v-card-text>
                <v-row>
                    <v-col class='text-center'>
                        <RouterLink to="/election/form" class='mt-4'>
                            <v-btn color='primary'>
                                Create
                            </v-btn>
                        </RouterLink>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col>
                        <v-data-table 
                            :items="elections"
                            :headers="headers"
                        >
                            <template v-slot:item.actions="{ item }">

                                <v-btn 
                                    density="compact"
                                    color='info' 
                                    variant="text"
                                    icon="mdi-town-hall" 
                                    title='Districts'
                                    @click='showDistrictDialog(item)'
                                />

                                <RouterLink :to="'/election/form/'+item.id" class='mt-4'>
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

    <districts-dialog 
        :showDialog='districtDialogShow'
        :electionId='districtDialogElectionId'
        :districts='districtDialogDistricts'
        @closeDialog='closeDistrictDialog'
    />
</template>

<script setup>
    import { ref, onMounted, computed } from 'vue'
    import axios from 'axios'
    import { useSnackbarStore } from '@/stores/snackbar'
    import DistrictsDialog from '@/views/Election/DistrictsDialog.vue'

    const snackbarStore = useSnackbarStore()  

    const elections = ref([])
    const loading = ref(false)
    const renderKey = ref(0)

    const districtDialogShow = ref(false)
    const districtDialogElectionId = ref(null)
    const districtDialogDistricts = ref([])

    const headers = [
            { title: 'Id', value: 'id' },
            { title: 'Start Date', value: 'start_date' },
            { title: 'End Date', value: 'end_date'},
            { title: 'Started', value: 'started'},
            { title: 'Ended', value: 'ended'},
            { title: 'Actions', value: 'actions', width: '150' },
        ]

    onMounted(() => {
            loadElections()
        })

    function loadElections() 
    {
        loading.value = true

        axios.get('/api/elections')
            .then(response => {
                elections.value = response.data
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

        axios.delete('/api/elections/'+id)
            .then(response => {
                snackbarStore.hideSnackBar()
                loadElections()
            })
            .catch(error => {
                const message = "Failed to delete. Reasons: "+error.response.data.message
                snackbarStore.showSnackBar(message, 'error', 5000)
            })
            .finally(() => {
                loading.value = false
            })
    }

    function showDistrictDialog(election)
    {
        districtDialogElectionId.value = election.id
        districtDialogDistricts.value = election.voting_districts
        districtDialogShow.value = true
    }
    
    function closeDistrictDialog()
    {
        districtDialogElectionId.value = null
        districtDialogDistricts.value = []
        districtDialogShow.value = false
    }
</script>

<style scoped>

</style>