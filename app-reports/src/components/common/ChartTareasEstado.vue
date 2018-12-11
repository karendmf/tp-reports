<script>

import {
  Bar
} from 'vue-chartjs'

export default {
  extends: Bar,
  props: {
    chartdata: {
      type: Object,
      default: null
    }
  },
  data() {
    return {
      options: {
          title:{
          display:true,
          text: 'Estado de las tareas'
        },
          scales: {
            yAxes: [{
              ticks: {
                beginAtZero: true,
                min: 0,
                stepSize: 1,
              },
              gridLines: {
                display: true
              }
            }],
            xAxes: [ {
              gridLines: {
                display: false
              }
            }]
          },
          legend: {
            display: true
          },
          responsive: true,
          maintainAspectRatio: false
        
      }
        
    }
  },
  mounted() {
    this.renderChart(this.chartdata, this.options)
  },

}
</script>
