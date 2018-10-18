<template>
  <v-container fluid>
    <v-layout row wrap v-if="informes">
            <v-flex xs6 sm3 v-for="informe in informes" :key="informe.id">
                <v-card>
                    <v-card-title primary-title>
                        <div>
                            <div class="headline">{{informe.titulo}}</div>
                        </div>
                    </v-card-title>
                    <v-card-actions>
                        <v-btn flat block color="cyan darken-1">Ver</v-btn>
                    </v-card-actions>
                </v-card>
            </v-flex>
        </v-layout>
  </v-container>
</template>

<script>
import router from '@/router'
import {
  store
} from '@/store'
import axios from 'axios'
export default {
  data() {
    return {
      informes: null,
      error: null
    }
  },
  beforeCreate() {
    if (!store.state.isLogged) {
      router.push('/signin')
    }
  },
  mounted() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      var self = this
      self.loading = true
      self.error = null;
      axios.get('http://localhost:8000//informe/ver',{
        headers: {
          Authorization: 'Bearer ' + localStorage.getItem('token')
        }
      })
        .then(function (response) {
          self.loading = false
          self.informes = response.data;
          //console.log('Informes: ', response.data);
        })
        .catch(function (err) {
          self.error = err.toString()
          self.loading = false
        });
    }
  }

}
</script>