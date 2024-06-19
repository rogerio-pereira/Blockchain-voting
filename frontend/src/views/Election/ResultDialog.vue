<template>
    <v-dialog v-model="active" width="auto" >
        <v-card :loading="loading ? 'info' : null" :key='renderKey'>
            <v-card-item>
                <v-card-title class='text-center'>
                    Results for Election {{electionId}}
                </v-card-title>

            <v-card-text>
                    <v-row>
                        <v-col>
                            <v-data-table 
                                :items="results"
                                :headers="headers"
                            />
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card-item>

             <v-card-actions class="pt-0">
                <v-btn color="primary" @click="closeDialog" >
                    Close
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script setup>
    import { ref, defineProps, computed, defineEmits, watch } from 'vue'
    import { useSnackbarStore } from '@/stores/snackbar'
    import axios from 'axios'

    const emit = defineEmits(['closeDialog'])

    const snackbarStore = useSnackbarStore()  

    const props = defineProps({
            showDialog: {
                type: Boolean,
                required: false,
                default: false
            },
            electionId: {
                type: String??Number??null,
                required: true
            },
        })
    
    const headers = [
            { title: 'Name', value: 'candidate.name' },
            { title: 'Political Party', value: 'candidate.political_party' },
            { title: 'Votes', value: 'total_votes' },
        ]
    const results = ref([])
    
    const active = computed(() => {
            return props.showDialog
        })
    
    const electionId = computed(() => {
            return props.electionId
        })
    
    watch(electionId, (id) => {
            if(id) {
                loadResults(id)
            }
            else {
                results.value = []
            }
        })

    function loadResults(id)
    {
        axios.get('/api/elections/'+id+'/votes')
                    .then(response => {
                        results.value = response.data
                    })
                    .catch(error => {
                        const message = "Failed to load Results. Reasons: "+error.response.data.message
                        snackbarStore.showSnackBar(message, 'error', 5000)
                    })
    }

    function closeDialog()
    {
        emit('closeDialog')
    }
</script>

<style scoped>

</style>