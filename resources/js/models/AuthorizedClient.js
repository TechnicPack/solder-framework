import Model from './Model'

export default class AuthorizedClient extends Model {
    resource() {
        return 'authorized-clients'
    }
}