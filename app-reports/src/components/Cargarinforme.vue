<template>
  <v-container fluid>
    <v-layout row wrap>
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
          <v-dialog ref="dialog" v-model="fechalimite" :return-value.sync="date" persistent lazy full-width width="290px">
            <v-text-field slot="activator" v-model="date" label="Seleccione Fecha Limite" :rules="dateRules" required></v-text-field>
            <v-date-picker v-model="date" scrollable>
              <v-spacer></v-spacer>
              <v-btn flat color="primary" @click="fechalimite = false">Cancelar</v-btn>
              <v-btn flat color="primary" @click="$refs.dialog.save(date)">Ingresar</v-btn>
            </v-date-picker>
          </v-dialog>

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

  </v-container>
</template>

<script src="./Informe/JS_informe.js"></script>
