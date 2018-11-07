import Vue from 'vue'
import Router from 'vue-router'
import {
  store
} from '@/store'

const routerOptions = [{
    path: '/',
    component: 'Landing',
    meta: {
      requiresAuth: true,
    }
  },
  {
    path: '/login',
    component: 'Login',
    meta: {
      requiresAuth: false,
    }
  },
  {
    path: '/informes',
    component: 'Informes',
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
    path: '/tareas',
    component: 'Tareas',
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
    path: '/logout',
    component: 'Logout',
    meta: {
      requiresAuth: true
    }
  },
  {
    path: '/informes/cargar',
    component: 'CargarInforme',
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
    path: '/usuario/nuevo',
    component: 'RegistrarUser',
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
    component: 'CargarHSEQ',
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
    component: 'CargarArea',
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
    component: 'SolicitarUsuario',
    meta: {
      requiresAuth: false,
    }
  },
  {
    path: '/informes/:idInforme/ver',
    component: 'VerInforme',
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
    path: '/tareas/:idTarea/ver',
    component: 'VerTarea',
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
]

const routes = routerOptions.map(route => {
  return {
    ...route,
    component: () => import(`@/components/${route.component}.vue`)
  }
})

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