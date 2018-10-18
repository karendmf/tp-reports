import Vue from 'vue'
import Vuex from 'vuex'
import router from '@/router'
import axios from 'axios'

Vue.use(Vuex)

export const store = new Vuex.Store({

  state: {
    appTitle: 'reportS',
    user: null,
    error: null,
    loading: false,
    isLogged: false
  },
  mutations: {
    LOGIN_USER (state) {
        state.isLogged = true
    },
    LOGOUT_USER (state) {
        state.isLogged = false
    },
    setUser (state, payload) {
      state.user = payload
    },
    setError (state, payload) {
      state.error = payload
    },
    setLoading (state, payload) {
      state.loading = payload
    }
  },
  actions: {
    userSignIn ({commit}, payload) {
      commit('setLoading', true)
      axios.post('http://localhost:8000/login',{
          usuario: payload.usuario,
          password: payload.password
      })
      .then(function (response) {
        commit('setUser', {usuario: response.data.user.usuario})
        commit('setLoading', false)
        commit('setError', null)
        store.commit('LOGIN_USER')
        router.push('/home')
        localStorage.setItem('token', response.data.token)
        //console.log(response.data);
      })
      .catch(error => {
        commit('setError', error.message)
        commit('setLoading', false)
      })
    }
  },
  getters: {}
})