<template>
  <div>
    <v-navigation-drawer v-model="drawer" clipped fixed app>
      <!-- AVATAR -->
      <avatar></avatar>
      <!-- FIN AVATAR -->
      <v-divider></v-divider>
      <!-- LISTA DE OPCIONES -->

      <v-list>
        <v-list-tile to="/">
          <v-list-tile-action>
            <v-icon>home</v-icon>
          </v-list-tile-action>
          <v-list-tile-title>Home</v-list-tile-title>
        </v-list-tile>
        <v-list-group v-for="item in items" v-model="item.active" :key="item.title" :prepend-icon="item.icon" no-action>

          <v-list-tile slot="activator">

            <v-list-tile-content>
              <v-list-tile-title>{{ item.title }}</v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>


          <v-list-tile v-for="subItem in item.menu" :key="subItem.title" @click="" :to="subItem.route">
            <v-list-tile-content>
              <v-list-tile-title>{{ subItem.title }}</v-list-tile-title>
            </v-list-tile-content>

            <v-list-tile-action>
              <v-icon>{{ subItem.icon }}</v-icon>
            </v-list-tile-action>
          </v-list-tile>
        </v-list-group>
      </v-list>

      <v-divider></v-divider>
      <v-list dense>
        <v-list-tile to="/logout">
          <v-list-tile-action>
            <v-icon>cancel</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>Cerrar sesión</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
      </v-list>
      <!-- FIN LISTA DE OPCIONES -->
    </v-navigation-drawer>
    <!-- FIN BARRA LATERAL DE NAVEGACIÓN -->

    <!-- NAVBAR TOP -->
    <v-toolbar app fixed clipped-left color='cyan darken-1' dark>
      <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
      <v-toolbar-title>
        <v-btn icon large>
          <v-avatar size="40px" tile>
            <img src="@/assets/logo_blanco.svg" alt="reportS">
          </v-avatar>
        </v-btn>
        reportS
      </v-toolbar-title>
      <v-spacer></v-spacer>
      <v-btn icon>
        <v-icon>notifications</v-icon>
      </v-btn>
      <v-btn icon>
        <v-icon>settings</v-icon>
      </v-btn>
    </v-toolbar>
    <!-- FIN NAVBAR TOP -->

    <!-- CONTENIDO -->
    <v-content>
      <v-container fluid>
        <v-fade-transition mode="out-in">
          <router-view></router-view>
        </v-fade-transition>
      </v-container>
    </v-content>
    <!-- FIN CONTENIDO -->

    <!-- FOOTER -->
    <v-footer fixed height="auto">
      <v-card class="flex" flat tile>
        <v-card-actions class="justify-center">
          <strong>TP FINAL</strong> — &copy;2018
        </v-card-actions>
      </v-card>
    </v-footer>
  </div>
</template>


<script>
import avatar from "@/components/common/Avatar.vue";
export default {
  name: "panel",
  components: {
    avatar
  },
  data() {
    return {
      drawer: true,
      items: [{
          title: "Usuarios",
          icon: "account_box",
          menu: [
            {
              title: "Ver Usuarios",
              icon: "visibility",
              route: "/usuario"
            },
            {
              title: "Registrar Usuario",
              icon: "add",
              route: "/usuario/nuevo"
            },
            
          ]
        },
        {
          title: "Informes",
          icon: "list_alt",
          menu: [
            {
              title: "Ver Informes",
              icon: "visibility",
              route: "/informes"
            },
            {
              title: "Crear Informe",
              icon: "add",
              route: "/informes/cargar"
            },
            
          ]
        },
        {
          title: "Tareas",
          icon: "list_alt",
          menu: [
            {
              title: "Ver Tareas",
              icon: "visibility",
              route: "/tareas"
            },
            
            
          ]
        }
      ]
    };
  },
  props: {
    source: String
  }
};
</script>