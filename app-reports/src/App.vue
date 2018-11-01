<template>
  <v-app id="app" class='cyan lighten-5'>

    <!-- PANEL PRINCIPAL -->
    <div v-if="this.$store.state.isLogged">
      <panel></panel>
    </div>
    <!-- INICIO DE SESIÓN -->
    <div v-if="!this.$store.state.isLogged">
      
      <v-container mt-5>
        <v-layout wrap align-space-around justify-center row fill-height elevation-5 mt-3>
          <v-flex xs12 md6 class='cyan lighten-3' pa-5>
            <h1>Lorem Ipsum</h1>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce at urna mi. Nullam quis justo et arcu
              fermentum blandit. Phasellus finibus justo tellus, ac tempor sapien porttitor sit amet. In hac habitasse
              platea dictumst. Quisque in risus risus. Duis quis lacus massa. Phasellus porttitor dolor et pulvinar
              rhoncus.</p>
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
      
    </div>
  </v-app>
</template>

<script>
import login from "@/components/Login.vue";
import panel from "@/components/Panel.vue";
import solicitar from "@/components/SolicitarUsuario.vue";
export default {
  data: () => ({
      step: 1,
    }),
  name: "app",
  components: {
    login,
    panel,
    solicitar
  },
  props: {
    source: String
  },
  
};
</script>

