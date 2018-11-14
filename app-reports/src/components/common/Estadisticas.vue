<template>
    <div id="estadisticas">
        <!-- Al usar un componente de VueJS preestablecido, solo nos encargamos de llamarlo e indicar que tipo de grafico usaresmo. Para ver el uso "normal", dirigirse a la documentación -->
        <GChart          
            type="PieChart"                       
            @ready="onChartReady"
            />
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
                    var data = new google.visualization.DataTable();

                    // Agregamos columnas
                    data.addColumn('string', 'mes');
                    data.addColumn('number', 'cantidad');

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
                    //console.log('Peticion: ', compressed);

                    // Una vez creado el array con los datos deseados, lo recorremos y agregamos las filas
                    var valor;
                    for (valor of compressed) {
                        // Las filas deben tener la misma cantidad de datos que de columnas agregadas. Si son 3 columnas, la fila debe tener 3 valores.
                        data.addRow([valor.value, valor.count])
                    }

                    // Podemos elegir opciones, como titulo, subtitilo, ect. Es opcional
                    const options = {
                        title: "Informes a cerrar por mes"
                    }
                    //console.log('Respuesta: ', data);
                    // Enviamos la tabla creada llamada "data", y las opciones. Como respuesta obtenemos un JSON, el cual la API de Charts se encarga de "desarmarlo" y dibujar el grafico.
                    chart.draw(data, options);
                });
        }
    }
}
</script>
