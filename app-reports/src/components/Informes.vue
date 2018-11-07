<template>
  <v-container fluid grid-list-xl>
    <v-layout v-if="this.$store.state.loading">
      <loading></loading>
    </v-layout>
    <v-layout row wrap v-if="informes">
      <v-flex xs12 sm6 md3 flexbox text-xs-center v-for="informe in informes" :key="informe.idinforme">
        <v-card height="100%">
          <v-card-title primary-title class="justify-center">
            <div>
              <span>
                <v-chip label :color="colorPrioridad(informe.prioridad.idprioridad)" text-color="white">Prioridad
                  {{informe.prioridad.nombre}}</v-chip>
              </span>
              <h3>{{informe.titulo}}</h3>
            </div>
          </v-card-title>
          <v-divider></v-divider>
          <v-card-text>
            <div>Cantidad de tareas: {{informe.tarea.length}}</div>
            <div>Fecha límite: <br>{{ moment(informe.fechalimite).format("dddd D MMMM YYYY") }}</div>
          </v-card-text>
          <v-card-actions>
            <v-btn flat block color="cyan darken-1" :to="`/informes/${informe.idinforme}/ver`">Ver</v-btn>
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
import moment from 'moment'
export default {
  data() {
    return {
      moment: moment,
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
  
  created() {
    moment.locale('es')
    this.informesUser()
  },
  watch:{
    '$route': 'informesUser'
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
          console.log(response.data)
          setTimeout(function(){ 
          self.informes = response.data;
          if (self.informes.length < 1){
            self.noInformes = true
          }
          self.$store.commit('setLoading', false)
          }, 500);
        })
        .catch(function (err) {
          //console.log(err.response)
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