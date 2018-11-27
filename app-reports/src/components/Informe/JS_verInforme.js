import {
    VueEditor
} from "vue2-editor";
import axios from "axios";
import moment from "moment";
import tarea from '@/components/Tarea/CargarTarea';
import vue2Dropzone from "vue2-dropzone";
export default {
    props: ["idInforme"],
    data() {
        return {
            idImg: undefined,
            dialogDeleteImg: null,
            dialogMostrarImg: null,
            dialogAddImg: null,
            areas: [],
            textareaRules: [v => !!v || "Es requerido que redacte un informe"],
            textSnack: null,
            descripcion: null,
            snackbar: false,
            titulo: '',
            tituloRules: [
                v => !!v || "Se requiere un título",
                v =>
                (v && v.length <= 60) || "El título debe tener menos de 60 caracteres"
            ],
            valid: true,
            dialogEditTarea: null,
            tareaSeleccionada: undefined,
            prioridad: [],
            idprioridad: null,
            zonas: [],
            idzona: null,
            fechalimite: false,
            dateRules: [v => !!v || "Se requiere una fecha limite"],
            date: null,
            alert: true,
            dialog: false,
            dialogEditInforme: false,
            dialogAddTarea: false,
            dialogEliminarTarea: false,
            moment: moment,
            informe: null,
            colorP: null,
            value: 100,
            cerrar: true,
            url: this.$storageURL,
            customToolbar: [
                ["bold", "italic", "underline", "strike"],
                [{
                        list: "ordered"
                    },
                    {
                        list: "bullet"
                    }
                ],
                [{
                    header: [1, 2, 3, 4, 5, 6, false]
                }],
                [{
                    align: []
                }],
                [{
                        indent: "-1"
                    },
                    {
                        indent: "+1"
                    }
                ], // outdent/indent
                [{
                    size: ["small", false, "large", "huge"]
                }], // custom dropdown
                [{
                        color: []
                    },
                    {
                        background: []
                    }
                ], // dropdown with defaults from theme
                [{
                    font: []
                }]
            ],
            fallo: false,
            dropzoneOptions: {
                url: axios.defaults.baseURL + "/informe/img/new",
                maxFiles: 4,
                addRemoveLinks: true,
                thumbnailWidth: 140,
                maxFilesize: 1,
                autoProcessQueue: false,
                acceptedFiles: 'image/*',
                dictDefaultMessage: '<h2>Arrastre las imágenes aquí</h2><h4>O haga click para seleccionar desde una carpeta</h4>',
                dictRemoveFile: 'Descartar',
                dictMaxFilesExceeded: 'Solo se pueden adjuntar 4 imágenes',
                dictFileTooBig: 'El archivo es muy grande. {{maxFilesize}}MB Max',
                headers: {
                  Authorization: "Bearer " + localStorage.getItem("token"),
                  "Cache-Control": "",
                  "X-Requested-With": ""
                }
              }
        };

    },
    computed: {
        formato() {
            return this.date ? moment(this.date).format('ddd D MMM YYYY') : ''
        }
    },
    components: {
        tarea,
        VueEditor,
        vueDropzone: vue2Dropzone
    },
    created() {
        moment.locale("es");
        this.fetchInforme();
    },
    mounted() {
        this.getZonas();
        this.getPrioridad();
        this.getAreas();
    },
    methods: {
        sendingEvent(file, xhr, formData) {
            formData.append('idinforme', this.idInforme)
        },
        cargarImagenes(){
            var self = this
            var count = self.$refs.dropzone.getQueuedFiles().length
            if(count > 0){
                self.$refs.dropzone.processQueue()
                self.snackbar = true
                self.textSnack = 'Imágenes agregadas.'
                setTimeout(function(){
                    self.$refs.dropzone.removeAllFiles()
                    self.dialogAddImg = false
                    self.fetchInforme()
                }, 4500);
            }else{
                self.snackbar = true
                self.textSnack = 'No se seleccionaron imágenes.'
            }
        },
        openDeleteImg(idimagen){
            var self = this
            self.idImg = idimagen
            self.dialogDeleteImg = true
        },
        eliminarImagen(idimagen){
            var self = this
            axios.delete('/informe/img/delete/' + idimagen, {
                headers: {
                    Authorization: "Bearer " + localStorage.getItem("token")
                }
            }).then(function () {
                self.snackbar = true
                self.textSnack = 'Imágen eliminada.'
                self.dialogDeleteImg = false
                setTimeout(function(){
                    self.fetchInforme()
                  }, 200);
            })
        },
        eliminarTarea(idTarea) {
            var self = this
            axios.delete('/tarea/delete/' + idTarea, {
                headers: {
                    Authorization: "Bearer " + localStorage.getItem("token")
                }
            }).then(function () {
                self.snackbar = true
                self.textSnack = 'Tarea eliminada.'
                self.dialogEliminarTarea = false
                setTimeout(function(){
                    self.fetchInforme()
                  }, 200);
            })
        },
        openEditarTarea(tarea) {
            var self = this
            self.tareaSeleccionada = tarea
            self.dialogEditTarea = true
        },
        openEliminarTarea(tarea) {
            var self = this
            self.tareaSeleccionada = tarea
            self.dialogEliminarTarea = true
        },
        actualizarTarea(tarea) {
            var self = this
            if (tarea.titulo !== null && tarea.descripcion !== null) {
                axios.put('/tarea/update/' + tarea.idtarea, {
                    idarea: tarea.idarea,
                    titulo: tarea.titulo,
                    descripcion: tarea.descripcion,
                }, {
                    headers: {
                        Authorization: "Bearer " + localStorage.getItem("token")
                    }
                }).then(function () {
                    self.snackbar = true
                    self.textSnack = 'Tarea actualizada.'
                    self.dialogEditTarea = false
                })
            }
        },
        agregarTarea() {
            var self = this
            self.$refs.tarea.submit(this.idInforme)
            self.snackbar = true
            self.textSnack = 'Se agregaron tareas al informe.'
            self.dialogAddTarea = false
            setTimeout(function(){
                self.fetchInforme()
            }, 200);
        },
        editarInforme() {
            var self = this
            if (self.descripcion.length == 0) {
                self.snackbar = true
                self.textSnack = 'Es requerido que redacte un informe'
            } else {
                if (this.$refs.form.validate()) {
                    axios.put(
                            "/informe/update/" + this.idInforme, {
                                titulo: this.titulo,
                                fechalimite: this.date,
                                descripcion: this.descripcion,
                                idzona: parseInt(this.idzona),
                                idprioridad: parseInt(this.idprioridad),
                            }, {
                                headers: {
                                    Authorization: "Bearer " + localStorage.getItem("token")
                                }
                            }
                        )
                        .then(function (response) {
                            self.idinforme = response.data.idinforme
                            self.snackbar = true
                            self.textSnack = 'Informe actualizado'
                            self.dialogEditInforme = false
                            setTimeout(function(){
                                self.fetchInforme()
                            }, 200);
                        })
                        .catch(error => {
                            if (error.response.status === 401) {
                                self.$store.commit('setExpired', true)
                                self.$router.push('/logout')
                            }
                            this.fallo = true;
                        });
                } else {
                    this.fallo = true;
                }
            }
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
                    if (error.response.status === 401) {
                        self.$store.commit('setExpired', true)
                        self.$router.push('/logout')
                    }
                });
        },
        getZonas() {
            var self = this;
            axios.get("/zona/ver", {
                    headers: {
                        Authorization: "Bearer " + localStorage.getItem("token")
                    }
                })
                .then(function (response) {
                    self.zonas = response.data;
                    //console.log(self.zonas)
                })
                .catch(error => {
                    if (error.response.status === 401) {
                        self.$store.commit('setExpired', true)
                        self.$router.push('/logout')
                    }
                });
        },
        getPrioridad() {
            var self = this;
            axios.get("/prioridad/ver", {
                    headers: {
                        Authorization: "Bearer " + localStorage.getItem("token")
                    }
                })
                .then(function (response) {
                    self.prioridad = response.data;
                    //console.log(self.prioridad)
                })
                .catch(error => {
                    if (error.response.status === 401) {
                        self.$store.commit('setExpired', true)
                        self.$router.push('/logout')
                    }
                });
        },
        colorPrioridad(prioridad) {
            if (prioridad === 1) {
                this.colorP = "teal darken-4";
            } else if (prioridad === 2) {
                this.colorP = "yellow darken-4";
            } else if (prioridad === 3) {
                this.colorP = "red darken-4";
            }
        },
        cerrarInforme() {
            var self = this;
            axios.get("/informe/cerrar/" + self.informe.idinforme, {
                    headers: {
                        Authorization: "Bearer " + localStorage.getItem("token")
                    }
                })
                .then(function (response) {
                    console.log(response.data) // eslint-disable-line no-console
                    self.fetchInforme()
                }).catch(function (err) {
                    console.log(err.response) // eslint-disable-line no-console
                })
        },
        fetchInforme() {
            var self = this;
            axios.get("/informe/ver/" + self.idInforme, {
                    headers: {
                        Authorization: "Bearer " + localStorage.getItem("token")
                    }
                })
                .then(function (response) {
                    self.informe = response.data;
                    self.date = self.informe.fechalimite
                    self.idzona = self.informe.zona.idzona
                    self.idprioridad = self.informe.prioridad.idprioridad
                    self.titulo = self.informe.titulo
                    self.descripcion = self.informe.descripcion
                    self.colorPrioridad(self.informe.prioridad.idprioridad);
                })
                .catch(function (err) {
                    if (err.response.status === 401) {
                        self.$store.commit("setExpired", true);
                        self.$router.push("/logout");
                    }
                });
        },
        tareasCompletas() {
            var self = this
            var tareas = self.informe.tarea
            for (let i = 0; i < tareas.length; i++) {
                if (tareas[i].detalle === null) {
                    return self.cerrar = false
                }
            }
            return self.cerrar
        }
    }
};