<template>
<div>
    <v-layout row wrap v-for="(line, index) in lines" v-bind:key="index">
        <v-flex xs6 sm6>
            <v-select
            v-model="line.idarea"
            label="Área involucrada"
            :items="areas" 
            item-text="nombre" 
            item-value="idarea"
            hint="Seleccione el área que se encargará de realizar la tarea"/>
        </v-flex>
        <v-flex xs6 sm6>
            <v-text-field v-model="line.titulo" :counter="60" label="Título" required></v-text-field>
        </v-flex>
        <v-flex xs12 sm12>
            <v-textarea
            rows = '1'
            auto-grow
            v-model="line.descripcion" 
            label="Descripción de tarea" 
            hint="Especifique con detalles, que debe hacer el area seleccionada"
            required></v-textarea>
        </v-flex>
        <v-flex xs6 sm6>
            <v-btn slot="activator"
            v-if="index + 1 === lines.length" @click="addLine" fab dark small color="cyan darken-1" outline>
                <v-icon>add</v-icon>
            </v-btn>
            
          
            <v-btn slot="activator"
            @click="removeLine(index)" fab dark small color="red" outline>
                <v-icon>close</v-icon>
            </v-btn>
          
        </v-flex>
    </v-layout>
    <!-- <v-btn @click="submit" outline color="cyan darken-1">
            Enviar
    </v-btn> -->
</div>
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
    submit(idinforme) {
      this.lines.forEach(element => {
        if (element.titulo !== null && element.descripcion !== null) {
          axios.post('/tarea/new', {
            idarea: element.idarea,
            idinforme: idinforme,
            titulo: element.titulo,
            descripcion: element.descripcion,
          }, {
            headers: {
              Authorization: "Bearer " + localStorage.getItem("token")
            }
          })
        }
      });
    },
    getAreas() {
      var self = this;
      axios.get("/area/ver", {
          headers: {
            Authorization: "Bearer " + localStorage.getItem("token")
          }
        })
        .then(function (response) {
          self.areas = response.data;
          //console.log(self.areas);
        })
        .catch(error => {
          console.log(error.response);
        });
    },
    addLine() {
      let checkEmptyLines = this.lines.filter(line => line.descripcion === null);
      if (checkEmptyLines.length >= 1 && this.lines.length > 0) return;
      this.lines.push({
        idarea: null,
        descripcion: null,
        titulo: null,
        idinforme: null
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