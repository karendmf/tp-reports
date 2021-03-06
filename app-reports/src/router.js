import Vue from 'vue'
import Router from 'vue-router'
import {
  store
} from '@/store'

/**Importación de componentes */
//INFORME:
import Informes from '@/components/Informe/Informes'
import CargarInforme from '@/components/Informe/CargarInforme'
import VerInforme from '@/components/Informe/VerInforme'
import InformesAll from '@/components/Informe/InformesAll'

//TAREA:
import Tareas from '@/components/Tarea/Tareas'
import VerTarea from '@/components/Tarea/VerTarea'

//USUARIO:
import RegistrarUser from '@/components/Usuario/RegistrarUser'
import SolicitarUsuario from '@/components/Usuario/SolicitarUsuario'
import CargarArea from '@/components/Usuario/CargarArea'
import CargarHSEQ from '@/components/Usuario/CargarHSEQ'
import Usuarios from '@/components/Usuario/Usuarios'
import VerSolicitudes from '@/components/Usuario/VerSolicitudes'

//SESION
import Login from '@/components/Sesion/Login'
import Logout from '@/components/Sesion/Logout'

//COMMON:
import Landing from '@/components/common/Landing'
import Calendario from '@/components/common/Calendario'

/** Definición de rutas */
const routes = [
  //COMMON:
  {
    path: '/',
    component: Landing,
    meta: {
      requiresAuth: true,
    }
  },
  {
    path: '/calendario',
    component: Calendario,
    meta: {
      requiresAuth: true,
    }
  },
  //SESION:
  {
    path: '/login',
    component: Login,
    meta: {
      requiresAuth: false,
    }
  },
  {
    path: '/logout',
    component: Logout,
    meta: {
      requiresAuth: true
    }
  },
  //INFORME:
  {
    path: '/informe/me',
    component: Informes,
    meta: {
      requiresAuth: true
    },
    beforeEnter: (to, from, next) => {
      if (store.state.rol === 'hseq') {
        next()
      } else if ((store.state.rol === 'admin')) {
        next()
      } else {
        next('/')
      }
    }
  },
  {
    path: '/informe/cargar',
    component: CargarInforme,
    meta: {
      requiresAuth: true
    },
    beforeEnter: (to, from, next) => {
      if (store.state.rol === 'hseq') {
        next()
      } else if ((store.state.rol === 'admin')) {
        next()
      } else {
        next('/')
      }
    }
  },
  {
    path: '/informe/:idInforme/ver',
    component: VerInforme,
    props: true,
    meta: {
      requiresAuth: true,
    },
    beforeEnter: (to, from, next) => {
      if (store.state.rol === 'hseq') {
        next()
      } else if ((store.state.rol === 'admin')) {
        next()
      } else {
        next('/')
      }
    }
  },
  {
    path: '/informe/todos',
    component: InformesAll,
    meta: {
      requiresAuth: true
    },
    beforeEnter: (to, from, next) => {
      if (store.state.rol !== 'admin') {
        next('/')
      } else {
        next()
      }
    }
  },
  //TAREA:
  {
    path: '/tarea/me',
    component: Tareas,
    meta: {
      requiresAuth: true
    },
    beforeEnter: (to, from, next) => {
      if (store.state.rol !== 'area') {
        next('/')
      } else {
        next()
      }
    }
  },
  {
    path: '/tarea/:idTarea/ver',
    component: VerTarea,
    props: true,
    meta: {
      requiresAuth: true,
    },
    beforeEnter: (to, from, next) => {
      if (store.state.rol !== 'area') {
        next('/')
      } else {
        next()
      }
    }
  },
  //USUARIO:
  {
    path: '/usuario/nuevo',
    component: RegistrarUser,
    meta: {
      requiresAuth: true
    },
    beforeEnter: (to, from, next) => {
      if (store.state.rol !== 'admin') {
        next('/')
      } else {
        next()
      }
    }
  },
  {
    path: '/usuario/nuevo/hseq',
    component: CargarHSEQ,
    meta: {
      requiresAuth: true
    },
    beforeEnter: (to, from, next) => {
      if (store.state.rol !== 'admin') {
        next('/')
      } else {
        next()
      }
    }
  },
  {
    path: '/usuario/nuevo/area',
    component: CargarArea,
    meta: {
      requiresAuth: true
    },
    beforeEnter: (to, from, next) => {
      if (store.state.rol !== 'admin') {
        next('/')
      } else {
        next()
      }
    }
  },
  {
    path: '/usuario/todos',
    component: Usuarios,
    meta: {
      requiresAuth: true
    },
    beforeEnter: (to, from, next) => {
      if (store.state.rol !== 'admin') {
        next('/')
      } else {
        next()
      }
    }
  },
  {
    path: '/solicitud',
    component: SolicitarUsuario,
    meta: {
      requiresAuth: false,
    }
  },
  {
    path: '/usuario/solicitud',
    component: VerSolicitudes,
    meta: {
      requiresAuth: true
    },
    beforeEnter: (to, from, next) => {
      if (store.state.rol !== 'admin') {
        next('/')
      } else {
        next()
      }
    }
  }
]

Vue.use(Router)

const router = new Router({
  mode: 'history',
  routes
})

router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.requiresAuth) && to.path !== '/login') {
    if (!store.state.isLogged) {
      next('/login')
    } else {
      next()
    }
  } else {
    next()
  }
});

export default router