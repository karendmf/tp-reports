import Vue from 'vue' 
import Router from 'vue-router' 
 
const routerOptions = [ 
  { path: '/', component: 'Landing' }, 
  { path: '/login', component: 'Login' },
  { path: '/informes', component: 'Informes' },
  { path: '/logout', component: 'Logout' },
  { path: '/informes/cargar', component: 'CargarInforme' },
  { path: '/usuario/nuevo', component: 'RegistrarUser' },
  { path: '/usuario/nuevo/hseq', component: 'CargarHSEQ' },
  { path: '/usuario/nuevo/area', component: 'CargarArea' },
  { path: '/solicitar', component: 'SolicitarUsuario' },
  { path: '/img', component: 'uploadImg' },
] 
 
const routes = routerOptions.map(route => { 
  return { 
    ...route, 
    component: () => import(`@/components/${route.component}.vue`) 
  } 
}) 
 
Vue.use(Router) 
 
export default new Router({ 
  mode: 'history', 
  routes 
})

/* router.beforeEach((to, from, next) => {
  if(to.matched.some(record => record.meta.requiresAuth)) {
    if (store.getters.isLoggedIn) {
      next()
      return
    }
    next('/login') 
  } else {
    next() 
  }
}) */