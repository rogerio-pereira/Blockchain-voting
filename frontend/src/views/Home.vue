<template>
    <h1>Welcome {{ userStore.user.name }}</h1>
</template>

<script setup>
    import { ref } from 'vue'
    import { useUserStore } from '@/stores/user'

    const userStore = useUserStore()    
</script>

<style scoped>

</style>