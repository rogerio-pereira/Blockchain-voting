<template>
    <v-card width='30vw' class='mx-auto mt-16'>
        <v-card-item>
            <v-card-title class='text-center'>
                Register
            </v-card-title>
        </v-card-item>

        <v-card-text>
            <h2>User</h2>
            <v-text-field 
                label="Name"
                v-model='form.user.name'
                :error-messages="errors['user.name']"
                clearable
            />

            <v-text-field 
                label="Email"
                v-model='form.user.email'
                :error-messages="errors['user.email']"
                clearable
            />

            <v-text-field 
                label="Password"
                v-model='form.user.password'
                type='password'
                :error-messages="errors['user.password']"
                clearable
            />

            <v-text-field 
                label="Confirmation"
                v-model='form.user.password_confirmation'
                type='password'
                :error-messages="errors['user.password_confirmation']"
                clearable
            />

            <hr class='my-4'>

            <h2>Voter</h2>
            <v-autocomplete
                label="Voting District"
                v-model='form.voter.voting_district_id'
                :items="districts"
                item-title="name"
                item-value="id"
                :error-messages="errors['voter.voting_district_id']"
                clearable
            ></v-autocomplete>

            <v-text-field 
                label="Address"
                v-model='form.voter.address'
                :error-messages="errors['voter.address']"
                clearable
            />

            <v-text-field 
                label="Address 2"
                v-model='form.voter.address_2'
                :error-messages="errors['voter.address_2']"
                clearable
            />

            <v-text-field 
                label="City"
                v-model='form.voter.city'
                :error-messages="errors['voter.city']"
                clearable
            />

            <v-autocomplete
                label="State"
                v-model='form.voter.state'
                :items="statesStore.states"
                item-title="name"
                item-value="abbreviation"
                :error-messages="errors['voter.state']"
                clearable
            ></v-autocomplete>

            <v-text-field 
                label="Zipcode"
                v-model='form.voter.zipcode'
                :error-messages="errors['voter.zipcode']"
                clearable
            />

        </v-card-text>

        <v-card-actions class='mb-4'>
            <v-row class='text-center'>
                <v-btn @click="register" color='primary' class='mx-auto' variant='elevated'>
                    Register 
                </v-btn>
            </v-row>
        </v-card-actions>
    </v-card>
</template>

<script setup>
    import { ref,computed, onMounted } from 'vue'
    import axios from 'axios'
    import { useUserStore } from '@/stores/user'
    import router from '@/router'
    import { useSnackbarStore } from '@/stores/snackbar'
    import { useStatesStore } from '@/stores/states'

    const snackbarStore = useSnackbarStore()  
    const userStore = useUserStore()
    const statesStore = useStatesStore()

    const form = ref({
        user: {
            'name': '',
            'email': '',
            'password': '',
            'password_confirmation': '',
        },
        voter: {
            'voting_district_id': null,
            'address': null,
            'address_2': null,
            'city': null,
            'state': null,
            'zipcode': null,
        }
    })
    const errors = ref([])
    const districts = ref([])

    onMounted(() => {
            loadDistricts()
        })

    function loadDistricts() 
    {
        axios.get('/api/voting-districts/open')
            .then(response => {
                districts.value = response.data
            })
            .catch(error => {
                const message = "Failed to load Voting Districts. Reasons: "+error.response.data.message
                snackbarStore.showSnackBar(message, 'error', 5000)
            })
    }

    function register() {
        axios.post('/api/register-to-vote', form.value)
            .then(response => {
                userStore.user = response.data.user
                router.push({name: 'Home'})
            })
            .catch(error => {
                if(error.response.status == 422) {
                    errors.value = error.response.data.errors
                }
                else {
                    const message = "Failed to register. Reasons: "+error.response.data.message
                    snackbarStore.showSnackBar(message, 'error', 5000)
                }
            })
    }
</script>
<style scoped>
    
</style>