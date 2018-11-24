<template>
  <v-container fluid grid-list-xl>
    
    <v-card v-if="informes">
            <v-card-title>
                Informes
                <v-spacer></v-spacer>
                <v-text-field v-model="search" append-icon="search" label="Buscar" single-line hide-details></v-text-field>
            </v-card-title>
            <v-data-table :headers="headers" :items="informes" :search="search" class="elevation-1" rows-per-page-text='' :rows-per-page-items="rowsperpageitems">
                <template slot="no-data">
                    <v-alert :value="true" color="error" icon="warning">
                        No hay informes :(
                    </v-alert>
                </template>
                <template slot="items" slot-scope="props">
                    <td>{{ props.item.usuario.nombre }}</td>
                    <td>{{ props.item.usuario.apellido }}</td>
                    <td class="text-xs-left">{{ props.item.informe.titulo }}</td>
                    <td class="text-xs-left">{{ props.item.informe.tarea.length }}</td>
                    <td class="text-xs-left">{{ moment(props.item.informe.fechalimite).format("dddd D MMMM YYYY") }}</td>
                    <td class="text-xs-left">{{ props.item.informe.estado.nombre }}</td>
                    <td class="text-xs-left">{{ props.item.informe.prioridad.nombre }}</td>
                    <td class="justify-center">
                      <v-btn flat color="cyan darken-1" :to="`/informe/${props.item.informe.idinforme}/ver`"><v-icon
                        small
                        class="mr-2">
                        visibility
                      </v-icon></v-btn>
                    </td>
                </template>
            <v-alert slot="no-results" :value="true" color="error" icon="warning">
                No hay resultados para "{{ search }}".
            </v-alert>
            </v-data-table>
        </v-card>
    <!-- <v-layout row wrap v-if="informes">
      <v-flex xs12 sm6 md3 flexbox text-xs-center v-for="i in informes" :key="i.informe.idinforme">
        <v-card height="100%">
          <v-card-title primary-title class="justify-center">
            <div>
              <span>
                <v-chip label :color="colorPrioridad(i.informe.prioridad.idprioridad)" text-color="white">Prioridad
                  {{i.informe.prioridad.nombre}}</v-chip>
              </span>
              <h3>{{i.informe.titulo}}</h3>
              <div class="font-weight-medium">HSEQ: {{i.usuario.nombre}} {{i.usuario.apellido}}</div>
            </div>
          </v-card-title>
          <v-divider></v-divider>
          <v-card-text>
            <div>Cantidad de tareas: {{i.informe.tarea.length}}</div>
            <div>Fecha límite:
              <span v-if="moment().format() > moment(i.informe.fechalimite).format()" class="font-weight-thin font-italic">CADUCADO</span>
              <br>
              {{ moment(i.informe.fechalimite).format("dddd D MMMM YYYY") }}
            </div>
            
            <div class="font-weight-medium">Estado: {{i.informe.estado.nombre}}</div>
          </v-card-text>
          <v-card-actions>
            <v-btn flat block color="cyan darken-1" :to="`/informe/${i.informe.idinforme}/ver`">Ver</v-btn>
          </v-card-actions>
        </v-card>
      </v-flex>
    </v-layout>
    <v-layout row wrap v-if="noInformes">
      <h1> Usted no tiene informes</h1>
    </v-layout> 
    <v-layout row wrap v-if="error">
      <h1> Error </h1>
    </v-layout>-->
  </v-container>
</template>
 
<script> 
import loading from "@/components/common/loading.vue";
import { store } from '@/store'
import axios from 'axios'
import moment from 'moment'
export default {
  data() {
    return {
      moment: moment,
      informes: false,
      //error: null,
      id: null,
      //alert: false,
      //noInformes: false,
      headers: [
            {
                text: 'Nombre',
                align: 'left',
                value: 'usuario.nombre'
            },
            {
                text: 'Apellido',
                align: 'left',
                value: 'usuario.apellido'
            },
            { text: 'Título', value: 'informe.titulo' },
            { text: 'Tareas', value: 'informe.tarea.length' },
            { text: 'Fecha límite', value: 'informe.fechalimite' },
            { text: 'Estado', value: 'informe.estado.nombre' },
            { text: 'Prioridad', value: 'informe.prioridad.nombre' },
            { text: 'Acciones', value: 'name', sortable: false }
            ],
            rowsperpageitems:[ 5, 10, 25, { "text": "Todos", "value": -1 } ],
            search: '',
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
      axios.get('/informe/ver/', {
          headers: {
            Authorization: 'Bearer ' + localStorage.getItem('token')
          }
        })
        .then(function (response) {
          console.log(response.data) // eslint-disable-line no-console
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