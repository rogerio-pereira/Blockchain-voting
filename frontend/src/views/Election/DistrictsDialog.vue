<template>
    <v-dialog v-model="active" width="auto" >
        <v-card :loading="loading ? 'info' : null" :key='renderKey'>
            <v-card-item>
                <v-card-title class='text-center'>
                    Voting Districts for Election {{electionId}}
                </v-card-title>

            <v-card-text>
                    <v-row>
                        <v-col>
                            <v-data-table 
                                :items="districts"
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
    import { ref, defineProps, computed, defineEmits } from 'vue'

    const emit = defineEmits(['closeDialog'])

    const props = defineProps({
            showDialog: {
                type: Boolean,
                required: false,
                default: false
            },
            electionId: {
                type: String,
                required: true
            },
            districts: {
                type: String,
                required: true
            },
        })
    
    const headers = [
            { title: 'Id', value: 'id' },
            { title: 'Name', value: 'name' },
        ]
    
    const active = computed(() => {
            return props.showDialog
        })

    function closeDialog()
    {
        emit('closeDialog')
    }
</script>

<style scoped>

</style>