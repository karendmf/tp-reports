<template>
  <v-stepper v-model="porcentaje" vertical>
    <!-- Paso 1 -->
    <v-stepper-step :complete="porcentaje == null" step="?" color='cyan darken-2'>
      Aceptar tarea
      <small>Usted se compromete a cumplir esta tarea</small>
    </v-stepper-step>
    <v-stepper-content step="?">
      <v-btn dark color="cyan darken-1" @click="updateProgreso(0)">Siguiente</v-btn>
    </v-stepper-content>

    <!-- Paso 2 -->
    <v-stepper-step :complete="porcentaje == 0" step="0" color='cyan darken-2'>Personal Asignado
         <small>Se asigna y envía personal a realizar la tarea</small>
    </v-stepper-step>

    <v-stepper-content step="0">
      <v-btn dark color="cyan darken-1" @click="updateProgreso(15)">Siguiente</v-btn>
    </v-stepper-content>

    <!-- Paso 3 -->
    <v-stepper-step :complete="porcentaje == 15" step="15" color='cyan darken-2'>Realizando Tarea
        <small>Se estan ejecutando acciones para resolver la tarea</small>
    </v-stepper-step>

    <v-stepper-content step="15">
      <v-btn dark color="cyan darken-1" @click="updateProgreso(40)">Siguiente</v-btn>
      <v-btn flat @click="updateProgreso(0)">Cancelar</v-btn>
    </v-stepper-content>

    <!-- Paso 4 -->
    <v-stepper-step :complete="porcentaje == 40" step="40" color='cyan darken-2'>Verificando Tarea
        <small>Se estan verificando las acciones realizadas</small>
    </v-stepper-step>

    <v-stepper-content step="40">
      <v-btn dark color="cyan darken-1" @click="updateProgreso(80)">Siguiente</v-btn>
      <v-btn flat @click="updateProgreso(15)">Cancelar</v-btn>
    </v-stepper-content>

    <!-- Paso 5 -->
    <v-stepper-step :complete="porcentaje == 80" step="80" color='cyan darken-2'>Finalizar Tarea</v-stepper-step>
    <v-stepper-content step="80">
      <v-btn dark @click="updateProgreso(100)" color="cyan darken-1">Terminar</v-btn>
      <v-btn flat @click="updateProgreso(40)">Cancelar</v-btn>
    </v-stepper-content>

    <v-stepper-step step="100" color='cyan darken-2'>Tarea Finalizada
    <small>Se han realizado todas las acciones necesarias</small></v-stepper-step>
  </v-stepper>
</template>

<script>
import axios from "axios";
export default {
  props: ['idTarea'],
  data() {
    return {
      idProgreso: null,
      porcentaje: '?'

    }
  },
  methods: {
    fetchProgreso() {
      var self = this
      
      axios.get('/tarea/progreso/verPorTarea/' + self.idTarea, {
          headers: {
            Authorization: 'Bearer ' + localStorage.getItem('token')
          }
        })
        .then(function (response) {
          if (response){
            self.porcentaje = response.data[0].porcentaje
            self.idProgreso = response.data[0].idavance
          }
        }).catch(function (err) {
          if (err.response && err.response.status === 401) {
            self.$store.commit('setExpired', true)
            self.$router.push('/logout')
          }
        })
      
    },
    updateProgreso(num) {
      var self = this
      if (self.idProgreso){
        axios.put(
            "/tarea/progreso/update/" + this.idProgreso, {
              porcentaje: num,
            }, {
              headers: {
                Authorization: "Bearer " + localStorage.getItem("token")
              }
            }
          )
          .then(function () {
            setTimeout(function () {
              self.fetchProgreso()
            }, 200);
          })
          .catch(error => {
            if (error.response.status === 401) {
              self.$store.commit('setExpired', true)
              self.$router.push('/logout')
            }
            this.fallo = true;
          });
      }else{
        axios.post(
            "/tarea/progreso/new", {
              porcentaje: num,
              idtarea: this.idTarea,
            }, {
              headers: {
                Authorization: "Bearer " + localStorage.getItem("token")
              }
            }
          )
          .then(function (response) {
            console.log(response.data) // eslint-disable-line no-console
            self.fetchProgreso()
          })
          .catch(error => {
            console.log(error.response) // eslint-disable-line no-console
          });
      }
    }
  },
  mounted() {
    this.fetchProgreso()
  }
}
</script>
