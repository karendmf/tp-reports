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

//TAREA:
import Tareas from '@/components/Tarea/Tareas'
import VerTarea from '@/components/Tarea/VerTarea'

//USUARIO:
import RegistrarUser from '@/components/Usuario/RegistrarUser'
import SolicitarUsuario from '@/components/Usuario/SolicitarUsuario'
import CargarArea from '@/components/Usuario/CargarArea'
import CargarHSEQ from '@/components/Usuario/CargarHSEQ'

//SESION
import Login from '@/components/Sesion/Login'
import Logout from '@/components/Sesion/Logout'

//COMMON:
import Landing from '@/components/common/Landing'

/** Definición de rutas */
const routes = [{
  //COMMON:
    path: '/',
    component: Landing,
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
    path: '/informes',
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
    path: '/informes/cargar',
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
    path: '/informes/:idInforme/ver',
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
  //TAREA:
  {
    path: '/tareas',
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
    path: '/tareas/:idTarea/ver',
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
    path: '/solicitar',
    component: SolicitarUsuario,
    meta: {
      requiresAuth: false,
    }
  },
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