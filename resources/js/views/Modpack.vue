<template>
    <div class="container">
        <div class="row mb-3">
            <div class="col-md-3">
                <h4>Modpack Details</h4>
            </div>
            <div class="col d-md-flex justify-content-md-end text-uppercase">
                <div class="ml-md-4">{{ modpack.name }}</div>
                <div class="ml-md-4">{{ modpack.visibility }}</div>
                <div class="ml-md-4"><strong>{{ modpack.builds.length }}</strong> Builds</div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3 solder-tabs">
                <aside>
                    <ul class="nav flex-column mb-4 ">
                        <li class="nav-item ">
                            <a class="nav-link active" href="#builds" aria-controls="builds" role="tab" data-toggle="tab">
                                <svg class="icon-20 " viewBox="0 0 20 20     " xmlns="http://www.w3.org/2000/svg ">
                                    <path d="M0 10V2l2-2h8l10 10-10 10L0 10zm4.5-4a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z" />
                                </svg>
                                Builds
                            </a>
                        </li>

                        <li class="nav-item ">
                            <a class="nav-link" href="#visibility" aria-controls="visibility" role="tab" data-toggle="tab">
                                <svg class="icon-20 " viewBox="0 0 20 20     " xmlns="http://www.w3.org/2000/svg ">
                                    <path d="M.2 10a11 11 0 0 1 19.6 0A11 11 0 0 1 .2 10zm9.8 4a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm0-2a2 2 0 1 1 0-4 2 2 0 0 1 0 4z"/>
                                </svg>
                                Visibility
                            </a>
                        </li>

                        <li class="nav-item ">
                            <a class="nav-link" href="#settings" aria-controls="settings" role="tab" data-toggle="tab">
                                <svg viewBox="0 0 20 20 " xmlns="http://www.w3.org/2000/svg " class="icon-20 ">
                                    <path d="M3.94 6.5L2.22 3.64l1.42-1.42L6.5 3.94c.52-.3 1.1-.54 1.7-.7L9 0h2l.8 3.24c.6.16 1.18.4 1.7.7l2.86-1.72 1.42 1.42-1.72 2.86c.3.52.54 1.1.7 1.7L20 9v2l-3.24.8c-.16.6-.4 1.18-.7 1.7l1.72 2.86-1.42 1.42-2.86-1.72c-.52.3-1.1.54-1.7.7L11 20H9l-.8-3.24c-.6-.16-1.18-.4-1.7-.7l-2.86 1.72-1.42-1.42 1.72-2.86c-.3-.52-.54-1.1-.7-1.7L0 11V9l3.24-.8c.16-.6.4-1.18.7-1.7zM10 13a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                </svg>
                                Settings
                            </a>
                        </li>
                    </ul>
                </aside>
            </div>

            <!-- Tab cards -->
            <div class="col-md-9">
                <div class="tab-content">
                    <!-- Builds -->
                    <div role="tabcard" class="tab-pane active" id="builds">
                        <modpack-create-build :modpack="modpack"></modpack-create-build>
                        <modpack-current-builds :modpack="modpack" :builds="modpack.builds"></modpack-current-builds>
                    </div>

                    <!-- Visibility -->
                    <div role="tabcard" class="tab-pane" id="visibility">
                        <modpack-visibility :modpack="modpack"></modpack-visibility>
                        <modpack-authorized-clients v-if="modpack.visibility === 'private'" :modpack="modpack" :clients="modpack.clients"></modpack-authorized-clients>
                    </div>

                    <!-- Settings -->
                    <div role="tabcard" class="tab-pane" id="settings">
                        <modpack-icon :modpack="modpack"></modpack-icon>
                        <modpack-settings :modpack="modpack"></modpack-settings>
                    </div>
                </div>

                <div class="d-md-flex align-items-center mt-4">
                    <div class="mr-auto">
                        <strong>ID:</strong> {{ modpack.id }}
                        <span>,</span>
                        <strong>Slug:</strong> {{ modpack.slug }}
                    </div>
                    <button title="Delete Server" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#deleteModpack">
                        Delete
                    </button>
                </div>
            </div>

        </div>

        <div class="modal fade" id="deleteModpack" tabindex="-1" role="dialog" aria-labelledby="deleteModpackLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModpackLabel">Delete?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        This will remove the modpack named <code>{{ modpack.name }}</code> and <strong>{{ modpack.builds.length }}</strong> builds under it. Are you sture you want to continue?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-outline-danger" @click="deleteModpack" >Delete Mod</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Modpack from '../models/Modpack'
    import ModpackAuthorizedClients from '../components/modpacks/ModpackAuthorizedClients'
    import ModpackCreateBuild from '../components/modpacks/ModpackCreateBuild'
    import ModpackCurrentBuilds from '../components/modpacks/ModpackCurrentBuilds'
    import ModpackVisibility from '../components/modpacks/ModpackVisibility'
    import ModpackIcon from '../components/modpacks/ModpackIcon'
    import ModpackSettings from '../components/modpacks/ModpackSettings'

    export default {
        props: ['modpackId'],

        components: {
            ModpackAuthorizedClients,
            ModpackCreateBuild,
            ModpackCurrentBuilds,
            ModpackVisibility,
            ModpackIcon,
            ModpackSettings,
        },


       /**
        * The component's data.
        */
        data() {
            return {
                modpack: {
                    builds: [],
                    clients: [],
                }
            }
        },


        /**
         * The component has been created by Vue.
         */
        created() {
            var self = this;

            this.getModpack();

            Bus.$on('updateModpack', function () {
                self.getModpack();
            });
        },


        /**
         * Watch for changes.
         */
        watch: {
            modpackId: function() {
                this.getModpack();
            }
        },


        methods: {
            /**
             * Get the modpack.
             */
            async getModpack() {
                this.modpack = await Modpack
                    .include('builds', 'latest', 'recommended', 'clients')
                    .find(this.modpackId);
            },

            /**
             * Delete the modpack.
             */
            async deleteModpack() {
                await this.modpack.delete();
                Bus.$emit('updateModpack');
                $('#deleteModpack').modal('hide');
                this.$router.push({ name: 'dashboard'});
            }
        }
    }
</script>

<style>
    .solder-tabs .nav-link {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        padding-top: 5px;
        padding-right: 0;
        padding-bottom: 5px;
        padding-left: 0;
        font-size: 1rem;
        font-weight: 600;
        color: #49545a;
    }

    .solder-tabs .nav-link svg {
        fill: currentColor;
        margin-right: 15px;
        color: #9aa5ac;
        -ms-flex-negative: 0;
        flex-shrink: 0;
    }

    .solder-tabs .nav-link:hover {
        font-weight: 600;
        color: #42a2dc;
    }

    .solder-tabs .nav-link:hover svg {
        fill: #42a2dc;
    }

    .solder-tabs .nav-link.active {
        font-weight: 600;
        color: #42a2dc;
    }

    .solder-tabs .nav-link.active svg {
        fill: #42a2dc;
    }

    .solder-tabs .nav-link {
        padding-bottom: 10px;
    }
</style>