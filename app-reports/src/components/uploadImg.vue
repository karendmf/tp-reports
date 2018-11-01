<template>
<v-container>
    <vue-dropzone ref="dropzone" id="dropzone" :options="dropzoneOptions" v-on:vdropzone-sending="sendingEvent">
    </vue-dropzone>
    <v-btn @click="submit" outline color="cyan darken-1">
            Enviar
    </v-btn>
</v-container>
</template>
<script>
import vue2Dropzone from "vue2-dropzone";
import "vue2-dropzone/dist/vue2Dropzone.min.css";
import axios from "axios";
export default {
    data(){
        return {
            idinforme: 14,
            dropzoneOptions: {
                url: "http://localhost:8000/informe/img/new",
                maxFiles: 4,
                addRemoveLinks: true,
                thumbnailWidth: 150,
                maxFilesize: 0.5,
                autoProcessQueue: false,
                acceptedFileTypes: '.jpeg, .png',
                dictDefaultMessage: '<h1>Arrastre las imágenes aquí</h1><h3>O haga click para seleccionar desde una carpeta</h3>',
                headers: { 
                    Authorization: "Bearer " + localStorage.getItem("token"),
                    "Cache-Control": "",
                    "X-Requested-With": "" }
            }
        }
    },
    components: {
        vueDropzone: vue2Dropzone
    },
    methods:{
        submit(){
            
            this.$refs.dropzone.processQueue()
        },
        sendingEvent (file, xhr, formData) {
            var self = this
            formData.append('idinforme', self.idinforme)
        }
    }
    
}
</script>

<style>
.dropzone-custom-content {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  text-align: center;
}

.dropzone-custom-title {
  margin-top: 0;
  color: #00b782;
}

.subtitle {
  color: #314b5f;
}
</style>
