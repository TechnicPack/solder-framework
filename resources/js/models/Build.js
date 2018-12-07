import Model from './Model'
import Dependency from "./Dependency";

export default class Build extends Model {
    resource() {
        return 'builds'
    }

    dependencies() {
        return this.hasMany(Dependency)
    }
}