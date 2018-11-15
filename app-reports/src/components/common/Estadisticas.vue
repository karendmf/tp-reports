<template>
    <div id="estadisticas">
        <!-- Al usar un componente de VueJS preestablecido, solo nos encargamos de llamarlo e indicar que tipo de grafico usaremos. Para ver el uso "normal", dirigirse a la documentación -->
        <v-card>
            <v-card-title primary-title color='cyan'>
                <div class="headline">Vencimiento de informes</div>
            </v-card-title>
            <v-flex fluid color='white'>
                <GChart 
                type="PieChart"                       
                @ready="onChartReady"
                />
            </v-flex>
        </v-card>
    </div>
</template>
<script>
import axios from 'axios'
import moment from 'moment'
import {
    GChart
} from 'vue-google-charts'
export default {
    name: 'estadisticas',
    components: {
        GChart
    },
    data() {
        return {
            moment: moment
        };
    },
    mounted() {
        moment.locale('es')
    },
    methods: {
        
        //Iniciamos la funcion
        onChartReady(chart, google) {
            self.id = this.$store.state.h;
            // Obtenemos los datos de nuestra API de informes con formato JSON
            axios.get('/informe/me/' + self.id, {
                    headers: {
                        Authorization: "Bearer " + localStorage.getItem("token")
                    }
                })
                .then(function (response) {

                    // Luego de obtener la respuesta por parte del servidor, inicializamos una variable que guarde esos datos.
                    const jsonData = response.data;

                    // google.visualization.DataTable() representa una tabla mutable o array multidimencional, al que le podremos agregar todas las columnas deseadas.
                    
                    // Agregamos columnas
                    var data = new google.visualization.DataTable()
                    data.addColumn('string', 'Mes');
                    data.addColumn('number', 'Informes');

                    // Se arma un arreglo nuevo, usando los datos qe guardamos de la respuesta de nuestra API de informes.
                    // * Array de meses * //
                    var meses = [];
                    var val;
                    for (val of jsonData) {
                        meses.push(moment(val.fechalimite).format('MMMM'))
                    }

                    var compressed = [];
                    var copy = meses.slice(0);

                    // * Array que contiene cuantas veces se repiten los meses en el arreglo anterior * //
                    for (var i = 0; i < meses.length; i++) {
                        var myCount = 0;
                        for (var w = 0; w < copy.length; w++) {
                            if (meses[i] == copy[w]) {
                                myCount++;
                                delete copy[w];
                            }
                        }

                        if (myCount > 0) {
                            var a = new Object();
                            a.value = meses[i];
                            a.count = myCount;
                            compressed.push(a);
                        }
                    }

                    // Una vez creado el array con los datos deseados, lo recorremos y agregamos las filas
                    // Las filas deben tener la misma cantidad de datos que de columnas agregadas. Si son 3 columnas, la fila debe tener 3 valores.
                    //data.addRow([valor.value, valor.count])

                    compressed.forEach(element => {
                        data.addRow([element.value, element.count])
                    });
                    // Podemos elegir opciones, como titulo, subtitilo, ect. Es opcional
                    const options = {
                        pieHole: 0.2,
                        backgroundColor: 'none',
                        chartArea:{width:'80%',height:'105%'}
                    }
                    //console.log('Respuesta: ', data);
                    // Enviamos la tabla creada llamada "data", y las opciones. Como respuesta obtenemos un JSON, el cual la API de Charts se encarga de "desarmarlo" y dibujar el grafico.

                    chart.draw(data, options);

                });
        }
    }
}
</script>
