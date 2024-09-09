import Vue from 'vue';
import Router from 'vue-router';
import LoginPage from './components/Login/Login.vue';
import CallbackPage from './components/Login/Callback.vue';
import AppPage from './components/App/App.vue';
import store from './store'

Vue.use(Router);

const router = new Router({
  mode: 'history', // Utilisez le mode 'history' pour de meilleures URLs
  routes: [
    {
      path: '/login',
      name: 'Login',
      component: LoginPage
    },
    {
      path: '/callback',
      name: 'Callback',
      component: CallbackPage
    },
    {
      path: '/app',
      name: 'App',
      component: AppPage,
      meta: { requiresAuth: true }
    },
    {
      path: '*',
      redirect: '/login' // Redirection par défaut
    }
  ]
});

// Gérer l'accès aux pages nécessitant une authentification
router.beforeEach((to, from, next) => {
  const isAuthenticated = store.getters.isAuthenticated; // Simple exemple de vérification
  console.log("no auth :", isAuthenticated);
  
  // if (to.matched.some(record => record.meta.requiresAuth) && !isAuthenticated) {
    // next({ name: 'Login' });
  // } else {
    next();
  // }
});

export default router;
