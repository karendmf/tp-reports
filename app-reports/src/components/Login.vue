<template> 
      <v-flex xs12 text-xs-center pa-3 ma-2>
        <v-avatar size="70px" color="cyan darken-1">
            <img src="../assets/logo_blanco.svg" alt="reportS">
        </v-avatar>
        <br>
        <br>
        <h1>Iniciar sesión</h1> 
        <form @submit.prevent="userSignIn"> 
          <v-layout column> 
            <v-flex> 
              <v-alert type="error" dismissible v-model="alert"> 
                {{ error }} 
              </v-alert>
              <v-alert type="error" dismissible :value='true' v-if='errorg'> 
                Su sesión ha expirado.<br>Por favor, inicie sesión nuevamente.
              </v-alert>
            </v-flex> 
            <v-flex> 
              <v-text-field 
                name="usuario" 
                label="Usuario" 
                id="usuario" 
                type="usuario" 
                v-model="usuario" 
                required></v-text-field> 
            </v-flex> 
            <v-flex> 
              <v-text-field 
                name="password" 
                label="Contraseña" 
                id="password" 
                type="password" 
                v-model="password" 
                required></v-text-field> 
            </v-flex> 
            <v-flex class="text-xs-center" mt-2> 
              <v-btn color="cyan darken-1" type="submit" dark>Ingresar</v-btn> 
            </v-flex> 
          </v-layout> 
        </form>
      </v-flex>
</template> 
 
<script> 
export default {
  data() {
    return {
      usuario: '',
      password: '',
      alert: false,
      errorg: this.$store.state.expired
    }
  },
  beforeCreate() {
    if (this.$store.state.isLogged) {
      this.$router.push('/')
    }
  },
  methods: {
    userSignIn() {
      this.$store.dispatch('userSignIn', {
        usuario: this.usuario,
        password: this.password
      })
    }
  },
  computed: {
    error() {
      return this.$store.state.error
    },
    loading() {
      return this.$store.state.loading
    }
  },
  watch: {
    error(value) {
      this.alert = false;
      if (value) {
        this.alert = true
      }
    },
    alert(value) {
      if (!value) {
        this.$store.commit('setError', null)
      }
    }
  }
}
</script>