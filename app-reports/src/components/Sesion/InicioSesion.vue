<template>
    <v-container mt-5 style="margin-top: 3em">
        <v-layout wrap align-space-around justify-center row fill-height elevation-5 mt-3>
          <v-flex xs12 md6 class='cyan lighten-3' pa-5 id='welcome'>
            <img src="@/assets/logotipo@3x.png" alt="logotipo de la app" id='logo-img'>
            <div>
              <br>
              <h1 class="font-weight-black">¡BIENVENIDO!</h1>
              <p class="font-weight-regular title">Inicia sesión con tu usuario y contraseña, o solicita uno completanto el formulario.</p>
            </div>
              
          </v-flex>
          <v-flex xs12 md6 text-xs-center pa-4>
            <v-window v-model="step">
              <v-window-item :value="1">
                <login></login>
              </v-window-item>
              <v-window-item :value="2">
                <solicitar></solicitar>
              </v-window-item>
            </v-window>
            <v-flex>
            <v-btn v-show="step === 2" :disabled="step === 1" flat @click="step--" color="cyan darken-1" outline>
              Regresar
            </v-btn>
            <v-btn v-show="step === 1" :disabled="step === 2" depressed @click="step++" color="cyan darken-1" outline>
              Solicitar Usuario
            </v-btn>
          </v-flex>
          </v-flex>
        </v-layout>
      </v-container>
</template>
<script>
import solicitar from "@/components/Usuario/SolicitarUsuario.vue";
import login from "@/components/Sesion/Login.vue";
export default {
    data: () => ({
      step: 1,
    }),
    components: {
    login,
    solicitar
  },
}
</script>
<style>
#welcome{
  background-image: url("../../assets/capextop.jpg");
  -webkit-background-size: cover;
  -moz-background-size: cover;
  background-size: cover;
  -o-background-size: cover;
}
#logo-img{
  height: auto;
  width: 100%;
}
</style>
