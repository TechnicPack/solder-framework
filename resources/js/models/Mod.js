import Model from './Model'
import Version from "./Version";

export default class Mod extends Model {
    resource() {
        return 'mods'
    }

    versions() {
        return this.hasMany(Version);
    }
}