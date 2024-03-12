import { createRouter, createWebHistory } from 'vue-router';
import Post from "./components/Post.vue";
import Create from "./components/Create.vue";
import Login from './pages/Login.vue';
import Register from './pages/Register.vue';
import store from './store';
import List from './components/List.vue';
import Editor from './components/Editor.vue';
console.log('Router is loaded');
const routes = [
    {
        path: '/post/:id',
        name: 'post',
        component: Post,
        props: true
    },
    {
        path: '/post/create',
        name: 'create',
        component: Create,
        props: true,
        meta: { requiresAuth: true },
    },
    {
        path: '/post/:id/edit',
        name: 'edit',
        component: Editor,
        props: true,
        meta: { requiresAuth: true },
    },
    {
        path: '/',
        name: 'posts',
        component: List
    },
    {
        path: '/login',
        name: 'login',
        component: Login
    },
    {
        path: '/register',
        name: 'register',
        component: Register,
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

// Navigation guard to check authentication
router.beforeEach((to, from, next) => {
    const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
  
    if (requiresAuth && !store.getters.isAuthenticated) {
      // If the route requires authentication and the user is not authenticated,
      // redirect to the login page or any other page you choose
      next('/login');
    } else {
      // Otherwise, proceed to the requested route
      next();
    }
  });

export default router;