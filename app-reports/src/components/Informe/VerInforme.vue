<template>
<v-container fluid grid-list-md v-if="informe && colorP" fill-height>
    <v-layout row wrap>
        <v-flex xs12 md3 flexbox>
            <v-card color="white">
                <v-flex pa-3 text-xs-center>
                    <span>
                        <v-chip label :color="colorP" text-color="white">Prioridad {{informe.prioridad.nombre}}</v-chip>
                    </span>
                    <v-divider class="mar"></v-divider>
                    <div class="font-weight-medium">HSEQ: {{informe.hseq.user.nombre}} {{informe.hseq.user.apellido}}</div>
                    <div>Creado: {{ moment(informe.create_at).format("ddd D MMMM YYYY") }}</div>
                    <div>Cantidad de tareas: {{informe.tarea.length}}</div>
                    <div>Estado: {{informe.estado.nombre}}</div>
                    <v-divider class="mar"></v-divider>
                    <div class="font-weight-medium">Zona: {{informe.zona.nombre}}</div>
                    <v-divider class="mar"></v-divider>
                    <div class="font-weight-bold">Fecha límite: <br>{{ moment(informe.fechalimite).format("dddd D MMMM YYYY")}}</div>
                </v-flex>
            </v-card>
        </v-flex>
        <v-flex xs12 md9 flexbox>
            <v-card color="white" height="100%">
                <v-flex pa-4>
                    <div class="headline text-xs-center text-uppercase font-weight-medium">{{informe.titulo}}</div>
                    <v-divider class="mar"></v-divider>
                    <div v-html="informe.descripcion"></div>
                </v-flex>
            </v-card>
        </v-flex>
        <v-flex xs12 flexbox>
            <v-card color="white" height="100%">
                <v-flex pa-4>
                    <div class="text-xs-center">
                        <v-badge color="cyan darken-3">
                            <span slot="badge">{{informe.tarea.length}}</span>
                            <div class="headline text-xs-center font-weight-medium">Tareas</div>
                        </v-badge>
                    </div>
                    <v-expansion-panel expand>
                        <v-expansion-panel-content v-for="tarea in informe.tarea" :key="tarea.idtarea">
                            <div slot="header">{{tarea.titulo}} <v-icon v-if="tarea.detalle" color="cyan darken-3">check_circle</v-icon></div>
                            
                            <v-card>
                                <v-card-text class="cyan lighten-5">
                                    <div><span class="font-weight-light font-italic">Descripción:</span></div>{{tarea.descripcion}}
                                    <v-divider class="mar"></v-divider>
                                    <div><span class="font-weight-light font-italic">Área encargada de la tarea:</span></div>{{tarea.area.nombre}}
                                    <v-divider class="mar"></v-divider>
                                    <div><span class="font-weight-light font-italic">Progreso:</span></div>
                                    <div class="text-xs-center">
                                        <v-progress-circular :rotate="180" :size="100" :width="15" :value="value" color="cyan darken-3">
                                            {{ value }}%
                                        </v-progress-circular>
                                    </div>
                                    <v-divider class="mar"></v-divider>
                                    <div v-if="tarea.detalle">
                                        <div><span class="font-weight-light font-italic">Respuesta:</span></div>{{tarea.detalle.descripcion}}
                                        <div class="caption font-italic text-xs-right">{{ moment(tarea.detalle.fecha_hora).format("lll")}}</div>
                                    </div>
                                </v-card-text>
                            </v-card>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                </v-flex>
            </v-card>
        </v-flex>
        <v-flex xs12 flexbox>
            <v-card color="white" height="100%">
                <v-flex pa-4>
                    <div class="headline text-xs-center font-weight-medium">Adjuntos</div>
                    <v-carousel>
                        <v-carousel-item
                            lazy
                            v-for="adjunto in informe.adjunto"
                            :key="adjunto.idadjunto"
                            :src="url + adjunto.url"
                            max-height="500"
                            contain
                            ></v-carousel-item>
                    </v-carousel>
                </v-flex>
            </v-card>
        </v-flex>
        <v-flex flexbox v-if="informe">
            <v-btn @click="dialog = true" :disabled="!tareasCompletas()" color="cyan darken-3" flat>Cerrar informe</v-btn>
            <v-dialog v-model="dialog" max-width="290">
                <v-card>
                    <v-card-title class="headline text-xs-center">¿Desea cerrar el informe actual?</v-card-title>
                    <v-card-actions class="justify-center">
                        <v-btn color="cyan darken-3" flat="flat" @click="dialog = false, cerrarInforme()">
                            Si
                        </v-btn>
                        <v-btn color="cyan darken-3" flat="flat" @click="dialog = false">
                            Cencelar
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-flex>
    </v-layout>
</v-container>
</template>
<script>
import axios from "axios";
import moment from "moment";
export default {
  props: ["idInforme"],
  data() {
    return {
      dialog: false,
      moment: moment,
      informe: null,
      colorP: null,
      value: 100,
      valid: false,
      cerrar: false,
      url: this.$storageURL
    };
  },
  created() {
    moment.locale("es");
    this.fetchInforme();
  },
  methods: {
    colorPrioridad(prioridad) {
      if (prioridad === 1) {
        this.colorP = "teal darken-4";
      } else if (prioridad === 2) {
        this.colorP = "yellow darken-4";
      } else if (prioridad === 3) {
        this.colorP = "red darken-4";
      }
    },
    cerrarInforme(){
        var self = this;
        axios.get("/informe/cerrar/"+ self.informe.idinforme, {
          headers: {
            Authorization: "Bearer " + localStorage.getItem("token")
          }
        })
        .then(function(response){
            console.log(response.data)
            self.fetchInforme()
        }).catch(function(err) {
            console.log(err.response)
        })
    },
    fetchInforme() {
      var self = this;
      axios.get("/informe/ver/" + self.idInforme, {
          headers: {
            Authorization: "Bearer " + localStorage.getItem("token")
          }
        })
        .then(function(response) {
          self.informe = response.data;
          self.colorPrioridad(self.informe.prioridad.idprioridad);
        })
        .catch(function(err) {
          if (err.response.status === 401) {
            self.$store.commit("setExpired", true);
            self.$router.push("/logout");
          }
        });
    },
    tareasCompletas(){
        var self= this
        self.informe.tarea.forEach(element => {
            if (element.detalle !== null){
                 self.cerrar = true
                 console.log(element.detalle)
            }else{
                self.cerrar = false
            }
        });
        return self.cerrar
    }
  }
};
</script>
<style>
.mar {
  margin-top: 0.5em;
  margin-bottom: 0.5em;
}
</style>
