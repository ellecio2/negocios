import { createRouter, createWebHistory } from "vue-router";

import Articles from "./components/Articles.vue";

const routes = [{ path: "/articles", component: Articles }];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
