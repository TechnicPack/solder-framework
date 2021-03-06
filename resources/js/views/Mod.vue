<template>
    <div class="container">
        <div class="row mb-3">
            <div class="col-md-3">
                <h4>Mod Details</h4>
            </div>
            <div class="col d-md-flex justify-content-md-end text-uppercase">
                <div class="ml-md-4">{{ mod.name }}</div>
                <!--<div class="ml-md-4"><strong>{{ mod.versions.length }}</strong> Builds</div>-->
                <div v-if="mod.url" class="ml-md-4">
                    <a :href="mod.url" target="_blank">
                        <svg class="icon-20 fill-current" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 10c0 5.523-4.477 10-10 10S0 15.523 0 10 4.477 0 10 0s10 4.477 10 10zm-2 0a8 8 0 1 0-16 0 8 8 0 0 0 16 0zm-8 2H5V8h5V5l5 5-5 5v-3z"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3 solder-tabs">
                <aside>
                    <ul class="nav flex-column mb-4">
                        <li class="nav-item ">
                            <a class="nav-link active" href="#versions" aria-controls="versions" role="tab" data-toggle="tab">
                                <svg class="icon-20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10 0l10 6-10 6L0 6l10-6zm6.67 10L20 12l-10 6-10-6 3.33-2L10 14l6.67-4z"></path>
                                </svg>
                                Versions
                            </a>
                        </li>

                        <li class="nav-item ">
                            <a class="nav-link" href="#settings" aria-controls="settings" role="tab" data-toggle="tab">
                                <svg viewBox="0 0 20 20 " xmlns="http://www.w3.org/2000/svg" class="icon-20 ">
                                    <path d="M3.94 6.5L2.22 3.64l1.42-1.42L6.5 3.94c.52-.3 1.1-.54 1.7-.7L9 0h2l.8 3.24c.6.16 1.18.4 1.7.7l2.86-1.72 1.42 1.42-1.72 2.86c.3.52.54 1.1.7 1.7L20 9v2l-3.24.8c-.16.6-.4 1.18-.7 1.7l1.72 2.86-1.42 1.42-2.86-1.72c-.52.3-1.1.54-1.7.7L11 20H9l-.8-3.24c-.6-.16-1.18-.4-1.7-.7l-2.86 1.72-1.42-1.42 1.72-2.86c-.3-.52-.54-1.1-.7-1.7L0 11V9l3.24-.8c.16-.6.4-1.18.7-1.7zM10 13a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"></path>
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
                    <!-- Versions -->
                    <div role="tabcard" class="tab-pane active" id="versions">
                        <mod-create-version :mod="mod"></mod-create-version>
                        <mod-current-versions :mod="mod" :versions="mod.versions"></mod-current-versions>
                    </div>

                    <!-- Settings -->
                    <div role="tabcard" class="tab-pane" id="settings">
                        <mod-settings :mod="mod"></mod-settings>
                    </div>
                </div>

                <div class="d-md-flex align-items-center mt-4">
                    <div class="mr-auto">
                        <strong>ID:</strong> {{ mod.id }}
                        <span>,</span>
                        <strong>ModID:</strong> {{ mod.modid }}
                    </div>
                    <button title="Delete Server" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#deleteMod">
                        Delete
                    </button>
                </div>
            </div>

        </div>

        <div class="modal fade" id="deleteMod" tabindex="-1" role="dialog" aria-labelledby="deleteModLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModLabel">Delete?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        This will remove the mod named <code>{{ mod.name }}</code> and <strong>{{ mod.versions.length }}</strong> versions under it. Are you sture you want to continue?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-outline-danger" @click="deleteMod" >Delete Mod</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Mod from '../models/Mod'
    import ModCreateVersion from '../components/mods/ModCreateVersion'
    import ModCurrentVersions from '../components/mods/ModCurrentVersions'
    import ModSettings from '../components/mods/ModSettings'

    export default {
        props: ['modId'],

        components: {
            ModCreateVersion,
            ModCurrentVersions,
            ModSettings,
        },


       /**
        * The component's data.
        */
        data() {
            return {
                mod: {
                    versions: []
                }
            }
        },


        /**
         * The component has been created by Vue.
         */
        created() {
            var self = this;

            this.getMod();

            Bus.$on('updateMod', function () {
                self.getMod();
            });
        },


        /**
         * Watch for changes.
         */
        watch: {
            modId: function() {
                this.getMod();
            }
        },


        methods: {
            /**
             * Get the mod.
             */
            async getMod() {
                this.mod = await Mod.include('versions').find(this.modId);
            },

            /**
             * Delete the mod.
             */
            async deleteMod() {
                await this.mod.delete();
                Bus.$emit('updateMod');
                $('#deleteMod').modal('hide');
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

    .fill-current {
        fill: currentColor;
    }
</style>