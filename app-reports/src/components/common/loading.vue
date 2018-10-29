<template>
    <v-container row wrap align-center>
        <vue-loading type='spiningDubbles' :size='{width: "80px", height: "80px"}'></vue-loading>
    </v-container>
</template>
<script>
  import { VueLoading } from 'vue-loading-template'
  export default {
    name:'loading',
    components: {
      VueLoading
    }
  }
</script>