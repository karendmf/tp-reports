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
//axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('token');
/* window.axios.interceptors.response.use(function (response) {
  return response;
}, function (error) {
  if (401 === error.response.status) {
      swal({
          title: "Session Expired",
          text: "Your session has expired. Would you like to be redirected to the login page?",
          type: "warning",
          
      }, function(){
          window.location = '/login';
          return Promise.reject(error);
      });
  } else {
      return Promise.reject(error);
  }
}); */

new Vue({
  
  el: '#app',
  store,
  router,
  axios,
  render: h => h(App)
}).$mount('#app')