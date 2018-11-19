<template>
    <div class="container">
        <div class="row mb-3">
            <div class="col-md-3">
                <h4>Version Details</h4>
            </div>
            <div class="col d-md-flex justify-content-md-end text-uppercase">
                <div class="ml-md-4">
                    <router-link :to="{ name: 'mod', params: { modId: mod.id }}">
                        {{ mod.name }}
                    </router-link>
                </div>
                <div class="ml-md-4">{{ version.tag }}</div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3 solder-tabs">
                <aside>
                    <ul class="nav flex-column mb-4 ">
                        <li class="nav-item ">
                            <a class="nav-link active" href="#files" aria-controls="files" role="tab" data-toggle="tab">
                                <svg class="icon-20 " viewBox="0 0 20 16 " xmlns="http://www.w3.org/2000/svg">
                                    <path d="M16 14v2H4v-2H0V2h4V0h12v2h4v12h-4zM14 3.5V2H6v12h8V3.5zm2 .5v8h2V4h-2zM4 4H2v8h2V4z"></path>
                                </svg>
                                Files
                            </a>
                        </li>

                        <li class="nav-item ">
                            <a class="nav-link" href="#settings" aria-controls="settings" role="tab" data-toggle="tab">
                                <svg viewBox="0 0 20 20 " xmlns="http://www.w3.org/2000/svg" class="icon-20 ">
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
                    <!-- Files -->
                    <div role="tabcard" class="tab-pane active" id="files">
                        Files
                    </div>

                    <!-- Settings -->
                    <div role="tabcard" class="tab-pane" id="settings">
                        <version-settings :mod="mod" :version="version"></version-settings>
                    </div>
                </div>

                <div class="d-md-flex align-items-center mt-4">
                    <div class="mr-auto">
                        <strong>Mod ID:</strong> {{ mod.id }}
                        <span>,</span>
                        <strong>Version ID:</strong> {{ version.id }}
                    </div>
                    <button title="Delete Version" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#deleteVersion">
                        Delete
                    </button>
                </div>
            </div>

        </div>

        <div class="modal fade" id="deleteVersion" tabindex="-1" role="dialog" aria-labelledby="deleteVersionLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteVersionLabel">Delete?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        This will remove the version tagged <code>{{ version.tag }}</code> from <strong>{{ mod.name }}</strong>. Are you sture you want to continue?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-outline-danger" @click="deleteVersion" >Delete Version</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import Mod from '../models/Mod';
    import Version from '../models/Version';
    import VersionSettings from '../components/versions/VersionSettings';

    export default {
        props: ['modId', 'versionId'],


        components: {
            VersionSettings,
        },


        /**
         * The component's data.
         */
        data() {
            return {
                mod: new Mod({}),
                version: new Version({}),
            }
        },


        /**
         * The component has been created by Vue.
         */
        created() {
            var self = this;

            this.getVersion();

            Bus.$on('updateVersion', function () {
                self.getVersion();
            });
        },


        /**
         * Watch for changes.
         */
        watch: {
            versionId: function() {
                this.getVersion();
            }
        },

        methods: {
            /**
             * Get the version.
             */
            async getVersion() {
                this.mod = await Mod.find(this.modId);
                this.version = await this.mod.versions().find(this.versionId);
            },

            /**
             * Delete the version.
             */
            async deleteVersion() {
                await this.version.for(this.mod).delete();
                Bus.$emit('updateMod');
                $('#deleteVersion').modal('hide');
                this.$router.push({ name: 'mod', params: { modId: this.mod.id }});
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