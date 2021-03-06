import Router from 'vue-router';
import Dashboard from './views/Dashboard'
import Modpack from './views/Modpack'
import Build from './views/Build'
import Mod from './views/Mod'
import Version from './views/Version'

export default new Router({
    mode: 'history',
    base: '/app',
    routes: [
        { path: '/', name: 'dashboard', component: Dashboard },
        { path: '/modpack/:modpackId', name: 'modpack', component: Modpack, props: true },
        { path: '/modpack/:modpackId/build/:buildId', name: 'build', component: Build, props: true },
        { path: '/mod/:modId', name: 'mod', component: Mod, props: true },
        { path: '/mod/:modId/version/:versionId', name: 'version', component: Version, props: true },
    ]
});