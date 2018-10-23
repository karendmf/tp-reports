<template> 
  <v-container fluid> 
    <v-layout row wrap v-if="informes"> 
            <v-flex xs6 sm3 v-for="informe in informes" :key="informe.id"> 
                <v-card> 
                    <v-card-title primary-title> 
                        <div> 
                            <div class="headline">{{informe.titulo}}</div> 
                        </div> 
                    </v-card-title> 
                    <v-card-actions> 
                        <v-btn flat block color="cyan darken-1">Ver</v-btn> 
                    </v-card-actions> 
                </v-card> 
            </v-flex> 
        </v-layout>
        <v-layout row wrap v-if="!informes">
          <h1> Usted no tiene informes</h1>
        </v-layout>
  </v-container> 
</template> 
 
<script> 
import router from '@/router' 
import { 
  store 
} from '@/store' 
import axios from 'axios' 
export default { 
  data() { 
    return { 
      informes: false, 
      error: null,
      id: null,
      alert: false
    } 
  }, 
  beforeCreate() { 
    if (!store.state.isLogged) { 
      router.push('/signin') 
    } 
  }, 
  created() { 
    this.informesUser() 
  }, 
  methods: { 
    informesUser() {
      this.$store.commit('setLoading', true)
      var self = this
      self.id= store.state.h;
      //console.log(self.id);
      axios.get('/informe/me/'+self.id) 
        .then(function (response) { 
          self.informes = response.data;
          self.$store.commit('setLoading', false)
        }) 
        .catch(function (err) {
          //console.log(err.response)
          self.$store.commit('setError', err.message)
          self.$store.commit('setLoading', false)
        }); 
    } 
  } 
 
} 
</script>