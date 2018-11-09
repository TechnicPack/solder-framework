import Vue from "vue";
import Router from "vue-router";
import Home from "./views/Home.vue";
import Modpack from "./views/Modpack.vue";
import CreateModpack from "./views/CreateModpack.vue";

Vue.use(Router);

export default new Router({
    mode: "history",
    // TODO: pull this base url from the Solder static class ...
    base: "app",
    routes: [
        { path: "/", name: "home", component: Home },
        { path: "/modpack/create", name: "modpack.create", component: CreateModpack },
        { path: "/modpack/:modpackId", name: "modpack.show", component: Modpack, props: true }
    ]
})