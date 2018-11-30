<template>
    <div id="clima">
        <v-card color="cyan darken-1" class="white--text" v-if="clima.channel">
            <v-card-title primary-title>
                <div class="headline">Clima en {{ clima.channel.location.city}}</div>
            </v-card-title>
            <v-list two-line v-if="clima.channel">
                <v-list-tile ripple>
                    <v-list-tile-content>
                        <v-list-tile-sub-title>Ultima actualización:</v-list-tile-sub-title>
                        <!-- Uso de datos de la respuesta en formato JSON -->
                        <v-list-tile-sub-title>{{clima.channel.item.condition.date.substring(5, 25)}}</v-list-tile-sub-title>
                    </v-list-tile-content>
                </v-list-tile>
                <v-divider></v-divider>
                <v-list-tile ripple>
                    <v-list-tile-content>
                        <v-list-tile-title>Viento</v-list-tile-title>
                        <v-list-tile-sub-title class="text--primary">Velocidad {{ clima.channel.wind.speed}} km/h</v-list-tile-sub-title>
                    </v-list-tile-content>
                </v-list-tile>
                <v-divider></v-divider>
                <v-list-tile ripple>
                    <v-list-tile-content>
                        <v-list-tile-title>Temperatura</v-list-tile-title>
                        <v-list-tile-sub-title class="text--primary">{{ clima.channel.item.condition.temp}} °c</v-list-tile-sub-title>
                        <!-- <v-list-tile-sub-title class="text-primary">Cielo {{ clima.channel.item.condition.text }}</v-list-tile-sub-title> -->
                    </v-list-tile-content>
                </v-list-tile>
                <v-divider></v-divider>
                <v-list-tile ripple>
                    <v-list-tile-content>
                        <v-list-tile-title>Nivel de confort</v-list-tile-title>
                        <v-list-tile-sub-title class="text--primary">Humedad: {{ clima.channel.atmosphere.humidity}}%</v-list-tile-sub-title>
                    </v-list-tile-content>
                </v-list-tile>
            </v-list>
        </v-card>
    </div>
</template>
<script>
    import axios from 'axios'
    import moment from 'moment'
    export default {
        data() {
            return {
                clima: {},
                moment: moment,
            }
        },
        mounted() {
            moment.locale('es')

            // Id de la ciudad, sacado del sitio de Yahoo clima
            const woeid = '466868';
            //const woeid = '332478';

            // Seleccionamos que datos queremos pedir a la API
            const select = 'location,wind,atmosphere,item.condition';

            // Se arma la url
            let url =
                `https://query.yahooapis.com/v1/public/yql?q=select ${select} from weather.forecast where woeid=${woeid} and u='c' &format=json`
            var self = this;

            // Se hace la petición
            axios.get(url)
                .then(function (response) {

                    // Guardamos la respuesta en una variable, para despues recorrerla en el HTML
                    self.clima = response.data.query.results;
                    //console.log('clima: ', response.data.query.results);
                })
        }
    }
</script>