import { Model } from 'vue-api-query';
import Vue from "vue";
import Router from "vue-router";
import Dashboard from './pages/Dashboard'
import Modpack from './pages/Modpack'
import Build from './pages/ModpackBuild'

let _apiBaseUrl = '/api'

let router = new Router({
    mode: "history",
    base: '/app',
    routes: [
        { path: "/", name: "dashboard", component: Dashboard },
        { path: "/modpack/:modpackId", name: "modpack", component: Modpack, props: true },
        { path: "/modpack/:modpackId/build/:buildId", name: "build", component: Build, props: true },
    ]
})

export default class Solder {
    static get apiBaseUrl() { return _apiBaseUrl }
    static set apiBaseUrl(value) { _apiBaseUrl = value }
    static get router() { return router }

    static init() {
        Vue.use(Router);
        Model.$http = window.axios;
        window.Bus = new Vue({name: 'Bus'});

        Vue.component('solder-app', require('./components/App'));
        Vue.component('solder-nav-modpacks', require('./components/NavModpacks'));
    }
}