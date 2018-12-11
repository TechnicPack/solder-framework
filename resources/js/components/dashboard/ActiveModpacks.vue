<template>
    <div class="card card-default">
        <div class="card-header">
            <div class="d-flex align-items-center justify-content-between">
            <span>Active Modpacks</span>
            <div class="input-group input-group-sm w-25">
                <input class="form-control py-2 border-right-0 border shadow-none" type="search" value="search" v-model="search">
                <span class="input-group-append">
                    <div class="input-group-text bg-white border"><i class="fa fa-search"></i></div>
                </span>
            </div>
            </div>
        </div>

        <div class="table-responsive" v-if="filteredModpacks.length">
            <table class="table table-valign-middle mb-0">
                <thead>
                <th>&nbsp;</th>
                <th>Name</th>
                <th>Visibility</th>
                <th>Builds</th>
                <th>&nbsp;</th>
                </thead>

                <tbody>
                <tr  v-for="modpack in filteredModpacks">
                    <td class="td-fit pr-0">
                        <img :src="modpack.icon" class="icon-50" />
                    </td>
                    <td>
                        <router-link
                                :to="{ name: 'modpack', params: { modpackId: modpack.id }}"
                        >
                            {{ modpack.name }}
                        </router-link>
                    </td>
                    <td>
                        {{ modpack.visibility }}
                    </td>
                    <td>
                        {{ modpack.builds.length }}
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
            <svg class="fill-current dim-25 m-3" height="40" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M17.4 18H20v2H0v-2h2.6L8 0h4l5.4 18zm-3.2-4H5.8l-1.2 4h10.8l-1.2-4zm-2.4-8H8.2L7 10h6l-1.2-4z"></path>
            </svg>
            <p class="lead">No modpack matched the given criteria.</p>
        </div>
    </div>
</template>

<script>
    import Modpack from '../../models/Modpack'

    export default {

        /**
         * The component's data.
         */
        data() {
            return {
                search: '',
                modpacks: [],
            }
        },


        /**
         * The component has been created by Vue.
         */
        created() {
            var self = this;

            this.getModpacks();

            Bus.$on('updateModpack', function () {
                self.getModpacks();
            });
        },

        computed: {
            filteredModpacks() {
                var self=this;
                return this.modpacks.filter(function(modpack) {
                    return modpack.name.toLowerCase().indexOf(self.search.toLowerCase())>=0;
                });
            }
        },


        methods: {
            /**
             * Get all modpacks.
             */
            async getModpacks() {
                this.modpacks = await Modpack.include('builds').get();
            }
        }
    }
</script>

<style>
    .icon-50{
        height: 50px;
        width: 50px;
    }

     .fill-current {
         fill: currentColor;
     }

    .dim-25 {
        opacity: 0.25;
    }
</style>