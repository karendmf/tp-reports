<script>
import {
  Line
} from 'vue-chartjs'
import {
  Bar
} from 'vue-chartjs'
import {
  Doughnut
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
          text: 'Creados por mes'
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
