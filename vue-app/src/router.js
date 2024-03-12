import { createRouter, createWebHistory } from 'vue-router';
import Post from './components/Post.vue';

const routes = [
    {
        path: '/post/:id',
        name: 'post',
        component: Post,
        props: true
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;