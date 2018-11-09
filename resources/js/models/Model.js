import { Model as BaseModel } from 'vue-api-query'

export default class Model extends BaseModel {

    // define a base url for a REST API
    baseURL () {
        return window.baseURL || '/api';
    }

    // implement a default request method
    request (config) {
        return this.$http.request(config)
    }
}