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

                Candidates
            </v-card-title>

           <v-card-text class='mt-4'>
                <v-row>
                    <v-col>
                        <v-text-field 
                            label="Name"
                            v-model='candidate.name'
                            clearable
                        />
                        
                        <v-text-field 
                            label="Political Party"
                            v-model='candidate.political_party'
                            clearable
                        />
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

    const candidate = ref({
            id: null,
            name: null,
            political_party: null,
        })
    
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
        })

    function loadData()
    {
        const id = route.params.id
        axios.get('/api/candidates/'+id)
            .then(response => {
                candidate.value = response.data
            })
            .catch(error => {
                const message = "Failed to load Candidatet with id "+id+". Reasons: "+error.response.data.message
                snackbarStore.showSnackBar(message, 'error', 5000)
            })
    }

    function save()
    {
        axios.post('/api/candidates', candidate.value)
            .then(response => {
                router.push('/candidate')
            })
            .catch(error => {
                const message = "Failed to create. Reasons: "+error.response.data.message
                snackbarStore.showSnackBar(message, 'error', 5000)
            })
    }

    function update()
    {
        const id = route.params.id
        axios.put('/api/candidates/'+id, candidate.value)
            .then(response => {
                router.push('/candidate')
            })
            .catch(error => {
                const message = "Failed to update. Reasons: "+error.response.data.message
                snackbarStore.showSnackBar(message, 'error', 5000)
            })
    }
</script>

<style scoped>

</style>