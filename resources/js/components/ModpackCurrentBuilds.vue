<template>
    <div class="card card-default">
        <div class="card-header">Current Builds</div>

        <div class="table-responsive">
            <table class="table table-valign-middle mb-0">
                <thead>
                <th>Tag</th>
                <th>Minecraft</th>
                <th>&nbsp;</th>
                </thead>

                <tbody>
                <tr  v-for="build in builds">
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

        methods: {
            buildUrl(build) {
                return '/modpacks/' + this.modpack.id + '/builds/' + build.id;
            }
        }
    }
</script>

<style scoped>

</style>