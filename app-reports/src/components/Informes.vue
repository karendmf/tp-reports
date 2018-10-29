<template> 
  <v-container fluid>
          <v-layout v-if="this.$store.state.loading">
              <loading></loading>
          </v-layout> 
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
        <v-layout row wrap v-if="noInformes">
          <h1> Usted no tiene informes</h1>
        </v-layout>
        <v-layout row wrap v-if="error">
          <h1> Error </h1>
        </v-layout>
  </v-container> 
</template> 
 
<script> 
import loading from "@/components/common/loading.vue";
import router from '@/router'
import { store } from '@/store'
import axios from 'axios'
export default {
  data() {
    return {
      informes: false,
      error: null,
      id: null,
      alert: false,
      noInformes: false
    }
  },
  components: {
    loading
  },
  beforeCreate() {
    if (!store.state.isLogged) {
      router.push('/signin')
    }
  },
  created() {
    this.informesUser()
  },
  watch:{
    '$route': 'informesUser'
  },
  methods: {
    informesUser() {
      this.$store.commit('setLoading', true)
      var self = this
      self.id = store.state.h;
      //console.log(self.id);
      axios.get('/informe/me/' + self.id, {
          headers: {
            Authorization: 'Bearer ' + localStorage.getItem('token')
          }
        })
        .then(function (response) {
          setTimeout(function(){ 
          self.informes = response.data;
          if (self.informes.length < 1){
            self.noInformes = true
          }
          self.$store.commit('setLoading', false)
          }, 2000);
        })
        .catch(function (err) {
          //console.log(err.response)
          self.error = true
          self.$store.commit('setError', err.message)
          self.$store.commit('setLoading', false)
        });
    }
  }

}
</script>