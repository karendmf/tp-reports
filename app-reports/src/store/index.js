import Vue from 'vue' 
import Vuex from 'vuex' 
import router from '@/router' 
import axios from 'axios'
import createPersistedState from 'vuex-persistedstate'
 
Vue.use(Vuex) 
 
export const store = new Vuex.Store({ 
 
  state: { 
    user: null, 
    error: null,
    h: null, 
    loading: false, 
    isLogged: false 
  }, 
  mutations: { 
    LOGIN_USER (state) { 
        state.isLogged = true
    }, 
    LOGOUT_USER (state) { 
        state.isLogged = false
        state.user = null
        state.h= null
        localStorage.removeItem('token')
    }, 
    setUser (state, payload) { 
      state.user = payload 
    }, 
    setH (state, payload) { 
      state.h = payload 
    }, 
    setError (state, payload) { 
      state.error = payload 
    }, 
    setLoading (state, payload) { 
      state.loading = payload 
    } 
  }, 
  actions: {
    /* getIDH({commit}){
      commit('setLoading', true)
      axios.get('http://localhost:8000/hseq/id',{ 
        headers: { 
          Authorization: 'Bearer ' + localStorage.getItem('token') 
        } 
      }). then(function (response){
        commit('setLoading', false)
        commit('setH', {h: response.data.idhseq})
      })
    }, */
    userSignIn ({commit}, payload) { 
      commit('setLoading', true) 
      axios.post('http://localhost:8000/login',{ 
          usuario: payload.usuario, 
          password: payload.password 
      }) 
      .then(function (response) { 
        commit('setUser', {u: response.data.usuario.idpersona}) 
        commit('setLoading', false) 
        commit('setError', null) 
        store.commit('LOGIN_USER')
        
        router.push('/') 
        localStorage.setItem('token', response.data.token)
        if (response.data.usuario.rol === 'admin' || response.data.usuario.rol === 'hseq'){
          axios.get('http://localhost:8000/hseq/id',{ 
            headers: { 
              Authorization: 'Bearer ' + localStorage.getItem('token') 
            } 
          }). then(function (response){
            commit('setH', {h: response.data[0].idhseq})
            console.log(response.data[0].idhseq);
          })
        }
        //console.log(response.data); 
      }) 
      .catch(error => { 
        commit('setError', error.message) 
        commit('setLoading', false) 
      }) 
    } 
  },
  plugins: [createPersistedState()],
  getters: {} 
})