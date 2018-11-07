import vue2Dropzone from "vue2-dropzone";
import "vue2-dropzone/dist/vue2Dropzone.min.css";
import moment from 'moment';
import {
  VueEditor
} from "vue2-editor";
import axios from "axios";
import tarea from "./Tarea.vue";
export default {
  data: () => ({
    moment: moment,
    date: new Date().toISOString().substr(0, 10),
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
    prioridad: [],
    idprioridad: null,
    zonas: [],
    idzona: null,
    fallo: false,
    valid: true,
    idinforme: "",
    titulo: "",
    tituloRules: [
      v => !!v || "Se requiere un título",
      v =>
      (v && v.length <= 60) || "El título debe tener menos de 60 caracteres"
    ],

    fechalimite: false,
    dateRules: [v => !!v || "Se requiere una fecha limite"],

    textarea: "",
    descripcion: null,
    textareaRules: [v => !!v || "Es requerido que redacte un informe"],

    dropzoneOptions: {
      url: "http://localhost:8000/informe/img/new",
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

  }),
  components: {
    tarea,
    VueEditor,
    vueDropzone: vue2Dropzone
  },
  beforeCreate() {
    moment.locale('es')
    if (!this.$store.state.isLogged) {
      this.$router.push("/signin");
    }
  },
  mounted() {
    this.getZonas();
    this.getPrioridad();
  },
  methods: {
    submit() {
      var self = this;
      if (this.$refs.form.validate()) {
        axios.post(
            "/informe/new", {
              idhseq: this.$store.state.h,
              titulo: this.titulo,
              fechalimite: this.date,
              descripcion: this.descripcion,
              idzona: parseInt(this.idzona),
              idprioridad: parseInt(this.idprioridad),
              idestado: 1
            }, {
              headers: {
                Authorization: "Bearer " + localStorage.getItem("token")
              }
            }
          )
          .then(function (response) {
            //redirigir al informe creado
            self.idinforme = response.data.idinforme
            self.$refs.tarea.submit(self.idinforme)
            self.$refs.dropzone.processQueue()
          })
          .catch(error => {
            if (error.response.status === 401){
              self.$store.commit('setExpired', true)
              self.$router.push('/logout')
            }
            this.fallo = true;
          });
      }
    },
    sendingEvent(file, xhr, formData) {
      var self = this
      formData.append('idinforme', self.idinforme)
    },
    clear() {
      this.$refs.form.reset();
      this.imageUrl = "";
      this.$refs.dropzone.removeAllFiles();
    },
    getZonas() {
      var self = this;
      axios
        .get("http://localhost:8000/zona/ver", {
          headers: {
            Authorization: "Bearer " + localStorage.getItem("token")
          }
        })
        .then(function (response) {
          self.zonas = response.data;
          //console.log(self.zonas)
        })
        .catch(error => {
          if (error.response.status === 401){
            self.$store.commit('setExpired', true)
            self.$router.push('/logout')
          }
        });
    },
    getPrioridad() {
      var self = this;
      axios
        .get("http://localhost:8000/prioridad/ver", {
          headers: {
            Authorization: "Bearer " + localStorage.getItem("token")
          }
        })
        .then(function (response) {
          self.prioridad = response.data;
          //console.log(self.prioridad)
        })
        .catch(error => {
          if (error.response.status === 401){
            self.$store.commit('setExpired', true)
            self.$router.push('/logout')
          }
        });
    }
  },
  computed: {
    formato () {
      return this.date ? moment(this.date).format('ddd D MMM YYYY') : ''
    }
  }

};