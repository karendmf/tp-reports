<template>
<v-container>
        <v-flex class="text-xs-center" mt-3>
            <h1>Respuesta</h1>
            <div class="text-xs-left font-weight-light">Luego de enviar la respuesta, la tarea quedará como cerrada y no podrá ser modificada.</div>
        </v-flex>
        <v-flex mt-2>
            <v-form ref="form" v-model="valid" lazy-validation>
               <v-textarea v-model="descripcion" label="Detalle su respuesta" :rules="textareaRules" required rows='1' auto-grow></v-textarea>
                <br>
                <v-btn :disabled="!valid" @click="submit" outline color="cyan darken-1">
                    Enviar
                </v-btn>
                <v-btn @click="clear" outline>
                    Limpiar Formulario
                </v-btn>
            </v-form>
        </v-flex>
</v-container>
</template>

<script>
import moment from 'moment';
import axios from "axios";
export default {
    props: ['idTarea'],
    data: () => ({
        moment: moment,
        valid: true,
        textarea: "",
        descripcion: null,
        textareaRules: [v => !!v || "Es requerido que redacte una respuesta"],
    }),

    methods: {
        submit() {
            var self = this;
            if (this.$refs.form.validate()) {
                axios.post(
                        "/tareadetalle/new", {
                            descripcion: this.descripcion,
                            idtarea: this.idTarea,
                        }, {
                            headers: {
                                Authorization: "Bearer " + localStorage.getItem("token")
                            }
                        }
                    )
                    .then(function (response) {
                        console.log('Se creo la respuesta',response.data) // eslint-disable-line no-console
                        self.$parent.fetchTarea()
                    })
                    .catch(error => {
                        console.log(error.response) // eslint-disable-line no-console
                    });
            }
        },
        clear() {
            this.$refs.form.reset();
        },
    }
}    
</script>
