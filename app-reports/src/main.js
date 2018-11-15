import Vue from 'vue'
import App from './App.vue'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
import 'babel-polyfill'
import router from './router'
import { store } from './store'
import axios from 'axios'
import 'vue-event-calendar/dist/style.css'
import vueEventCalendar from 'vue-event-calendar'
import VueGoogleCharts from 'vue-google-charts'

Vue.use(Vuetify)
Vue.use(vueEventCalendar, {locale: 'es', color: 'gray'}) 
Vue.use(VueGoogleCharts, {'language': 'es'})
Vue.config.productionTip = false

axios.defaults.baseURL = 'http://localhost:8000';

Vue.prototype.$storageURL = 'http://localhost/Trabajo%20Final/tp-reports/api/storage/';


new Vue({
  el: '#app',
  store,
  router,
  axios,
  render: h => h(App)
}).$mount('#app')