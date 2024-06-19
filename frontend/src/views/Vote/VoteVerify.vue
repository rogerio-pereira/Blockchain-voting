<template>
    <v-card width='50vw' class='mx-auto'>
        <v-card-item>
            <v-card-title class='text-center'>
                Verify Vote in Blockchain
            </v-card-title>

           <v-card-text class='mt-4'>
                <v-row>
                    <v-col>
                        <v-text-field 
                            label="Blockchain Id"
                            v-model='blockchainId'
                            :error-messages="errors['blockchain_id']"
                            clearable
                        />
                    </v-col>
                </v-row>

                <v-row>
                    <v-col class='text-right'>
                        <v-btn color='primary' @click='verify' >
                            Verify
                        </v-btn>
                    </v-col>
                </v-row>

                <v-row v-if='blockchainVote'>
                    <v-col>
                        <p>
                            Id: <strong>{{blockchainVote.id}}</strong><br/>
                            Candidate Id: <strong>{{blockchainVote.candidate_id}}</strong><br/>
                            Election Id: <strong>{{blockchainVote.election_id}}</strong><br/>
                            Candidate Id: <strong>{{blockchainVote.candidate_id}}</strong><br/>
                            Voter Hash: <strong>{{blockchainVote.voter_hash}}</strong><br/>
                            Created at: <strong>{{blockchainVote.created_at}}</strong><br/>
                            Updated at: <strong>{{blockchainVote.updated_at}}</strong><br/>
                        </p>
                    </v-col>
                </v-row>
           </v-card-text>
        </v-card-item>
    </v-card>
</template>

<script setup>
    import { ref, computed, onMounted } from 'vue'
    import axios from 'axios'
    import { useSnackbarStore } from '@/stores/snackbar'
    import { useRoute } from 'vue-router'

    const route = useRoute()

    const snackbarStore = useSnackbarStore()

    const blockchainId = ref(null)
    const blockchainVote = ref(null)
    const errors = ref([])

    const routeParamId = computed(() => {
            return route.params.id
        })
        
    onMounted(() => {
            if(routeParamId.value) {
                blockchainId.value = routeParamId
                verify()
            }
        })

    function verify()
    {
        snackbarStore.showSnackBar('Verifying vote in blockchain. Please wait.', 'info', -1)
        blockchainVote.value = null

        const id = blockchainId.value

        axios.get('/api/blockchain/verify/'+id)
            .then(response => {
                blockchainVote.value = response.data
                snackbarStore.hideSnackBar()
            })
            .catch(error => {
                if(error.response.status == 422) {
                    errors.value = error.response.data.errors
                }
                else {
                    const message = "Failed to verify in blockchain. Reasons: "+error.response.data.message
                    snackbarStore.showSnackBar(message, 'error', 5000)
                }
            })
    }
</script>

<style scoped>

</style>