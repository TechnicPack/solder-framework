import { Model as BaseModel } from 'vue-api-query'
import ErrorCollection from './ErrorCollection'
import Solder from '../solder';

export default class Model extends BaseModel {
    /**
     * Construct a new Model instance.
     */
    constructor(...atributtes) {
        super(...atributtes);

        this.busy = false;
        this.errors = new ErrorCollection;
    }

    // define a base url for a REST API
    baseURL () {
        return Solder.apiBaseUrl;
    }

    // implement a default request method
    request (config) {
        return this.$http.request(config)
    }

    /**
     * Start processing the model.
     */
    startProcessing() {
        this.errors.forget();
        this.busy = true;
    };

    /**
     * Finish processing the model.
     */
    finishProcessing() {
        this.busy = false;
    };

    /**
     * Reset the errors and other state for the model.
     */
    resetStatus() {
        this.errors.forget();
        this.busy = false;
    };


    /**
     * Set the errors on the model.
     */
    setErrors(errors) {
        this.busy = false;
        this.errors.set(errors);
    };
}