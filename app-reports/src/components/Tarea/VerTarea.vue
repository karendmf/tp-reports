<template>
<v-container fluid grid-list-md  v-if="tarea">
<v-layout row wrap>
    <v-flex xs12>
        <v-card>
            <v-flex pa-1 text-xs-center>
                <h2 >Detalle de informe</h2>
            </v-flex>
        </v-card>
    </v-flex>
    <v-flex xs12 md3>
        <v-card color="white">
                <v-flex pa-3 text-xs-center>
                    <span>
                        <v-chip label :color="colorPrioridad(tarea.informe.prioridad.idprioridad)" text-color="white">Prioridad {{tarea.informe.prioridad.nombre}}</v-chip>
                    </span>
                    <div>Creado: {{ moment(tarea.informe.create_at).format("ddd D MMMM YYYY") }}</div>
                    <v-divider class="mar"></v-divider>
                    <div class="font-weight-medium">Zona: {{tarea.informe.zona.nombre}}</div>
                    <v-divider class="mar"></v-divider>
                    <div class="font-weight-bold">Fecha límite: <br>{{ moment(tarea.informe.fechalimite).format("dddd D MMMM YYYY")}}</div>
                </v-flex>
            </v-card>
    </v-flex>
    <v-flex xs12 md9 flexbox>
            <v-card color="white" height="100%">
                <v-flex pa-4>
                    <div class="headline text-xs-center text-uppercase font-weight-medium">{{tarea.informe.titulo}}</div>
                    <v-divider class="mar"></v-divider>
                    <div v-html="tarea.informe.descripcion"></div>
                </v-flex>
            </v-card>
    </v-flex>
    <v-flex xs12>
      <v-card>
        <v-container grid-list-sm fluid>
          <v-layout row wrap>
            <v-carousel>
                <v-carousel-item
                    lazy
                    v-for="adjunto in tarea.informe.adjunto"
                    :key="adjunto.idadjunto"
                    :src="url + adjunto.url"
                    max-height="550"
                    contain
                    ></v-carousel-item>
                </v-carousel>
          </v-layout>
        </v-container>
      </v-card>
    </v-flex>
    <v-flex xs12 mt-3>
        <v-card>
            <v-flex pa-1 text-xs-center>
                <h2 >Tarea a realizar</h2>
            </v-flex>
        </v-card>
    </v-flex>
    <v-flex xs12 md6>
       <v-card >
           
            <v-flex pa-4 >
                <div class="headline text-xs-center text-uppercase font-weight-medium">{{tarea.titulo}}</div>
                <v-divider class="mar"></v-divider>
                <div>{{tarea.descripcion}}</div>
            </v-flex>
            <v-card-actions v-if="tarea.detalle" text-xs-center>
                <v-flex text-xs-center>
                    <v-chip  label color="cyan darken-1" text-color="white">Tarea concluida</v-chip>
                </v-flex>
            </v-card-actions>
            <v-card-actions v-if="!tarea.detalle"> 
                <v-btn v-show="respuesta == false" flat block color="cyan darken-1" @click="respuesta = true">Responder</v-btn>
                <v-btn v-show="respuesta == true" flat block color="cyan darken-1" @click="respuesta = false">Cancelar</v-btn>
            </v-card-actions>
        </v-card>
    </v-flex>
        <v-flex xs12 md6 v-if="tarea.detalle">
            <v-card height="100%">
                <v-flex pa-4 >
                <div class="headline text-xs-center text-uppercase font-weight-medium">Respuesta</div>
                <div>{{ moment(tarea.detalle.fecha_hora).format("lll")}}</div>
                <v-divider class="mar"></v-divider>
                <div>{{tarea.detalle.descripcion}}</div>
                </v-flex>
            </v-card>
        </v-flex>
        <div class="flex xs12 md6">
            <div class="v-card theme--light">
                <responder v-if="respuesta && !tarea.detalle" ref="responder" :idTarea='tarea.idtarea'></responder>
            </div>
       </div>
</v-layout>
</v-container>
</template>

<script>
import axios from 'axios';
import moment from 'moment';
import responder from '@/components/Tarea/ResponderTarea'
export default {
    props: ['idTarea'],
    data(){
        return{
            respuesta: false,
            moment: moment,
            tarea: null,
            url: this.$storageURL
        }
    },
    components:{
        responder
    },
    created() {
        moment.locale('es')
        this.fetchTarea();
    },
    methods:{
        fetchTarea(){
            var self = this
            axios.get('/tarea/ver/' + self.idTarea, {
                headers: {
                    Authorization: 'Bearer ' + localStorage.getItem('token')
                }
            })
            .then(function(response){
                self.tarea = response.data
                console.log(self.tarea)
            }).catch(function (err) {
                if (err.response.status === 401){
                    self.$store.commit('setExpired', true)
                    self.$router.push('/logout')
                }
            })
        },
        colorPrioridad(prioridad){
            if(prioridad=== 1){
                return 'teal darken-4'
            }else if(prioridad === 2){
                return 'yellow darken-4'
            }else if(prioridad === 3){
                return 'red darken-4'
            }
        }
        
    },
}
</script>
<style>
.mar{
    margin-top: 0.5em;
    margin-bottom: 0.5em;
}
</style>
