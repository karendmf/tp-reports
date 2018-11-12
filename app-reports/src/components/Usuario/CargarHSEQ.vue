<template>
    <v-container fluid>
        <v-layout row wrap>
            <v-flex xs12 class="text-xs-center" mt-5>
                <h1>Registrar HSEQ</h1>
            </v-flex>
            <v-flex xs12 sm6 offset-sm3 mt-3>
                <v-alert v-model="fallo" dismissible color="error" icon="warning" if='error' outline>
                    Error
                </v-alert>

                <v-form ref="form" v-model="valid" lazy-validation>

                    <v-select
                    v-model="idcargo"
                    label="Cargo"
                    :items="cargos" 
                    item-text="nombre" 
                    item-value="idcargo"
                    :rules="[v => !!v || 'Se requiere ingresar un cargo']"></v-select>
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
import router from '@/router'
export default {
    data() {
        return {
            cargos: [],
            idcargo: null,
            fallo: null,
            valid: true,
        }
    },
    mounted() {
        this.getCargos()
    },
    beforeCreate() {
        if (!this.$store.state.isLogged) {
            router.push('/signin')
        }
    },
    methods: {
        getCargos() {
            var self = this
            axios.get('/cargo/ver', {
                    headers: {
                        Authorization: 'Bearer ' + localStorage.getItem('token')
                    }
                }).then(function (response) {
                    self.cargos = response.data
                    console.log(self.cargos)
                })
                .catch(error => {
                    console.log(error.response)
                });
        },
        submit() {
            var self = this
            if (this.$refs.form.validate()) {
                // Native form submission is not yet supported
                axios.post('/hseq/new', {

                        idpersona: this.$store.state.r,
                        idcargo: this.idcargo

                    }, {
                        headers: {
                            Authorization: 'Bearer ' + localStorage.getItem('token')
                        }
                    }).then(function (response) {
                        console.log(response)
                        self.$store.commit('registrarUsuario', null)
                        //redirigir a la info del usuario cargado
                        router.push('/')
                        self.$refs.form.reset()
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

}
</script>
