<template>
    <v-container fluid>
        
        <v-layout row wrap>
            <v-flex xs12 class="text-xs-center" mt-5>
                <h1>Registrar Área</h1>
            </v-flex>
            <v-flex xs12 sm6 offset-sm3 mt-3>
                <v-alert v-model="fallo" dismissible color="error" icon="warning" if='error' outline>
                    Error
                </v-alert>

                <v-form ref="form" v-model="valid" lazy-validation>

                    <v-text-field
                    v-model="nombre"
                    :rules="nombreRules"
                    :counter="55"
                    label="Nombre del área"
                    required></v-text-field>
                    <v-btn :disabled="!valid" @click="submit">
                        Enviar
                    </v-btn>
                    <v-btn @click="clear">Limpiar Formulario</v-btn>
                </v-form>
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
<script>
import axios from 'axios'
import router from '@/router'
export default {
    data: () => ({
        textSnack: null,
        snackbar: false,
        nombre: null,
        fallo: null,
        valid: true,
        nombreRules: [
            v => !!v || 'Se requiere un nombre de área',
            v => (v && v.length <= 60) || 'El nombre debe tener menos de 55 caracteres'
        ],

    }),
    methods: {
        submit() {
            var self = this
            if (this.$refs.form.validate()) {
                axios.post('/area/new', {
                        idpersona: this.$store.state.r,
                        nombre: this.nombre
                    }, {
                        headers: {
                            Authorization: 'Bearer ' + localStorage.getItem('token')
                        }
                    }).then(function (response) {
                        console.log(response) // eslint-disable-line no-console
                        self.$store.commit('registrarUsuario', null)
                        self.snackbar = true
                        self.textSnack = 'Usuario cargado correctamente.'
                        self.$refs.form.reset()
                        setTimeout(function () {
                            router.push('/')
                        }, 600);
                        
                        
                    })
                    .catch(error => {
                        if (error.response.status === 401){
                            self.$store.commit('setExpired', true)
                            self.$router.push('/logout')
                        }
                        this.fallo = true
                    });
            }
        },
        clear() {
            this.$refs.form.reset()
            this.imageUrl = ''
        },
    },
    beforeCreate() {
        if (!this.$store.state.isLogged) {
            router.push('/signin')
        }
    },
}
</script>
