<template>
    <v-container>
        <v-layout v-if="this.$store.state.loading">
            <loading></loading>
        </v-layout>

        <v-card v-if="usuarios">
            <v-card-title>
                Usuarios registrados
                <v-spacer></v-spacer>
                <v-text-field v-model="search" append-icon="search" label="Buscar" single-line hide-details></v-text-field>
            </v-card-title>
            <v-data-table :headers="headers" :items="usuarios" :search="search" class="elevation-1" rows-per-page-text='' :rows-per-page-items="rowsperpageitems">
                <template slot="no-data">
                    <v-alert :value="true" color="error" icon="warning">
                        No hay usuarios registrados :(
                    </v-alert>
                </template>
                <template slot="items" slot-scope="props">
                    <td>{{ props.item.nombre }}</td>
                    <td>{{ props.item.apellido }}</td>
                    <td class="text-xs-left">{{ props.item.usuario }}</td>
                    <td class="text-xs-left">{{ props.item.dni }}</td>
                    <td class="text-xs-left">{{ props.item.legajo }}</td>
                    <td class="text-xs-left">{{ props.item.email }}</td>
                    <td class="text-xs-left">{{ props.item.telefono }}</td>
                    <td class="text-xs-center">{{ props.item.rol }}</td>
                </template>
            <v-alert slot="no-results" :value="true" color="error" icon="warning">
                No hay resultados para "{{ search }}".
            </v-alert>
            </v-data-table>
        </v-card>
    </v-container>
</template>
<script>
import axios from "axios";
import loading from "@/components/common/loading.vue";
export default {
    data(){
        return{
            
            usuarios: null,
            headers: [
            {
                text: 'Nombre',
                align: 'left',
                value: 'nombre'
            },
            {
                text: 'Apellido',
                align: 'left',
                value: 'apellido'
            },
            { text: 'Usuario', value: 'usuario' },
            { text: 'DNI', value: 'dni' },
            { text: 'Legajo', value: 'legajo' },
            { text: 'Email', value: 'email' },
            { text: 'Telefono', value: 'tel' },
            { text: 'Rol', value: 'rol' }
            ],
            rowsperpageitems:[ 5, 10, 25, { "text": "Todos", "value": -1 } ],
            search: '',
        }
    },
    components: {
        loading
    },
    created(){
        this.getUsuarios()
    },
    methods:{
        getUsuarios() {
        this.$store.commit('setLoading', true)
        var self = this;
        axios.get("/user/ver", {
            headers: {
                Authorization: "Bearer " + localStorage.getItem("token")
            }
            })
            .then(function (response) {
                setTimeout(function(){ 
                self.usuarios = response.data
                self.$store.commit('setLoading', false)
                }, 200);
            })
            .catch(error => {
            if (error.response.status === 401){
                self.$store.commit('setExpired', true)
                self.$router.push('/logout')
            }
            });
        }
    }
}

</script>
