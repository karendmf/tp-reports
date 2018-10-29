import {
    VueEditor
  } from "vue2-editor";
  import axios from 'axios'
  import tarea from '../Tarea.vue'
  export default {
    data: () => ({
      customToolbar: [
        ["bold", "italic", "underline", "strike"],
        [{
          list: "ordered"
        }, {
          list: "bullet"
        }],
        [{
          'header': [1, 2, 3, 4, 5, 6, false]
        }],
        [{
          'align': []
        }],
        [{
          'indent': '-1'
        }, {
          'indent': '+1'
        }], // outdent/indent
        [{
          'size': ['small', false, 'large', 'huge']
        }], // custom dropdown
        [{
          'color': []
        }, {
          'background': []
        }], // dropdown with defaults from theme
        [{
          'font': []
        }],
      ],
      imageName: '',
      imageUrl: '',
      imageFile: '',
      prioridad: [],
      idprioridad: null,
      zonas: [],
      idzona: null,
      fallo: false,
      valid: true,

      titulo: '',
      tituloRules: [
        v => !!v || 'Se requiere un título',
        v => (v && v.length <= 60) || 'El título debe tener menos de 60 caracteres'
      ],

      date: null,
      fechalimite: false,
      dateRules: [
        v => !!v || 'Se requiere una fecha limite'
      ],

      textarea: '',
      descripcion: null,
      textareaRules: [
        v => !!v || 'Es requerido que redacte un informe',
      ],

    }),
    components: {
      tarea,
      VueEditor
    },
    beforeCreate() {
      if (!this.$store.state.isLogged) {
        this.$router.push('/signin')
      }
    },
    mounted() {
      this.getZonas()
      this.getPrioridad()
    },
    methods: {
      pickFile() {
        this.$refs.image.click()
      },
      onFilePicked(e) {
        const files = e.target.files
        if (files[0] !== undefined) {
          this.imageName = files[0].name
          if (this.imageName.lastIndexOf('.') <= 0) {
            return
          }
          const fr = new FileReader()
          fr.readAsDataURL(files[0])
          fr.addEventListener('load', () => {
            this.imageUrl = fr.result
            this.imageFile = files[0] // this is an image file that can be sent to server...
          })
        } else {
          this.imageName = ''
          this.imageFile = ''
          this.imageUrl = ''
        }
      },
      submit() {
        var self = this
        if (this.$refs.form.validate()) {
          // Native form submission is not yet supported
          axios.post('/informe/new', {

              idhseq: this.$store.state.h,
              titulo: this.titulo,
              fechalimite: this.date,
              descripcion: this.descripcion,
              idzona: parseInt(this.idzona),
              idprioridad: parseInt(this.idprioridad),
              idestado: 1,

            }, {
              headers: {
                Authorization: 'Bearer ' + localStorage.getItem('token')
              },
            }).then(function (response) {
              //console.log(response)
              //redirigir al informe creado
              self.$refs.form.reset()
            })
            .catch(error => {
              this.fallo = true
              console.log(error.response)
            });
        }
      },
      clear() {
        this.$refs.form.reset()
        this.imageUrl = ''
      },
      getZonas() {
        var self = this
        axios.get('http://localhost:8000/zona/ver', {
            headers: {
              Authorization: 'Bearer ' + localStorage.getItem('token')
            }
          }).then(function (response) {
            self.zonas = response.data
            //console.log(self.zonas)
          })
          .catch(error => {
            //console.log(error.response)
          });
      },
      getPrioridad() {
        var self = this
        axios.get('http://localhost:8000/prioridad/ver', {
            headers: {
              Authorization: 'Bearer ' + localStorage.getItem('token')
            }
          }).then(function (response) {
            self.prioridad = response.data
            //console.log(self.prioridad)
          })
          .catch(error => {
            //console.log(error.response)
          });
      }

    }
  }