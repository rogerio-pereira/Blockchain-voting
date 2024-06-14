import { ref } from 'vue'
import { defineStore } from 'pinia'

export const useSnackbarStore = defineStore('snackbar', () => {
    const message = ref('')
    const color = ref('')
    const timeout = ref(0)
    const active = ref(false)
    const renderKey = ref(0)

    function showSnackBar(text, c = 'info', time = 3000) 
    {
        message.value = text
        color.value = c
        timeout.value = time
        active.value = true

        renderKey.value++
    }

    function hideSnackBar()
    {
        message.value = ''
        color.value = ''
        timeout.value = 0
        active.value = false

        renderKey.value++
    }

    return { 
        message,
        color,
        timeout,
        active,
        showSnackBar, 
        hideSnackBar 
    }
})