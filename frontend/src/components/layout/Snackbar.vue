<template>
    <v-snackbar 
        v-model="snackbarStore.active" 
        :timeout="snackbarStore.timeout" 
        :color='snackbarStore.color'
        variant="tonal"
        location='top'
    >
        {{ snackbarStore.message }}

        <template v-slot:actions>
            <v-btn color="snackbarStore.color" variant="text" @click="snackbarStore.hideSnackBar">
                Close
            </v-btn>
        </template>
    </v-snackbar>
</template>

<script setup>
    import { useSnackbarStore } from '@/stores/snackbar'

    const snackbarStore = useSnackbarStore()    
</script>

<style scoped>

</style>