<template> 
  <v-container fluid>
          <v-layout v-if="this.$store.state.loading">
              <loading></loading>
          </v-layout> 
          <v-layout row wrap v-if="tareas"> 
            <v-flex xs6 sm3 v-for="tarea in tareas" :key="tarea.id"> 
                <v-card> 
                    <v-card-title primary-title> 
                        <div> 
                            <div class="headline">{{tarea.titulo}}</div> 
                        </div> 
                    </v-card-title> 
                    <v-card-actions> 
                        <v-btn flat block color="cyan darken-1" :to="`/tareas/${tarea.idtarea}/ver`">Ver</v-btn>
                    </v-card-actions> 
                </v-card> 
            </v-flex> 
        </v-layout>
        <v-layout row wrap v-if="noTareas">
          <h1> Usted no tiene tareas</h1>
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
      tareas: false,
      error: null,
      id: null,
      alert: false,
      noTareas: false
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
    this.tareasUser()
  },
  watch:{
    '$route': 'tareasUser'
  },
  methods: {
    tareasUser() {
      this.$store.commit('setLoading', true)
      var self = this
      self.id = store.state.a;
      //console.log(self.id);
      axios.get('/tarea/me/' + self.id, {
          headers: {
            Authorization: 'Bearer ' + localStorage.getItem('token')
          }
        })
        .then(function (response) {
          
          setTimeout(function(){ 
          self.tareas = response.data;
          if (self.tareas.length < 1){
            self.noTareas = true
          }
          self.$store.commit('setLoading', false)
          }, 500);
        })
        .catch(function (err) {
          if (err.response.status === 401){
            self.$store.commit('setExpired', true)
            self.$router.push('/logout')
          }
          self.error = true
          self.$store.commit('setError', err.message)
          self.$store.commit('setLoading', false)
        });
    }
  }

}
</script>
