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
                                <!-- Edit -->
                                <RouterLink :to="'/election/form/'+item.id" class='mt-4'>
                                    <v-btn 
                                        density="compact"
                                        color='primary' 
                                        variant="text"
                                        icon="mdi-pencil" 
                                        title='Edit'
                                    />
                                </RouterLink>

                                <!-- Delete -->
                                <v-btn 
                                    density="compact"
                                    color='error' 
                                    variant="text"
                                    icon="mdi-trash-can-outline" 
                                    title='Delete'
                                    class='mr-4'
                                    @click='deleteItem(item.id)'
                                />

                                <!-- Voting Districts -->
                                <v-btn 
                                    density="compact"
                                    color='secondary' 
                                    variant="text"
                                    icon="mdi-town-hall" 
                                    title='Districts'
                                    @click='showDistrictDialog(item)'
                                />

                                <!-- Candidates -->
                                <v-btn 
                                    density="compact"
                                    color='secondary' 
                                    variant="text"
                                    icon="mdi-account-tie" 
                                    title='Candidates'
                                    class='mr-4'
                                    @click='showCandidateDialog(item)'
                                />

                                <!-- Start Election -->
                                <v-btn 
                                    density="compact"
                                    color='success' 
                                    variant="text"
                                    icon="mdi-play-circle" 
                                    title='Start'
                                    v-if='item.started == null'
                                    @click='startElection(item)'
                                />

                                <!-- Stop Election -->
                                <v-btn 
                                    density="compact"
                                    color='error' 
                                    variant="text"
                                    icon="mdi-stop-circle" 
                                    title='Stop'
                                    v-if='item.started != null && item.ended == null'
                                    @click='endElection(item)'
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


    <candidates-dialog 
        :showDialog='candidateDialogShow'
        :electionId='candidateDialogElectionId'
        :candidates='candidateDialogCandidates'
        @closeDialog='closeCandidateDialog'
    />
</template>

<script setup>
    import { ref, onMounted, computed } from 'vue'
    import axios from 'axios'
    import { useSnackbarStore } from '@/stores/snackbar'
    import DistrictsDialog from '@/views/Election/DistrictsDialog.vue'
    import CandidatesDialog from '@/views/Election/CandidatesDialog.vue'

    const snackbarStore = useSnackbarStore()  

    const elections = ref([])
    const loading = ref(false)
    const renderKey = ref(0)

    const districtDialogShow = ref(false)
    const districtDialogElectionId = ref(null)
    const districtDialogDistricts = ref([])

    const candidateDialogShow = ref(false)
    const candidateDialogElectionId = ref(null)
    const candidateDialogCandidates = ref([])

    const headers = [
            { title: 'Id', value: 'id' },
            { title: 'Start Date', value: 'start_date' },
            { title: 'End Date', value: 'end_date'},
            { title: 'Started', value: 'started'},
            { title: 'Ended', value: 'ended'},
            { title: 'Actions', value: 'actions', width: '210' },
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

    function startElection(election)
    {
        loading.value = true
        snackbarStore.showSnackBar('Starting election, please wait.', 'info', -1)

        axios.post('/api/elections/'+election.id+'/start')
            .then(response => {
                snackbarStore.hideSnackBar()
                loadElections()
            })
            .catch(error => {
                const message = "Failed to start. Reasons: "+error.response.data.message
                snackbarStore.showSnackBar(message, 'error', 5000)
            })
            .finally(() => {
                loading.value = false
            })
    }

    function endElection(election)
    {
        loading.value = true
        snackbarStore.showSnackBar('Stoping election, please wait.', 'info', -1)

        axios.post('/api/elections/'+election.id+'/stop')
            .then(response => {
                snackbarStore.hideSnackBar()
                loadElections()
            })
            .catch(error => {
                const message = "Failed to stop. Reasons: "+error.response.data.message
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


    function showCandidateDialog(election)
    {
        candidateDialogElectionId.value = election.id
        candidateDialogCandidates.value = election.candidates
        candidateDialogShow.value = true
    }
    
    function closeCandidateDialog()
    {
        candidateDialogElectionId.value = null
        candidateDialogCandidates.value = []
        candidateDialogShow.value = false
    }
</script>

<style scoped>

</style>