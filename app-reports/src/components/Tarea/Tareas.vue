<template> 
  <v-container fluid grid-list-xl>
          <v-layout v-if="this.$store.state.loading">
              <loading></loading>
          </v-layout> 
          <v-layout row wrap v-if="tareas"> 
            <v-flex xs12 sm6 md4 flexbox text-xs-center v-for="tarea in tareas" :key="tarea.id"> 
                <v-card height="100%">
                    <v-card-title primary-title class="justify-center"> 
                      <div>
                      <span>
                        <v-chip label :color="colorPrioridad(tarea.informe.idprioridad)" text-color="white"></v-chip>
                      </span>
                      <h3>{{tarea.titulo}}</h3>
                      </div> 
                    </v-card-title>
                    <v-card-text>
                      <div class="font-weight-bold">Fecha límite: <br>{{ moment(tarea.informe.fechalimite).format("dddd D MMMM YYYY")}}</div>
                    </v-card-text>
                    <v-card-actions> 
                        <v-btn flat block color="cyan darken-1" :to="`/tarea/${tarea.idtarea}/ver`">Ver</v-btn>
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
import moment from "moment";
export default {
  data() {
    return {
      moment: moment,
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
    moment.locale('es')
    this.tareasUser()
  },
  watch:{
    '$route': 'tareasUser'
  },
  methods: {
    colorPrioridad(prioridad){
            if(prioridad=== 1){
                return 'teal darken-4'
            }else if(prioridad === 2){
                return 'yellow darken-4'
            }else if(prioridad === 3){
                return 'red darken-4'
            }
        },
    tareasUser() {
      this.$store.commit('setLoading', true)
      var self = this
      self.id = store.state.a;
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
          console.log(self.tareas) // eslint-disable-line no-console
          self.$store.commit('setLoading', false)
          }, 500);
          console.log(self.tareas) // eslint-disable-line no-console
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
