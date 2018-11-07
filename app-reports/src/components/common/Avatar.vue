<template>
    <v-toolbar flat class="transparent  my-3">
        <v-list>

          <v-list-tile avatar v-if="user && typeof user.cargo !== 'undefined'">
            <v-list-tile-avatar color='cyan darken-1'>
              <img src="../../assets/logo_blanco.svg">
            </v-list-tile-avatar>
            <v-list-tile-content>
              <v-list-tile-title>{{user.user.nombre}} {{user.user.apellido}}</v-list-tile-title>
              <v-list-tile-sub-title>{{user.cargo.nombre}}</v-list-tile-sub-title>
              
            </v-list-tile-content>
          </v-list-tile>

          <v-list-tile avatar v-if="user && typeof user.cargo === 'undefined'">
            <v-list-tile-avatar color='cyan darken-1'>
              <img src="../../assets/logo_blanco.svg">
            </v-list-tile-avatar>
            <v-list-tile-content>
              <v-list-tile-title>{{user.user.nombre}} {{user.user.apellido}}</v-list-tile-title>
              <v-list-tile-sub-title>{{user.nombre}}</v-list-tile-sub-title>
            </v-list-tile-content>
          </v-list-tile>

        </v-list>
      </v-toolbar>
</template>
<script>
import axios from 'axios'
export default {
    data() { 
    return { 
      user: null,
      ishseq: null,
      isarea: null,
    } 
  },
  /* beforeCreate() {
    if (!this.$store.state.isLogged) {
      this.$router.push('/signin')
    }
  }, */
  created() { 
    this.usuario() 
  }, 
  methods: { 
    area() {
     return axios.get('/area/id',{
            headers: { 
              Authorization: 'Bearer ' + localStorage.getItem('token') 
            },
          })
    },
    hseq() {
      return axios.get('/hseq/id',{
            headers: { 
              Authorization: 'Bearer ' + localStorage.getItem('token') 
            },
          })
    },
    usuario() {
      this.$store.commit('setLoading', true)
      var self = this
      //console.log(self.id);
      axios.all([
        self.area(),
        self.hseq()
      ])
    .then(axios.spread((first_response, second_response) => {
          self.isarea = first_response.data
          self.ishseq = second_response.data
          //console.log(this.isarea.length,'is area')
          //console.log(this.ishseq.length,'is hseq')
          if (self.ishseq.length > 0){
              self.user = self.ishseq[0]
              //console.log('hseq',self.user)
          }else{
              self.user = self.isarea[0]
              //console.log('area',self.user)
          }
        this.$store.commit('setLoading', false)
        }))
        .catch(function (err) { 
          if (err.response.status === 401){
            self.$store.commit('setExpired', true)
            self.$router.push('/logout')
          }else{
          self.$store.commit('setError', err.message)
          }
          self.$store.commit('setLoading', false)
        }); 
    } 
  },
}
</script>
