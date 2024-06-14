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

                Voting Districs
            </v-card-title>

           <v-card-text class='mt-4'>
                <v-row>
                    <v-col>
                        <v-text-field 
                            label="Name"
                            v-model='district.name'
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

    const district = ref({
            id: null,
            name: null,
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
        axios.get('/api/voting-districts/'+id)
            .then(response => {
                district.value = response.data
            })
            .catch(error => {
                const message = "Failed to load Voting District with id "+id+". Reasons: "+error.response.data.message
                snackbarStore.showSnackBar(message, 'error', 5000)
            })
    }

    function save()
    {
        axios.post('/api/voting-districts', district.value)
            .then(response => {
                router.push('/voting-district')
            })
            .catch(error => {
                const message = "Failed to create. Reasons: "+error.response.data.message
                snackbarStore.showSnackBar(message, 'error', 5000)
            })
    }

    function update()
    {
        const id = route.params.id
        axios.put('/api/voting-districts/'+id, district.value)
            .then(response => {
                router.push('/voting-district')
            })
            .catch(error => {
                const message = "Failed to update. Reasons: "+error.response.data.message
                snackbarStore.showSnackBar(message, 'error', 5000)
            })
    }
</script>

<style scoped>

</style>