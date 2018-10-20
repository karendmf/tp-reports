<template>
<v-container fluid>
  <v-layout row wrap>
    <v-flex xs12 class="text-xs-center" mt-5>
      <h1>Cargar Informe</h1>
    </v-flex>
    <v-flex xs12 sm6 offset-sm3 mt-3>
      <v-alert v-model="fallo" dismissible color="error" icon="warning" if='error' outline>
        Error al cargar un informe
      </v-alert>
      <v-form ref="form" v-model="valid" lazy-validation>
        <v-text-field v-model="titulo" :rules="tituloRules" :counter="60" label="Título" required></v-text-field>

        <v-dialog ref="dialog" v-model="fechalimite" :return-value.sync="date" persistent lazy full-width width="290px">
          <v-text-field slot="activator" v-model="date" label="Seleccione Fecha Limite" :rules="dateRules" required></v-text-field>
          <v-date-picker v-model="date" scrollable>
            <v-spacer></v-spacer>
            <v-btn flat color="primary" @click="fechalimite = false">Cancelar</v-btn>
            <v-btn flat color="primary" @click="$refs.dialog.save(date)">Ingresar</v-btn>
          </v-date-picker>
        </v-dialog>

        <v-textarea v-model="descripcion" label="Informe" hint="Redacte su informe" :rules="textareaRules" required></v-textarea>


        <v-select :items="zonas" v-model="idzona" label="Zona" :rules="[v => !!v || 'Se requiere ingresar la zona']"
          item-text="nombre" item-value="idzona"></v-select>

        <v-select v-model="idprioridad" :items="prioridad" :rules="[v => !!v || 'Se requiere ingresar la prioridad']"
          label="Prioridad" item-text="nombre" item-value="idprioridad" required></v-select>
        <img :src="imageUrl" height="150" v-if="imageUrl"/>
					<v-text-field label="Select Image" @click='pickFile' v-model='imageName' prepend-icon='attach_file'></v-text-field>
					<input
						type="file"
						style="display: none"
						ref="image"
						accept="image/*"
						@change="onFilePicked"
					>
        

        <v-btn :disabled="!valid" @click="submit">
          Enviar
        </v-btn>
        <v-btn @click="clear">Limpiar Formulario</v-btn>
      </v-form>
    </v-flex>
  </v-layout>
</v-container>
</template>

<script>
  import axios from 'axios'
  export default {
    data: () => ({
      imageName: '',
      imageUrl: '',
      imageFile: '',
      prioridad: [],
      idprioridad: null,
      zonas: [],
      idzona: null,
      fallo: false ,
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
    mounted(){
      this.getZonas()
      this.getPrioridad()
    },
    methods: {
      pickFile () {
            this.$refs.image.click ()
        },
      onFilePicked (e) {
			const files = e.target.files
			if(files[0] !== undefined) {
				this.imageName = files[0].name
				if(this.imageName.lastIndexOf('.') <= 0) {
					return
				}
				const fr = new FileReader ()
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
      submit () {
        var self = this
        if (this.$refs.form.validate()) {
          // Native form submission is not yet supported
          axios.post('http://localhost:8000/informe/new', {

            idhseq: this.$store.state.h,
            titulo: this.titulo,
            fechalimite: this.date,
            descripcion: this.descripcion,
            idzona: parseInt(this.idzona),
            idprioridad: parseInt(this.idprioridad),
            idestado: 1,
            
          },
          {
            headers: { 
              Authorization: 'Bearer ' + localStorage.getItem('token') 
            },
          }).then(function (response) { 
            console.log(response)
            //redirigir al informe creado
            self.$refs.form.reset()
          })
          .catch(error => {
              this.fallo = true
              console.log(error.response)
          });
        }
      },
      clear () {
        this.$refs.form.reset()
        this.imageUrl= ''
      },
      getZonas(){
        var self = this
        axios.get('http://localhost:8000/zona/ver',{
          headers: { 
            Authorization: 'Bearer ' + localStorage.getItem('token') 
          }
        }).then(function (response) {
          self.zonas = response.data
            console.log(self.zonas)
          })
          .catch(error => {
              console.log(error.response)
          });
      },
      getPrioridad(){
        var self = this
        axios.get('http://localhost:8000/prioridad/ver',{
          headers: { 
            Authorization: 'Bearer ' + localStorage.getItem('token') 
          }
        }).then(function (response) {
          self.prioridad = response.data
            console.log(self.prioridad)
          })
          .catch(error => {
              console.log(error.response)
          });
      }

    }
  }
</script>