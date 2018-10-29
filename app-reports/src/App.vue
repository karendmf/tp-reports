<template>
<v-app id="app">
  <!-- PANEL PRINCIPAL -->
  <div v-if="this.$store.state.isLogged">
    <panel></panel>
  </div>

  <!-- INICIO DE SESIÓN -->
  <div v-if="!this.$store.state.isLogged">
    <login></login>
  </div>
</v-app>
</template>

<script>
import login from "@/components/Login.vue";
import panel from "@/components/Panel.vue";
export default {
  name: "app",
  components: {
    login,
    panel
  },
  props: {
    source: String
  }
};
</script>
