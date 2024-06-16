<template>
    <v-card width='30vw' class='mx-auto mt-16'>
        <v-card-item>
            <v-card-title class='text-center'>
                Login
            </v-card-title>
        </v-card-item>

        <v-card-text>
            <v-text-field 
                label="Email"
                v-model='form.email'
                prepend-icon="mdi-at"
                            :error-messages="errors['email']"
                clearable
            />

            <v-text-field 
                label="Password"
                type='password'
                v-model='form.password'
                prepend-icon="mdi-asterisk"
                :error-messages="errors['password']"
                clearable
            />
        </v-card-text>

        <v-card-actions class='mb-4'>
            <v-row class='text-center'>
                <v-btn @click="login" color='primary' class='mx-auto' variant='elevated'>
                    Login 
                </v-btn>
            </v-row>
        </v-card-actions>
    </v-card>
</template>

<script setup>
    import { ref,computed } from 'vue'
    import axios from 'axios'
    import { useUserStore } from '@/stores/user'
    import router from '@/router'
    import { useSnackbarStore } from '@/stores/snackbar'

    const userStore = useUserStore()

    const snackbarStore = useSnackbarStore()  

    const form = ref({
        'email': '',
        'password': '',
    })
    const errors = ref([])

    function login() {
        axios.post('/login', form.value)
            .then(response => {
                userStore.user = response.data.user
                router.push({name: 'Home'})
            })
            .catch(error => {
                if(error.response.status == 422) {
                    errors.value = error.response.data.errors
                }
                else {
                    const message = "Failed to Login. Reasons: "+error.response.data.message
                    snackbarStore.showSnackBar(message, 'error', 5000)
                }
            })
    }
</script>

<style scoped>
    
</style>