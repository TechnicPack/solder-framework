<template>
    <div class="card card-default">
        <div class="card-header">
            <div class="d-flex align-items-center justify-content-between">
            <span>Current Builds</span>
            <div class="input-group input-group-sm w-25">
                <input class="form-control py-2 border-right-0 border shadow-none" type="search" value="search" v-model="search">
                <span class="input-group-append">
                    <div class="input-group-text bg-white border"><i class="fa fa-search"></i></div>
                </span>
            </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-valign-middle mb-0">
                <thead>
                <th>Tag</th>
                <th>Minecraft</th>
                <th>&nbsp;</th>
                </thead>

                <tbody>
                <tr  v-for="build in filteredBuilds">
                    <td>
                        <a :href="buildUrl(build)">{{ build.tag }}</a>
                    </td>
                    <td>
                        {{ build.minecraft_version }}
                    </td>

                    <!-- Edit Button -->
                    <td class="td-fit">
                        <!--<a :href="'/settings/{{Spark::teamsPrefix()}}/'+team.id">-->
                            <!--<button class="btn btn-outline-primary">-->
                                <!--<i class="fa fa-cog"></i>-->
                            <!--</button>-->
                        <!--</a>-->

                        <!--<button class="btn btn-outline-warning" @click="approveLeavingTeam(team)"-->
                                <!--data-toggle="tooltip" title="{{__('teams.leave_team')}}"-->
                                <!--v-if="user.id !== team.owner_id">-->
                            <!--<i class="fa fa-sign-out"></i>-->
                        <!--</button>-->
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['modpack', 'builds'],

        data() {
            return {
                search: '',
            }
        },

        computed: {
            filteredBuilds() {
                var self=this;
                return this.builds.filter(function(build) {
                    return build.tag.toLowerCase().indexOf(self.search.toLowerCase())>=0;
                });
            }
        },

        methods: {
            buildUrl(build) {
                return '/modpacks/' + this.modpack.id + '/builds/' + build.id;
            }
        }
    }
</script>

<style scoped>

</style>
