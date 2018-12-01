import Build from './Build'

export default class LatestBuild extends Build {
    resource() {
        return 'builds/latest'
    }
}
