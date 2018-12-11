<template>
<v-container fluid grid-list-md v-if="informe && colorP" fill-height id="imprimir">
    
    <v-layout row wrap >
        <!-- Alerta si el informe vencio -->
        <v-flex xs12 v-if="(moment().utcOffset(-3).format('DD/MM/YYYY') > moment(informe.fechalimite).utcOffset(-3).format('DD/MM/YYYY')) && informe.estado.idestado == 1">
            <v-alert v-model="alert" dismissible type="warning" color="cyan darken-3">
                Este informe se encuentra fuera de la fecha límite.
            </v-alert>
        </v-flex>
        <!-- Dialogo para editar informe -->

        <v-dialog v-model="dialogEditInforme" persistent max-width="800px" v-if="informe.idestado===1">
            
            <v-card>
                <v-form ref="form" v-model="valid" lazy-validation>
                    <v-card-title>
                        <span class="headline">Editar Informe</span>
                    </v-card-title>
                    <v-card-text>
                        <v-container grid-list-md>
                            <!-- Alerta de error -->
                            <v-alert v-model="fallo" dismissible color="error" icon="warning" if='error' outline>
                                Error al actualizar informe
                            </v-alert>
                            <v-layout wrap>
                                <v-flex xs12 sm12 md6>
                                    <!-- título -->
                                    <v-text-field label="Título" v-model='titulo' :rules="tituloRules" :counter="60" required
                                        :value="`${informe.titulo}`"></v-text-field>
                                </v-flex>
                                <v-flex xs12 sm12 md6>
                                    <!-- fecha límite -->
                                    <v-menu :close-on-content-click="true" v-model="fechalimite" :nudge-right="40" lazy
                                        transition="scale-transition" offset-y full-width min-width="290px">
                                        <v-text-field required clearable :rules="dateRules" slot="activator" label="Seleccione la fecha límite"
                                            prepend-icon="event" readonly :value="formato"></v-text-field>
                                        <v-date-picker v-model="date" @input="fechalimite = false" locale="es-mx" color="cyan lighten-3"
                                            :min='moment(new Date()).format("YYYY-MM-DD")'></v-date-picker>
                                    </v-menu>
                                </v-flex>
                                <v-flex xs12 sm12 md12>
                                    <!-- descripción -->
                                    <vue-editor v-model="descripcion" :editorToolbar='customToolbar' :value="`${informe.descripcion}`"></vue-editor>
                                </v-flex>
                                <v-flex xs12 sm6>
                                    <!-- zonas select -->
                                    <v-select :items="zonas" v-model="idzona" label="Zona" :rules="[v => !!v || 'Se requiere ingresar la zona']"
                                        item-text="nombre" item-value="idzona"></v-select>
                                </v-flex>
                                <v-flex xs12 sm6>
                                    <!-- prioridad select -->
                                    <v-select v-model="idprioridad" :items="prioridad" :rules="[v => !!v || 'Se requiere ingresar la prioridad']"
                                        label="Prioridad" item-text="nombre" item-value="idprioridad" required></v-select>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <!-- Botones para cancelar o guardar la edición de informe -->
                        <v-btn color="cyan darken-2" flat @click="dialogEditInforme = false">Cancelar</v-btn>
                        <v-btn :disabled="!valid" @click.prevent="editarInforme() && (dialogEditInforme = false)" color="cyan darken-2"
                            flat>Guardar</v-btn>
                    </v-card-actions>
                </v-form>
            </v-card>
        </v-dialog>
        <!-- Fin dialogo para editar -->
        <!-- Card de información -->
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
                    
                    <v-divider class="mar"></v-divider>
                    <div v-if="historial" class="caption">Ultima modificación: <br>{{moment(historial.fecha_hora).format("DD/MM/YYYY, h:mm:ss a")}}<br>Por {{historial.hseq.user.apellido}} {{historial.hseq.user.nombre}}</div>
                    <v-divider class="mar" v-if="quienCerro"></v-divider>
                    <div v-if="quienCerro">Cerrado el {{moment(quienCerro.fecha_hora).format("DD/MM/YYYY")}}<br>Por {{quienCerro.hseq.user.apellido}} {{quienCerro.hseq.user.nombre}}</div>
                    <!-- Btn dialogo editar informe -->
                    <div class="text-xs-center" v-if="informe.idestado===1">
                    <v-btn slot="activator" color="blue lighten-1" dark @click="dialogEditInforme = true" small>
                        <v-icon>edit</v-icon>
                    </v-btn>
                    </div>
                    <div v-if="informe.idestado===2">
                        <v-btn color="cyan darken-2" dark @click="download()">Descargar PDF</v-btn>
                    </div>
                </v-flex>
            </v-card>
        </v-flex>
        <!-- Card título y descripción -->
        <v-flex xs12 md9 flexbox>
            <v-card color="white" height="100%">
                <v-flex pa-4> 
                    <div class="headline text-xs-center text-uppercase font-weight-medium">{{informe.titulo}}</div>
                    <v-divider class="mar"></v-divider>
                    <div v-html="informe.descripcion"></div>
                </v-flex>
            </v-card>
        </v-flex>
        <!-- Tareas -->
        <v-flex xs12 flexbox>
            <v-card color="white" height="100%">
                <v-flex pa-4>
                    <div class="text-xs-center">
                        <v-badge color="cyan darken-3">
                            <span slot="badge">{{informe.tarea.length}}</span>
                            <div class="headline text-xs-center font-weight-medium">Tareas</div>
                        </v-badge>
                    </div>
                    <v-expansion-panel expand v-if="informe.tarea.length > 0">
                        <!-- paneles de expanción. Se recorre la colección de tareas que contiene el informe -->
                        <v-expansion-panel-content v-for="tarea in informe.tarea" :key="tarea.idtarea">
                            <div slot="header">{{tarea.titulo}} <v-icon v-if="tarea.detalle" color="cyan darken-3">check_circle</v-icon>
                            </div>
                            <v-card>
                                <!-- detalles de la tarea -->
                                <v-card-text class="cyan lighten-5">
                                    <div><span class="font-weight-light font-italic">Descripción:</span></div>{{tarea.descripcion}}
                                    <v-divider class="mar"></v-divider>
                                    <div><span class="font-weight-light font-italic">Área encargada de la tarea:</span></div>{{tarea.area.nombre}}
                                    <v-divider class="mar"></v-divider>
                                    <!-- barra de progreso de tarea -->
                                    <div v-if="tarea.progreso">
                                    <div><span class="font-weight-light font-italic">Progreso:</span></div>
                                            <div class="text-xs-center">
                                                <v-progress-circular :rotate="180" :size="100" :width="15" :value="tarea.progreso.porcentaje" color="cyan darken-3">
                                                    {{ tarea.progreso.porcentaje }}%
                                                </v-progress-circular>
                                            </div>
                                            <v-divider class="mar"></v-divider>
                                    </div>
                                    <div v-if="tarea.detalle">
                                        <div><span class="font-weight-light font-italic">Respuesta:</span></div>{{tarea.detalle.descripcion}}
                                        <div class="caption font-italic text-xs-right">{{ moment(tarea.detalle.fecha_hora).format("lll")}}</div>
                                    </div>
                                </v-card-text>
                                <v-card-actions>
                                    <!-- Btn editar tarea seleccionada -->
                                    <v-btn right color="blue lighten-1" dark style="margin-left:1em" @click='openEditarTarea(tarea)'
                                        v-if="informe.idestado===1 && !tarea.detalle" small>
                                        <v-icon>edit</v-icon>
                                    </v-btn>

                                    <!-- Btn eliminar tarea seleccionada -->
                                    <v-btn color="red lighten-1" dark @click="openEliminarTarea(tarea)" v-if="informe.idestado===1 && !tarea.detalle  && !tarea.progreso" small>
                                        <v-icon>delete</v-icon>
                                    </v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
					<div v-if="informe.tarea.length == 0">
						<h4>No hay tareas ingresadas.</h4>
					</div>
                    <!-- Dialogo para editar tareas -->
                    <v-dialog v-model="dialogEditTarea" persistent max-width="700px" v-if="tareaSeleccionada">
                        <v-card>
                            <v-card-title>
                                <span class="headline">Editar Tarea</span>
                            </v-card-title>
                            <v-card-text>
                                <v-flex xs12>
                                    <h4>Tarea: {{ tareaSeleccionada.titulo}}</h4>
                                    <v-flex xs12 sm12>
                                        <v-select v-model="tareaSeleccionada.idarea" label="Área involucrada" :items="areas"
                                            item-text="nombre" item-value="idarea" hint="Seleccione el área que se encargará de realizar la tarea" />
                                    </v-flex>
                                    <v-flex xs12 sm12>
                                        <v-text-field v-model="tareaSeleccionada.titulo" :counter="60" label="Título" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm12>
                                        <v-textarea :rules='textareaRules' auto-grow v-model="tareaSeleccionada.descripcion"
                                            label="Descripción de tarea" hint="Especifique con detalles, que debe hacer el área seleccionada"
                                            required></v-textarea>
                                    </v-flex>
                                </v-flex>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="cyan darken-2" flat @click="dialogEditTarea = false">Cancelar</v-btn>
                                <v-btn color="cyan darken-2" flat @click.prevent="actualizarTarea(tareaSeleccionada)">Guardar</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                    <!-- Fin dialogo para editar tareas -->
                    <v-dialog v-model="dialogEliminarTarea" max-width="290" v-if="tareaSeleccionada">
                        <v-card>
                            <v-card-title class="headline text-xs-center">¿Desea eliminar la tarea?</v-card-title>
                            <v-card-actions class="justify-center">
                                <v-btn color="cyan darken-3" flat="flat" @click="dialogEliminarTarea = false, eliminarTarea(tareaSeleccionada.idtarea)">
                                    Si
                                </v-btn>
                                <v-btn color="cyan darken-3" flat="flat" @click.prevent="dialogEliminarTarea = false">
                                    Cencelar
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                    <br>
                    <!-- Dialogo para agregar una nueva tarea -->
                    <div class="text-xs-right" v-if="informe.idestado===1">
                        <v-dialog v-model="dialogAddTarea" persistent max-width="800px">
                            <v-btn slot="activator" fab small color="green lighten-1" dark>
                                <v-icon>add</v-icon>
                            </v-btn>
                            <v-card>
                                <v-card-title class="headline" primary-title>
                                    Agregar nuevas tareas
                                </v-card-title>
                                <v-card-text>
                                    <tarea ref="tarea"></tarea>
                                </v-card-text>
                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn color="cyan darken-2" flat @click="dialogAddTarea = false">Cancelar</v-btn>
                                    <v-btn :disabled="!valid" @click="agregarTarea() && (dialogAddTarea = false)" color="cyan darken-2"
                                        flat>Guardar</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </div>
                    <!-- Fin dialogo para agregar una nueva tarea -->
                </v-flex>
            </v-card>
        </v-flex>
        <!-- Fin tareas -->
        <!-- Imagenes -->
        <v-flex xs12 flexbox>
            <v-card color="white" height="100%">
                <!-- carousel de img de informe -->
                <v-flex pa-4>
                    <div class="headline text-xs-center font-weight-medium">Imágenes</div>
                    <v-carousel light hide-delimiters v-if="informe.adjunto.length > 0">
                        <v-carousel-item lazy v-for="adjunto in informe.adjunto" :key="adjunto.idadjunto" :src="url + adjunto.url"
                            max-height="500" contain></v-carousel-item>
                    </v-carousel>
					<div v-if="informe.adjunto.length == 0">
						<h4>No hay imágenes</h4>
					</div>
                </v-flex>
                <v-card-actions class="justify-end mb-2" v-if="informe.idestado===1">
                    <!-- Btn abre el dialogo para agregar img -->
                    <v-btn small color="green lighten-1" dark fab @click="dialogAddImg = true">
                        <v-icon>add</v-icon>
                    </v-btn>
                    <!-- Dialogo agregar img -->
                    <v-dialog v-model="dialogAddImg" width="600">
                        <v-card>
                            <v-card-title class="headline" primary-title>
                                Agregar imágenes
                            </v-card-title>
                            <v-card-text>
                                <vue-dropzone ref="dropzone" id="dropzone" :options="dropzoneOptions" v-on:vdropzone-sending="sendingEvent">
                                </vue-dropzone>
                            </v-card-text>
                            <v-divider></v-divider>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="cyan darken-2" flat @click="dialogAddImg = false">
                                    Cancelar
                                </v-btn>
                                <v-btn color="cyan darken-2" flat @click="cargarImagenes()">
                                    Agregar
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>

                    <!-- Btn para abrir dialogo de mostrar img a eliminar -->
                    <v-btn small class="mr-2 ml-2" color="red lighten-1" dark fab @click="dialogMostrarImg = true">
                        <v-icon>delete</v-icon>
                    </v-btn>
                    <!-- Dialogo de img a eliminar -->
                    <v-dialog v-model="dialogMostrarImg" max-width="700px">
                        <v-card>
                            <v-container grid-list-sm fluid>
                                <v-layout row wrap>
                                    <v-flex v-for="(adjunto, index) in informe.adjunto" :key="adjunto.idadjunto" xs4 d-flex>
                                        
                            <v-card flat tile class="d-flex">
                                <v-img
                                :id="'img' + index"
                                :src="url + adjunto.url"
                                :lazy-src="url + adjunto.url"
                                aspect-ratio="1"
                                class="grey lighten-2"
                                >
                                <v-layout
                                    slot="placeholder"
                                    fill-height
                                    align-center
                                    justify-center
                                    ma-0
                                >
                                    <v-progress-circular indeterminate color="grey lighten-5"></v-progress-circular>
                                </v-layout>
                                <!-- Btn de eliminar imágen -->
                                <div class="text-xs-right">
                                    <v-btn fab small color="red lighten-1" dark @click="openDeleteImg(adjunto.idadjunto)"><v-icon>clear</v-icon></v-btn>
                                </div>
                                </v-img>
                            </v-card>
                        </v-flex>
                        <!-- Dialogo para confirmar la eliminarción de imágen -->
                        <v-dialog v-model="dialogDeleteImg" max-width="290" v-if="idImg">
                                        <v-card>
                                            <v-card-title class="headline text-xs-center">¿Desea eliminar la imágen?</v-card-title>
                                            <v-card-actions class="justify-center">
                                                <v-btn color="cyan darken-3" flat="flat" @click="eliminarImagen(idImg)">
                                                    Si
                                                </v-btn>
                                                <v-btn color="cyan darken-3" flat="flat" >
                                                    Cencelar
                                                </v-btn>
                                            </v-card-actions>
                                        </v-card>
                                    </v-dialog>
                                </v-layout>
                            </v-container>
                        <v-card-actions class="justify-end mr-3">
                            <v-btn color="cyan darken-1" dark @click="dialogMostrarImg = false">
                            Cerrar
                            </v-btn>
                        </v-card-actions>
                        </v-card>
                        
                    </v-dialog>
                </v-card-actions>
            </v-card>
           
        </v-flex>
        <!-- Fin imagenes -->
        <!-- Alerta si esta cerrado -->
        <v-flex>
            <v-alert :value="true" type="info" v-if="informe.idestado===2">
                Informe cerrado.
            </v-alert>
        </v-flex>
        <!-- Botón para cerrar informe -->
        
        <v-flex flexbox v-if="informe && informe.idestado==1">
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
    
    <!-- Alerta flotante -->
    <v-snackbar v-model="snackbar" top right multi-line="multi-line" :timeout="4000">
        {{ textSnack }}
        <v-btn color="cyan darken-1" dark flat @click="snackbar = false">
            Cerrar
        </v-btn>
    </v-snackbar>
</v-container>
</template>
<script src="./JS_verInforme.js">

</script>
<style>
.mar {
  margin-top: 0.5em;
  margin-bottom: 0.5em;
}

</style>
