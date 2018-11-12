import Vue from 'vue'
import App from './App.vue'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
import 'babel-polyfill'
import router from './router'
import { store } from './store'
import axios from 'axios'

Vue.use(Vuetify)



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