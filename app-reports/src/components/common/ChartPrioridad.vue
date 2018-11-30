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
  extends: Doughnut,
  props: {
    chartdata: {
      type: Object,
      default: null
    }
  },
  data() {
    return {

      options: {
        rotation:-1.5 * Math.PI,
        title:{
          display:true,
          text: 'Prioridad de informes'
        },
        layout: {
        },
        cutoutPercentage: 0,
        ticks: {
          suggestedMin: 50,
          suggestedMax: 100
        },
        legend: {
          display: true
        },
        responsive: true,
        maintainAspectRatio: true

      }
    }
  },
  mounted() {
    this.renderChart(this.chartdata, this.options)
  },

}
</script>
