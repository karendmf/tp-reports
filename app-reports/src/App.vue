<template>
  <v-app id="app" class='cyan lighten-5'>

    <!-- PANEL PRINCIPAL -->
    <div v-if="this.$store.state.isLogged">
      <panel></panel>
    </div>
    <!-- INICIO DE SESIÓN -->
    <div v-if="!this.$store.state.isLogged">
      <inicio-sesion></inicio-sesion>
    </div>
  </v-app>
</template>

<script>
import panel from "@/components/common/Panel.vue";
import inicioSesion from "@/components/Sesion/InicioSesion.vue";

export default {
  data: () => ({
      
    }),
  name: "app",
  components: {
    panel,
    inicioSesion
  },
  props: {
    source: String
  },
  
};
</script>

