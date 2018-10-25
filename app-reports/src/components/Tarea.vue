<template>
<v-container fluid grid-list-md>
    <v-layout row wrap v-for="(line, index) in lines" v-bind:key="index">
        <v-flex xs5 sm3>
            <v-select
            v-model="line.idarea"
            label="Area involucrada"
            :items="areas" 
            item-text="nombre" 
            item-value="idarea"/>
        </v-flex>
        <v-flex xs7 sm6>
            <v-text-field 
            v-model="line.tarea" 
            label="Descripción de tarea" 
            hint="Especifique con detalles, que debe hacer el area seleccionada"
            required></v-text-field>
        </v-flex>
        <v-flex xs12 sm3>
          <v-tooltip bottom>
            <v-btn slot="activator"
            v-if="index + 1 === lines.length" @click="addLine" fab dark small color="cyan darken-1">
                <v-icon>add</v-icon>
            </v-btn>
            <span>Agregar otra tarea</span>
          </v-tooltip>
          <v-tooltip bottom>
            <v-btn slot="activator"
            @click="removeLine(index)" fab dark small color="red">
                <v-icon>close</v-icon>
            </v-btn>
            <span>Eliminar tarea</span>
          </v-tooltip>
        </v-flex>
    </v-layout>
</v-container>
</template>

<script>
import axios from "axios";
export default {
  name: "Tarea",
  data() {
    return {
      areas: [],
      lines: [],
      blockRemoval: true
    };
  },
  watch: {
    lines() {
      this.blockRemoval = this.lines.length <= 1;
    }
  },
  methods: {
    getAreas() {
      var self = this;
      axios.get("/area/ver", {
          headers: {
            Authorization: "Bearer " + localStorage.getItem("token")
          }
        })
        .then(function(response) {
          self.areas = response.data;
          console.log(self.areas);
        })
        .catch(error => {
          console.log(error.response);
        });
    },
    addLine() {
      let checkEmptyLines = this.lines.filter(line => line.tarea === null);
      if (checkEmptyLines.length >= 1 && this.lines.length > 0) return;
      this.lines.push({
        idarea: null,
        tarea: null
      });
    },
    removeLine(lineId) {
      if (!this.blockRemoval) this.lines.splice(lineId, 1);
    }
  },
  mounted() {
    this.addLine();
    this.getAreas();
  },
  beforeCreate() { 
        if (!this.$store.state.isLogged) { 
        this.$router.push('/signin') 
        } 
    },
};
</script>