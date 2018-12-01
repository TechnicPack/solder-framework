import Model from './Model'
import Build from './Build'

export default class Modpack extends Model {
    resource() {
        return 'modpacks'
    }

    builds() {
        return this.hasMany(Build);
    }
}
