import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'Login',
      component: () => import('./src/components/Auth/Login.vue')
    },
    {
      path: '/signup',
      name: 'Signup',
      component: () => import('./src/components/Auth/Signup.vue')
    },
    {
      path: '/todos',
      name: 'Todos',
      component: () => import('./src/components/Todos/Todos.vue'),
    },
  ]
})


router.beforeEach((to, from, next) => {

  if ((to.name !== 'Login' && to.name !== 'Signup') && !localStorage.getItem('auth_token')) next({ name: 'Login' })
  else next()
})

export default router
