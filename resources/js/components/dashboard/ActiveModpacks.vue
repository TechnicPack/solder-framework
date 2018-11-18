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

        <div class="table-responsive">
            <table class="table table-valign-middle mb-0">
                <thead>
                <th>Name</th>
                <th>Builds</th>
                <th>&nbsp;</th>
                </thead>

                <tbody>
                <tr  v-for="modpack in filteredModpacks">
                    <td>
                        <router-link
                                :to="{ name: 'modpack', params: { modpackId: modpack.id }}"
                        >
                            {{ modpack.name }}
                        </router-link>
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