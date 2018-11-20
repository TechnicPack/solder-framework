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

        <div class="table-responsive" v-if="filteredBuilds.length">
            <table class="table table-valign-middle mb-0">
                <thead>
                <th>Tag</th>
                <th>Minecraft</th>
                <th>&nbsp;</th>
                </thead>

                <tbody>
                <tr  v-for="build in filteredBuilds">
                    <td>
                        <router-link
                                :to="{ name: 'build', params: { modpackId: modpack.id, buildId: build.id }}"
                        >
                            {{ build.tag }}
                        </router-link>
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

        <div class="card-body text-center text-muted" v-else>
            <svg class="fill-current dim m-3" height="40" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M17.4 18H20v2H0v-2h2.6L8 0h4l5.4 18zm-3.2-4H5.8l-1.2 4h10.8l-1.2-4zm-2.4-8H8.2L7 10h6l-1.2-4z"></path>
            </svg>
            <p class="lead">No build matched the given criteria.</p>
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
        }
    }
</script>

<style>
    .fill-current {
        fill: currentColor;
    }

    .dim {
        opacity: 0.25;
    }
</style>
