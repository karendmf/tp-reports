<template> 
  <v-container fluid> 
    <v-layout row wrap grid-list-xl> 
      <v-flex xs12 class="text-xs-center" mt-3> 
        <h1>Inicio</h1> 
      </v-flex> 
      <v-flex xs12 sm6 offset-sm3 mt-3> 
        <blockquote class="blockquote text-xs-center"> 
          Este sera el tablero de inicio <br> 
          con widgets y estadisticas
        </blockquote> 
      </v-flex> 
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