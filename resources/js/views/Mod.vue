<template>
    <div class="container">
        <div class="row mb-3">
            <div class="col-md-3">
                <h4>Mod Details</h4>
            </div>
            <div class="col d-md-flex justify-content-md-end text-uppercase">
                <div class="ml-md-4">{{ mod.name }}</div>
                <!--<div class="ml-md-4"><strong>{{ mod.versions.length }}</strong> Builds</div>-->
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
                    <button title="Delete Server" class="btn btn-sm btn-secondary">
                        Delete
                    </button>
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