<template>
    <v-container>
        <v-layout v-if="this.$store.state.loading">
            <loading></loading>
        </v-layout>

        <v-card v-if="solicitudes">
            <v-card-title>
                Solicitudes para nuevo usuario
                <v-spacer></v-spacer>
                <v-text-field v-model="search" append-icon="search" label="Buscar" single-line hide-details></v-text-field>
            </v-card-title>
            <v-data-table :headers="headers" :items="solicitudes" :search="search" class="elevation-1" rows-per-page-text='' :rows-per-page-items="rowsperpageitems">
                <template slot="no-data">
                    <v-alert :value="true" color="error" icon="warning">
                        No hay solicitudes nuevas :(
                    </v-alert>
                </template>
                <template slot="items" slot-scope="props">
                    <td>{{ props.item.nombre }}</td>
                    <td>{{ props.item.apellido }}</td>
                    <td class="text-xs-left">{{ props.item.legajo }}</td>
                    <td class="text-xs-left">{{ props.item.email }}</td>
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
            
            solicitudes: null,
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
            { text: 'Legajo', value: 'legajo' },
            { text: 'Email', value: 'email' }
            ],
            rowsperpageitems:[ 5, 10, 25, { "text": "Todos", "value": -1 } ],
            search: '',
        }
    },
    components: {
        loading
    },
    created(){
        this.getSolicitudes()
    },
    methods:{
        getSolicitudes() {
        this.$store.commit('setLoading', true)
        var self = this;
        axios.get("/solicitudes/ver", {
            headers: {
                Authorization: "Bearer " + localStorage.getItem("token")
            }
            })
            .then(function (response) {
                setTimeout(function(){ 
                self.solicitudes = response.data
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
