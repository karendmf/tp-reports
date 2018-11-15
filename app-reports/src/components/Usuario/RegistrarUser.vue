<template>
    <v-container fluid class="white" text-xs-center>
        <v-layout row wrap>
            <v-flex xs12 class="text-xs-center" mt-5>
                <h1>Registrar Usuario</h1>
            </v-flex>
            <v-flex xs12 sm8 offset-sm2 mt-3 >
                <v-alert type="error" dismissible v-model="alert" outline> 
                    {{ error }} 
                </v-alert>

                <v-form ref="form" v-model="valid" lazy-validation>

                    <v-text-field v-model="nombre" :rules="namesurname" :counter="50" label="Nombre" required></v-text-field>

                    <v-text-field v-model="apellido" :rules="namesurname" :counter="50" label="Apellido" required></v-text-field>

                    <v-text-field v-model="dni" :rules="dniRules" label="DNI" type="number" :counter="8" required></v-text-field>

                    <v-text-field v-model="email" :rules="emailRules" label="E-mail" required></v-text-field>

                    <v-text-field v-model="telefono" :rules="telefonoRules" label="Telefono" type="number" :counter="11"
                        required></v-text-field>

                    <v-text-field v-model="legajo" :rules="legajoRules" :counter="12" label="Legajo" required></v-text-field>

                    <v-text-field v-model="usuario" :rules="username" :counter="70" label="Usuario" type="usuario"
                        required></v-text-field>

                    <v-text-field v-model="password" :rules="mypassword" :counter="90" label="Contraseña" type="password"
                        required></v-text-field>
                    <v-select
                    v-model="rol"
                    label="Rol"
                    :items="roles" 
                    item-text="nombre" 
                    item-value="value"
                    :rules="[v => !!v || 'Se requiere ingresar un rol']"></v-select>
                    <v-btn :disabled="!valid" @click="submit" outline color="cyan darken-1">
                        Enviar
                    </v-btn>
                    <v-btn @click="clear" outline>Limpiar Formulario</v-btn>
                </v-form>
            </v-flex>
        </v-layout>
    </v-container>
</template>
<script>
    import axios from 'axios'
    import router from '@/router'
    export default {
        data: () => ({
            alert: false,
            valid: true,
            rol: null,

            roles: [{
                    nombre: 'HSEQ',
                    value: 'hseq'
                },
                {
                    nombre: 'Area',
                    value: 'area'
                },
            ],
            nombre: '',
            apellido: '',
            namesurname: [
                v => !!v || 'Se requiere nombre y apellido',
                v => (v && v.length <= 50) || 'Debe tener menos de 50 caracteres'
            ],

            dni: '',
            dniRules: [
                v => !!v || 'Se requiere el DNI',
                v => (v && v.length <= 8) || 'Debe tener menos de 8 caracteres'
            ],


            email: '',
            emailRules: [
                v => !!v || 'Se requiere E-Mail',
                v => /.+@.+/.test(v) || 'Debe ser un E-Mail valido'
            ],

            telefono: '',
            telefonoRules: [
                v => !!v || 'Se requiere el telefono',
                v => (v && v.length <= 11) || 'Debe tener menos de 11 caracteres'
            ],

            legajo: '',
            legajoRules: [
                v => !!v || 'Se requiere el legajo',
                v => (v && v.length <= 12) || 'Debe tener menos de 45 caracteres'
            ],

            usuario: '',
            username: [
                v => !!v || 'Se requiere un nombre de usuario',
                v => (v && v.length <= 70) || 'Debe tener menos de 70 caracteres'
            ],

            password: '',
            mypassword: [
                v => !!v || 'Se requiere una contraseña',
                v => (v && v.length <= 90) || 'Debe tener menos de 90 caracteres'
            ],

        }),
        beforeCreate() {
            if (!this.$store.state.isLogged) {
                router.push('/signin')
            }
        },
        methods: {
            submit() {
                var self = this
                if (this.$refs.form.validate()) {
                    // Native form submission is not yet supported
                    axios.post('/user/register', {

                            nombre: this.nombre,
                            apellido: this.apellido,
                            dni: parseInt(this.dni),
                            email: this.email,
                            telefono: parseInt(this.telefono),
                            legajo: this.legajo,
                            usuario: this.usuario,
                            password: this.password,
                            rol: this.rol
                        }, {
                            headers: {
                                Authorization: 'Bearer ' + localStorage.getItem('token')
                            }
                        })
                        .then(function (response) {
                            console.log(response) // eslint-disable-line no-console
                            self.$store.commit('setError', null)
                            if (response.status === 201){
                                self.$store.commit('registrarUsuario', response.data)
                                if (self.rol === 'hseq') {
                                    //console.log('es hseq')
                                    router.push('/usuario/nuevo/hseq')
                                }
                                if (self.rol === 'area') {
                                    //console.log('es area')
                                    router.push('/usuario/nuevo/area')
                                }
                                //redirigir al informe creado
                                self.$refs.form.reset()
                            }
                        })
                        .catch(error => {
                            if (error.response.status === 401){
                                self.$store.commit('setExpired', true)
                                self.$router.push('/logout')
                            }
                            self.$store.commit('setError', error.response.data) 
                            self.$store.commit('setLoading', false)
                            //console.log('catch', error.response.data)
                        });
                }
            },
            clear() {
                this.$refs.form.reset()
                this.imageUrl = ''
            },

        },
        computed: {
            error() {
                return this.$store.state.error
            },
            loading() {
                return this.$store.state.loading
            }
        },
        watch: {
            error(value) {
                if (value) {
                    this.alert = true
                }
            },
            alert(value) {
                if (!value) {
                    this.$store.commit('setError', null)
                }
            }
        }
    }
</script>