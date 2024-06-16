<template>
    <v-card width='30vw' class='mx-auto'>
        <v-card-item>
            <v-card-title class='text-center'>
                Vote
            </v-card-title>

           <v-card-text class='mt-4'>
                <v-row>
                    <v-col>
                        <!-- Candidates -->
                        <v-autocomplete
                            label="Candidates"
                            v-model='vote.candidate_id'
                            :items="candidates"
                            item-title="name"
                            item-value="id"
                            :error-messages="errors['candidate_id']"
                            clearable
                        >
                            <template v-slot:item="{ props, item }">
                                <v-list-item
                                    v-bind="props"
                                    :title="item.raw.name+' - '+item.raw.political_party"
                                />
                            </template>

                            <template v-slot:selection="{ props, item }">
                                {{item.raw.name}} - {{item.raw.political_party}}
                            </template>
                        </v-autocomplete>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col class='text-right'>
                        <v-btn color='primary' @click='save' >
                            Vote
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
    import { useSnackbarStore } from '@/stores/snackbar'
    import router from '@/router'

    const route = useRoute()

    const snackbarStore = useSnackbarStore()

    const vote = ref({
            candidate_id: null,
        })
    const errors = ref([])
    const candidates = ref([])

    const electionId = computed(() => {
            return route.params.id
        })
        
    onMounted(() => {
            loadCandidates()
        })

    function loadCandidates()
    {
        axios.get('/api/candidates/open')
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
        const data = {
            candidate_id: vote.value.candidate_id,
            election_id: electionId.value,
        }

        axios.post('/api/vote', data)
            .then(response => {
                const message = response.data.message
                snackbarStore.showSnackBar(message, 'success', 5000)
                
                router.push({name: 'Home'})
            })
            .catch(error => {
                if(error.response.status == 422) {
                    errors.value = error.response.data.errors
                }
                else {
                    const message = "Failed to Register Vote. Reasons: "+error.response.data.message
                    snackbarStore.showSnackBar(message, 'error', 5000)
                }
            })
    }
</script>

<style scoped>

</style>