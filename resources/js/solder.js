let _apiBaseUrl = '/api'

export default class Solder {
    static get apiBaseUrl() { return _apiBaseUrl }
    static set apiBaseUrl(value) { _apiBaseUrl = value }
}