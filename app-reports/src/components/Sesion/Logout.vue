<template>
    <div></div>
</template>

<script>
    import router from '@/router'
    //import store from '@/store'
    export default {
        name: 'logout',
        mounted () {
            localStorage.removeItem('token')
            this.$store.commit('LOGOUT_USER')
            router.push('/')
        }
    }
</script>