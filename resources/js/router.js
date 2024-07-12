import { createRouter, createWebHistory, useRouter } from 'vue-router'

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
    {
      path: '/add_todo',
      name: 'AddTodo',
      component: () => import('./src/components/Todos/AddTodo.vue'),
    },
    {
      path: '/edit_todo/',
      name: 'EditTodo',
      component: () => import('./src/components/Todos/EditTodo.vue'),
    },
  ]
})

const router1 = useRouter()

router.beforeEach((to, from, next) => {


  if ((to.name !== 'Login' && to.name !== 'Signup') && !localStorage.getItem('auth_token')) next({ name: 'Login' })
  else next()

  if (to.name == 'Login' && localStorage.getItem('auth_token')) {
    router.push({ name: 'Todos' })
  }
})


export default router
