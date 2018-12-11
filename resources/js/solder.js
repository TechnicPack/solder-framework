import { Model } from 'vue-api-query';
import Router from 'vue-router';
import SolderRouter from './router';

/**
 * Application Variables
 */
let _apiBaseUrl = '/api';
let _vue = null;

export default class Solder {
    static get apiBaseUrl() { return _apiBaseUrl }
    static set apiBaseUrl(value) { _apiBaseUrl = value }
    static set vue(value) { _vue = value }
    static get router() { return SolderRouter }

    /**
     * Initialize Solder.
     */
    static init() {
        this.createEventBus();
        this.configureIntegrations();
        this.registerGlobalComponents();
    }

    /**
     * Create a global event bus.
     */
    static createEventBus() {
        window.Bus = new Vue({name: 'Bus'});
    }

    /**
     * Configure third party integrations.
     */
    static configureIntegrations() {
        _vue.use(Router);
        Model.$http = window.axios;
    }

    /**
     * Register Global Components.
     */
    static registerGlobalComponents() {
        _vue.component('solder-app', require('./App'));
        _vue.component('solder-nav-modpacks', require('./components/nav/NavModpacks'));
        _vue.component('solder-nav-mods', require('./components/nav/NavMods'));
        _vue.component('solder-keys', require('./components/Keys'));
        _vue.component('solder-clients', require('./components/Clients'));
    }
}