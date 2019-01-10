import Model from './Model'
import Package from './Package'

export default class Version extends Model {
    resource() {
        return 'versions'
    }

    package() {
        return this.hasMany(Package)
    }
}