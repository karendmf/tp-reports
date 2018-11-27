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
Vue.use(VueGoogleCharts, {'language': 'es'})
Vue.use(vueEventCalendar, {locale: 'es', color: 'gray'}) 
Vue.config.productionTip = false

Vue.prototype.$storageURL = 'http://localhost/Trabajo%20Final/tp-reports/api/storage/'
axios.defaults.baseURL = 'http://localhost:8000'
//axios.defaults.baseURL = 'http://192.168.0.18:8000';

new Vue({
  el: '#app',
  store,
  router,
  axios,
  render: h => h(App)
}).$mount('#app')