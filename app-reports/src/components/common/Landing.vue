<template> 
  <v-container fluid grid-list-xl>
    <v-layout row wrap >
      
      <v-flex xs12 md6>
        <ChartEstadoInformes v-if="chartdataestado" :chartdata="chartdataestado"></ChartEstadoInformes>
      </v-flex>
      <v-flex xs12 md6>
        <ChartPrioridad v-if="chartdataprioridad" :chartdata="chartdataprioridad"></ChartPrioridad>
      </v-flex>
      <v-flex xs12 md6>
        <ChartMes v-if="chartdatames" :chartdata="chartdatames"></ChartMes>
      </v-flex>
      <v-flex xs12 md4>
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
export default {
  data() {
    return {
      chartdataestado: null,
      chartdataprioridad: null,
      chartdatames: null,
      
    }
  },
  components: {
    ChartEstadoInformes,
    clima,
    ChartPrioridad,
    ChartMes
  },
  mounted() {
    this.chartEstado()
    this.chartPrioridad()
    this.chartMes()
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
          self.chartdataestado = {
            labels: ['Abiertos', 'Cerrados', 'Vencidos'],
            datasets: [{
              backgroundColor: ['#00695C', '#80CBC4', '#26A69A'],
              pointBackgroundColor: 'white',
              borderWidth: 1,
              pointBorderColor: '#249EBF',
              data: [abiertos, cerrados, vencidos]
            }]
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
              backgroundColor: ['#004D40', '#00796B', '#26A69A'],
              pointBackgroundColor: 'white',
              borderWidth: 1,
              pointBorderColor: '#249EBF',
              //Data to be represented on y-axis
              data: data
            }]
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
          var jsonarray= response.data
          var labels = jsonarray.map(function(e) {
            return moment('2017-'+e.mes+'-11').format('MMMM')
          })
          var data = jsonarray.map(function(e) {
            return e.cantidad;
          });
          
          self.chartdatames = {
            labels: labels,
            datasets: [{
              label: 'Cantidad',
              backgroundColor: '#00796B',
              pointBackgroundColor: '#1DE9B6',
              borderWidth: 1,
              pointBorderColor: '#1DE9B6',
              data: data
            }
            ]
          }
        })
    }
  }
}
</script>