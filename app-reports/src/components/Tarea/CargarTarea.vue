<template>
<div>
    <v-layout row wrap v-for="(line, index) in lines" v-bind:key="index">
        <v-flex xs6 sm6>
            <v-select
            :rules="[v => !!v || 'Seleccione un área']"
            v-model="line.idarea"
            label="Área involucrada"
            :items="areas" 
            item-text="nombre" 
            item-value="idarea"
            hint="Seleccione el área que se encargará de realizar la tarea"/>
        </v-flex>
        <v-flex xs6 sm6>
            <v-text-field v-model="line.titulo" :counter="60" label="Título" :rules="[v => !!v || 'Ingrese un título']" required></v-text-field>
        </v-flex>
        <v-flex xs12 sm12>
            <v-textarea
            :rules='textareaRules'
            rows = '1'
            auto-grow
            v-model="line.descripcion" 
            label="Descripción de tarea" 
            hint="Especifique con detalles, que debe hacer el área seleccionada"
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
</div>
</template>

<script>
import axios from "axios";
export default {
  props: ['tarea'],
  data() {
    return {
      
      coleccionIdArea:[],
      areas: [],
      lines: [],
      blockRemoval: true,
      textareaRules: [v => !!v || "Es requerido que redacte un informe"],
    };
  },
  watch: {
    lines() {
      this.blockRemoval = this.lines.length <= 1;
    }
  },
  methods: {
    submit(idinforme) {
      var self=this
        self.lines.forEach(element => {
          
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
            }).then(function(response){
              var idarea= response.data.idarea
              var titulo= response.data.titulo
              var descripcion = response.data.descripcion
              var msj = 'Nueva tarea disponible.'
              self.enviarMail(idarea, titulo, descripcion, msj)
            })
          }
        });
        Object.assign(this.$data, this.$options.data())
        this.addLine()
        this.getAreas();
    },
    enviarMail(idarea, titulo, descripcion, msj){
      axios.post('informe/mail',{
        idarea: idarea,
        titulo: titulo,
        descripcion: descripcion,
        msj: msj
      },
      {
        headers: {
          Authorization: "Bearer " + localStorage.getItem("token")
        }
      }).then(function(){
        //console.log(msj)
      })
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
          if (error.response.status === 401){
            self.$store.commit('setExpired', true)
            self.$router.push('/logout')
          }
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
  }
};
</script>