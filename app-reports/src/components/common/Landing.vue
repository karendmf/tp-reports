<template> 
  <v-container fluid grid-list-xl>
    <v-layout row wrap  v-if="this.$store.state.rol == 'hseq' || this.$store.state.rol == 'admin'">
      <v-flex xs12 md6 v-if="chartdataestado">
        <ChartEstadoInformes v-if="chartdataestado" :chartdata="chartdataestado"></ChartEstadoInformes>
      </v-flex>
      <v-flex xs12 md6 v-if="chartdataprioridad">
        <ChartPrioridad v-if="chartdataprioridad" :chartdata="chartdataprioridad"></ChartPrioridad>
      </v-flex>
      <v-flex xs12 md6 v-if="chartdatames">
        <ChartMes v-if="chartdatames" :chartdata="chartdatames"></ChartMes>
      </v-flex>
      <v-flex xs12 md6>
        <clima></clima>
      </v-flex>
    </v-layout> 
    <v-layout row wrap v-if="this.$store.state.rol == 'area'">
      <v-flex xs12 md6 v-if="chartTarea">
        <ChartTarea v-if="chartTarea" :chartdata="chartTarea"></ChartTarea>
      </v-flex>
      <v-flex xs12 md6>
        <clima></clima>
      </v-flex>
    </v-layout>
  </v-container> 
</template> 
 
<script> 
import axios from 'axios'
import moment from "moment";
import clima from './Clima.vue'
import ChartEstadoInformes from './ChartEstadoInformes.vue'
import ChartPrioridad from './ChartPrioridad.vue'
import ChartMes from './ChartInformesMes.vue'
import ChartTarea from './ChartTareasEstado.vue'
export default {
  data() {
    return {
      chartdataestado: null,
      chartdataprioridad: null,
      chartdatames: null,
      chartTarea:null
      
    }
  },
  components: {
    ChartEstadoInformes,
    clima,
    ChartPrioridad,
    ChartMes,
    ChartTarea
  },
  mounted() {
    this.chartEstado()
    this.chartPrioridad()
    this.chartMes()
    this.chartTareaEstado()
  },
  methods: {
    chartEstado() {
      var self = this
      self.id = this.$store.state.h;
      axios.get('/informe/cantInformes/' + self.id, {
          headers: {
            Authorization: "Bearer " + localStorage.getItem("token")
          }
        })
        .then(function (response) {
          var cerrados = response.data.cerrado
          var abiertos = response.data.abierto
          var vencidos = response.data.vencido
          if (vencidos != 0 || abiertos != 0 || cerrados != 0){
          self.chartdataestado = {
            labels: ['Abiertos', 'Cerrados', 'Vencidos'],
            datasets: [{
              backgroundColor: ['#4DD0E1', '#00ACC1', '#00838F'],
              pointBackgroundColor: 'white',
              borderWidth: 1,
              pointBorderColor: '#249EBF',
              data: [abiertos, cerrados, vencidos]
            }]
          }
          }
        })
        .catch(error => {
            if (error.response.status === 401){
              self.$store.commit('setExpired', true)
              self.$router.push('/logout')
            }
        })
    },
    chartPrioridad() {
      var self = this
      self.id = this.$store.state.h;
      axios.get('/informe/cantPrioridad/' + self.id, {
          headers: {
            Authorization: "Bearer " + localStorage.getItem("token")
          }
        })
        .then(function (response) {
          if (response.data != 0){
            var jsonarray= response.data
            var labels = jsonarray.map(function(e) {
              return e.nombre;
            })
            var data = jsonarray.map(function(e) {
              return e.cantidad;
            });
            self.chartdataprioridad = {
              labels: labels,
              datasets: [{
                backgroundColor: ['#0097A7', '#26C6DA', '#80DEEA'],
                pointBackgroundColor: 'white',
                borderWidth: 1,
                pointBorderColor: '#249EBF',
                //Data to be represented on y-axis
                data: data
              }]
            }
          }
        })
        .catch(error => {
            if (error.response.status === 401){
              self.$store.commit('setExpired', true)
              self.$router.push('/logout')
            }
        })
    },
    chartMes() {
      var self = this
      self.id = this.$store.state.h;
      axios.get('/informe/cantMesCreate/' + self.id, {
          headers: {
            Authorization: "Bearer " + localStorage.getItem("token")
          }
        })
        .then(function (response) {
          if (response.data != 0){
            var jsonarray= response.data
            var labels = jsonarray.map(function(e) {
              return moment(e.mes, "MM").format('MMMM')
            })
            var data = jsonarray.map(function(e) {
              return e.cantidad;
            });
            
            self.chartdatames = {
              labels: labels,
              datasets: [{
                label: 'Cantidad',
                backgroundColor: '#00BCD4',
                pointBackgroundColor: '#1DE9B6',
                borderWidth: 1,
                pointBorderColor: '#1DE9B6',
                data: data
              }
              ]
            }
          }
        })
        .catch(error => {
            if (error.response.status === 401){
              self.$store.commit('setExpired', true)
              self.$router.push('/logout')
            }
        })
    },
    chartTareaEstado() {
      var self = this
      self.id = this.$store.state.a;
      axios.get('/tarea/estado/' + self.id, {
          headers: {
            Authorization: "Bearer " + localStorage.getItem("token")
          }
        })
        .then(function (response) {
          if ((response.data[0].abiertas !=0) || (response.data[0].cerradas !=0)){
            var datos = response.data[0]
            self.chartTarea= {
              labels: ['Tareas cerradas', 'Tareas abiertas'],
              datasets: [{
                label: 'Cantidad',
                backgroundColor: '#26C6DA',
                pointBackgroundColor: 'white',
                borderWidth: 1,
                pointBorderColor: '#249EBF',
                //Data to be represented on y-axis
                data: [datos.cerradas, datos.abiertas]
              }]
            }
          }
        })
        .catch(error => {
            if (error.response.status === 401){
              self.$store.commit('setExpired', true)
              self.$router.push('/logout')
            }
        })
    }
  }
}
</script>