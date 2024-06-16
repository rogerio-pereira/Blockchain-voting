<template>
    <v-card width='30vw' class='mx-auto'>
        <v-card-item>
            <v-card-title class='text-center'>
                <span v-if='isCreating'>
                    Create 
                </span>
                <span v-else>
                    Update
                </span>

                Elections
            </v-card-title>

           <v-card-text class='mt-4'>
                <v-row>
                    <v-col>
                        <v-text-field 
                            label="Start Date (YYYY-MM-DD)"
                            v-model='election.start_date'
                            :error-messages="errors['start_date']"
                            clearable
                        />

                        <v-text-field 
                            label="End Date (YYYY-MM-DD)"
                            v-model='election.end_date'
                            :error-messages="errors['end_date']"
                            clearable
                        />

                        <!-- Voting District -->
                        <v-select
                            label="Voting District"
                            v-model='election.voting_districts'
                            :items="districts"
                            item-title="name"
                            item-value="id"
                            :error-messages="errors['voting_districts']"
                            multiple
                            clearable
                        >
                            <template v-slot:prepend-item>
                                <v-list-item title="Select All" @click="toggleDistricts">
                                    <template v-slot:prepend>
                                        <v-checkbox-btn
                                            :indeterminate="selectedSomeDistricts && !selectedAllDistricts"
                                            :model-value="selectedAllDistricts"
                                        ></v-checkbox-btn>
                                    </template>
                                </v-list-item>

                                <v-divider class="mt-2"></v-divider>
                            </template>

                            <template v-slot:selection="{ item, index }">
                                <v-chip v-if="index == 0">
                                    <span class="text-grey text-caption align-self-center">
                                        {{ election.voting_districts.length  }} items selected
                                    </span>
                                </v-chip>
                            </template>
                        </v-select>



                        <!-- Candidates -->
                        <v-select
                            label="Candidates"
                            v-model='election.candidates'
                            :items="candidates"
                            item-title="name"
                            item-value="id"
                            :error-messages="errors['candidates']"
                            multiple
                            clearable
                        >
                            <template v-slot:prepend-item>
                                <v-list-item title="Select All" @click="toggleCandidates">
                                    <template v-slot:prepend>
                                        <v-checkbox-btn
                                            :indeterminate="selectedSomeCandidates && !selectedAllCandidates"
                                            :model-value="selectedAllCandidates"
                                        ></v-checkbox-btn>
                                    </template>
                                </v-list-item>

                                <v-divider class="mt-2"></v-divider>
                            </template>

                            <template v-slot:selection="{ item, index }">
                                <v-chip v-if="index == 0">
                                    <span class="text-grey text-caption align-self-center">
                                        {{ election.candidates.length  }} items selected
                                    </span>
                                </v-chip>
                            </template>
                        </v-select>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col class='text-right'>
                        <v-btn v-if='isCreating' color='primary' @click='save' >
                            Save
                        </v-btn>

                        <v-btn v-else color='primary' @click='update' >
                            Update
                        </v-btn>
                    </v-col>
                </v-row>
           </v-card-text>
        </v-card-item>
    </v-card>
</template>

<script setup>
    import { ref, computed, onMounted } from 'vue'
    import { useRoute } from 'vue-router'
    import axios from 'axios'
    import router from '@/router'
    import { useSnackbarStore } from '@/stores/snackbar'

    const route = useRoute()

    const snackbarStore = useSnackbarStore()  

    const election = ref({
            id: null,
            start_date: null,
            end_date: null,
            voting_districts: [],
            candidates: [],
        })
    const errors = ref([])
    const districts = ref([])
    const candidates = ref([])
    
    const isCreating = computed(() => {            
            if(route.params.id == null || route.params.id == '' ) {
                return true
            }

            return false
        })

    onMounted(() => {
            if(isCreating.value == false) {
                loadData()
            }

            loadDistricts()
            loadCandidates()
        })

    function loadData()
    {
        const id = route.params.id
        axios.get('/api/elections/'+id)
            .then(response => {
                election.value = response.data
            })
            .catch(error => {
                const message = "Failed to load Voting District with id "+id+". Reasons: "+error.response.data.message
                snackbarStore.showSnackBar(message, 'error', 5000)
            })
    }

    function loadDistricts()
    {
        axios.get('/api/voting-districts')
            .then(response => {
                districts.value = response.data
            })
            .catch(error => {
                const message = "Failed to load Voting Districts. Reasons: "+error.response.data.message
                snackbarStore.showSnackBar(message, 'error', 5000)
            })
    }

    function loadCandidates()
    {
        axios.get('/api/candidates')
            .then(response => {
                candidates.value = response.data
            })
            .catch(error => {
                const message = "Failed to load Candidates. Reasons: "+error.response.data.message
                snackbarStore.showSnackBar(message, 'error', 5000)
            })
    }

    function save()
    {
        axios.post('/api/elections', election.value)
            .then(response => {
                router.push('/election')
            })
            .catch(error => {
                if(error.response.status == 422) {
                    errors.value = error.response.data.errors
                }
                else {
                    const message = "Failed to create. Reasons: "+error.response.data.message
                    snackbarStore.showSnackBar(message, 'error', 5000)
                }
            })
    }

    function update()
    {
        const id = route.params.id
        axios.put('/api/elections/'+id, election.value)
            .then(response => {
                router.push('/election')
            })
            .catch(error => {
                if(error.response.status == 422) {
                    errors.value = error.response.data.errors
                }
                else {
                    const message = "Failed to update. Reasons: "+error.response.data.message
                    snackbarStore.showSnackBar(message, 'error', 5000)
                }
            })
    }


    /*
     * =================================================================================================================
     * Methods Related to Selectbox
     * =================================================================================================================
     */
    const selectedSomeDistricts = computed(() => {            
            return election.value.voting_districts.length > 0
        })

    const selectedAllDistricts = computed(() => {            
            return election.value.voting_districts.length === districts.value.length
        })

    function toggleDistricts () {
            if (selectedAllDistricts.value) {
                election.value.voting_districts = []
            } else {
                election.value.voting_districts = districts.value.map(element => element.id)    //Return all ids from districs
            }
        }

    const selectedSomeCandidates = computed(() => {            
            return election.value.candidates.length > 0
        })

    const selectedAllCandidates = computed(() => {            
            return election.value.candidates.length === candidates.value.length
        })

    function toggleCandidates () {
            if (selectedAllCandidates.value) {
                election.value.candidates = []
            } else {
                election.value.candidates = candidates.value.map(element => element.id)    //Return all ids from candidates
            }
        }
</script>

<style scoped>

</style>