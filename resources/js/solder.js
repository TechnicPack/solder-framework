import { Model } from 'vue-api-query';
import Vue from 'vue';
import Router from 'vue-router';
import SolderRouter from './router';

/**
 * Application Variables
 */
let _apiBaseUrl = '/api';

export default class Solder {
    static get apiBaseUrl() { return _apiBaseUrl }
    static set apiBaseUrl(value) { _apiBaseUrl = value }
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
        Vue.use(Router);
        Model.$http = window.axios;
    }

    /**
     * Register Global Components.
     */
    static registerGlobalComponents() {
        Vue.component('solder-app', require('./App'));
        Vue.component('solder-nav-modpacks', require('./components/nav/NavModpacks'));
    }
}