<template>
  <v-container fluid class="white">
    <v-layout row wrap >
      <v-flex xs12 class="text-xs-center" mt-5>
        <h1>Cargar Informe</h1>
      </v-flex>
      <v-flex xs12 sm8 offset-sm2 mt-2>
        <!-- Si hay un error -->
        <v-alert v-model="fallo" dismissible color="error" icon="warning" if='error' outline>
          Error al cargar un informe
        </v-alert>

        <!-- FORMULARIO INICIO -->
        <v-form ref="form" v-model="valid" lazy-validation>

          <!-- Titulo -->
          <v-text-field v-model="titulo" :rules="tituloRules" :counter="60" label="Título" required></v-text-field>

          <!-- Fecha -->
          <v-menu :close-on-content-click="true" v-model="fechalimite" :nudge-right="40" lazy transition="scale-transition"
            offset-y full-width min-width="290px">
            <v-text-field clearable :rules="dateRules" slot="activator" label="Seleccione la fecha límite" prepend-icon="event" readonly :value="formato"></v-text-field>
            <v-date-picker v-model="date" @input="fechalimite = false" locale="es-mx" color="cyan lighten-3" :min='moment(new Date()).format("YYYY-MM-DD")'></v-date-picker>
          </v-menu>
          <br>
          <!-- Descripción -->

          <label>Descripción</label>
          <v-container>
            <vue-editor v-model="descripcion" :editorToolbar='customToolbar' :rules='textareaRules'></vue-editor>
          </v-container>
          <br>
          <!-- Imagenes-->
          <label>Agregar imágenes</label>
          <v-container>
            <vue-dropzone ref="dropzone" id="dropzone" :options="dropzoneOptions" v-on:vdropzone-sending="sendingEvent">
            </vue-dropzone>
          </v-container>
          <!-- Select Zonas -->
          <v-select :items="zonas" v-model="idzona" label="Zona" :rules="[v => !!v || 'Se requiere ingresar la zona']"
            item-text="nombre" item-value="idzona"></v-select>

          <!-- Select Prioridad -->
          <v-select v-model="idprioridad" :items="prioridad" :rules="[v => !!v || 'Se requiere ingresar la prioridad']"
            label="Prioridad" item-text="nombre" item-value="idprioridad" required></v-select>
          <br>
          <!-- Tareas -->
          <label>Tareas</label>
          <tarea ref="tarea"></tarea>

          <!-- Botones -->
          <v-btn :disabled="!valid" @click="submit" outline color="cyan darken-1">
            Enviar
          </v-btn>
          <v-btn @click="clear" outline>Limpiar Formulario</v-btn>
        </v-form>
        <!-- FORMULARIO FIN -->

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

<script src="./JS_cargarInforme.js"></script>
