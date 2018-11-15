<template> 
  <v-container fluid grid-list-xl>
    <v-layout row wrap >
      <v-flex xs12 md4>
        <clima></clima>
      </v-flex>
      <v-flex xs12 md4>
        <estadisticas></estadisticas>
      </v-flex>
    </v-layout> 
  </v-container> 
</template> 
 
<script> 
import clima from './Clima.vue'
import estadisticas from './Estadisticas.vue'
export default {
  components:{
        clima,
        estadisticas
    },
} 
</script>