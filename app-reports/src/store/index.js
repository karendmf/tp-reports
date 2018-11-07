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
    expired:false,
    h: null,
    a: null,
    loading: false, 
    isLogged: false,
    r:null,
    rol: null
  }, 
  mutations: {
    setRol (state, payload) { 
      state.rol = payload 
    },
    setExpired (state, payload) { 
      state.expired = payload 
    },
    registrarUsuario(state, payload){
      state.r = payload
    },
    LOGIN_USER (state) { 
        state.isLogged = true
    }, 
    LOGOUT_USER (state) { 
        state.isLogged = false
        state.user = null
        state.h= null
        state.a= null
        state.error= null
        localStorage.removeItem('token')
        state.r = null
        state.rol= null
    }, 
    setUser (state, payload) { 
      state.user = payload 
    }, 
    setH (state, payload) { 
      state.h = payload 
    },
    setA (state, payload) { 
      state.a = payload 
    }, 
    setError (state, payload) { 
      state.error = payload 
    }, 
    setLoading (state, payload) { 
      state.loading = payload 
    } 
  }, 
  actions: {

    /**
     * Funcíon para iniciar sesión
     * Ejemplo para usarla: this.$store.dispatch('userSignIn', { usuario: this.usuario, password: this.password })
     * @param {*} payload -> objeto con usuario y password
     */
    userSignIn ({commit}, payload) {
      
      commit('setLoading', true) 
      axios.post('/login',{ 
          usuario: payload.usuario, 
          password: payload.password 
      }) 
      .then(function (response) { 
        commit('setUser', response.data.usuario.idpersona) 
        commit('setError', null) 
        router.push('/') 
        localStorage.setItem('token', response.data.token)
        if (response.data.usuario.rol === 'admin'){
          commit('setH', response.data.usuario.hseq.idhseq)
        }else if (response.data.usuario.rol === 'hseq'){
          commit('setH', response.data.usuario.hseq.idhseq)
        }else{
          commit('setA', response.data.usuario.area.idarea )
        }
        commit('setRol', response.data.usuario.rol)
        store.commit('LOGIN_USER')
        commit('setLoading', false)
        commit('setExpired', false)
        //console.log(response.data); 
      }) 
      .catch(error => { 
        console.log(error.response);
        commit('setError', error.message) 
        commit('setLoading', false) 
      }) 
    },
  },
  plugins: [createPersistedState()],
  getters: {} 
})