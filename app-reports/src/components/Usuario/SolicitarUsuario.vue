<template>
<v-layout row justify-center>
  <v-flex xs12 text-xs-center pa-3 ma-3>
    <h1>Solicitar Usuario</h1>
    <v-alert type="error" dismissible v-model="alert" outline transition="scale-transition">
      {{ error }}
    </v-alert>
    <v-form ref="form" v-model="valid">
      <v-text-field v-model="nombre" :rules="namesurname" :counter="50" label="Nombre" required></v-text-field>
      <v-text-field v-model="apellido" :rules="namesurname" :counter="50" label="Apellido" required></v-text-field>
      <v-text-field v-model="legajo" :rules="legajoRules" :counter="12" label="Legajo" required></v-text-field>
      <v-text-field v-model="email" :rules="emailRules" label="E-mail" required></v-text-field>
      <v-btn @click="submit" color="cyan darken-1" dark>Enviar</v-btn>
    </v-form>
  </v-flex>
  <v-flex>
    <v-dialog v-model="dialog" persistent max-width="350" >
      <v-card class='text-xs-center'>
        <v-card-title class="headline justify-center">Solicitud de usuario nuevo</v-card-title>
        <v-card-text>Su solicitud se ha enviado con exito.</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="cyan darken-1" flat @click.native="dialog = false">Aceptar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-flex>
</v-layout>
</template>

<script>
import axios from 'axios'
export default {
  data: () => ({
    alert: false,
    valid: true,
    dialog: false,
    nombre: "",
    apellido: "",
    namesurname: [
      v => !!v || "Se requiere nombre y apellido",
      v => (v && v.length <= 50) || "Debe tener menos de 50 caracteres"
    ],

    legajo: "",
    legajoRules: [
      v => !!v || "Se requiere el legajo",
      v => (v && v.length <= 12) || "Debe tener menos de 45 caracteres"
    ],

    email: "",
    emailRules: [
      v => !!v || "Se requiere E-Mail",
      v => /.+@.+/.test(v) || "Debe ser un E-Mail valido"
    ]
  }),

  methods: {
    submit() {
      var self = this
      if (this.$refs.form.validate()) {
        axios.post(
            "/solicitud/new", {
              nombre: this.nombre,
              apellido: this.apellido,
              legajo: this.legajo,
              email: this.email
            }
          )
          .then(function (response) {
            console.log(response) // eslint-disable-line no-console
            self.$store.commit("setError", null);
            if (response.status === 201) {
              self.dialog=true;
              self.$refs.form.reset();
            }
          })
          .catch(error => {
            self.$store.commit("setError", error.response.data);
            self.$store.commit("setLoading", false);
            console.log('catch', error.response.data) // eslint-disable-line no-console
          });
      }
    },
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
        this.alert = true;
      }
    },
    alert(value) {
      if (!value) {
        this.$store.commit("setError", null);
      }

    }
  }
};
</script>