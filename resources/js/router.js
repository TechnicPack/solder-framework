import Router from "vue-router";
import Dashboard from './pages/Dashboard'
import Modpack from './pages/Modpack'
import Build from './pages/Build'

export default new Router({
    mode: "history",
    base: '/app',
    routes: [
        { path: '/', redirect: '/dashboard' },
        { path: "/", name: "dashboard", component: Dashboard },
        { path: "/modpack/:modpackId", name: "modpack", component: Modpack, props: true },
        { path: "/modpack/:modpackId/build/:buildId", name: "build", component: Build, props: true },
    ]
});