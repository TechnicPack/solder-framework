<template>
    <div class="container">
        <div class="row mb-3">
            <div class="col-md-3">
                <h4>Build Details</h4>
            </div>
            <div class="col d-md-flex justify-content-md-end text-uppercase">
                <div class="ml-md-4"><a :href="modpackUrl">{{ modpack.name }}</a></div>
                <div class="ml-md-4">{{ build.tag }}</div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3 solder-tabs">
                <aside>
                    <ul class="nav flex-column mb-4 ">
                        <li class="nav-item ">
                            <a class="nav-link active" href="#dependencies" aria-controls="dependencies" role="tab" data-toggle="tab">
                                <svg class="icon-20 " viewBox="0 0 20 16 " xmlns="http://www.w3.org/2000/svg ">
                                    <path d="M16 14v2H4v-2H0V2h4V0h12v2h4v12h-4zM14 3.5V2H6v12h8V3.5zm2 .5v8h2V4h-2zM4 4H2v8h2V4z" />
                                </svg>
                                Mods
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
                    <!-- Dependencies -->
                    <div role="tabcard" class="tab-pane active" id="dependencies">
                        Dependencies
                    </div>

                    <!-- Settings -->
                    <div role="tabcard" class="tab-pane" id="settings">
                        <build-settings :modpack="modpack" :build="build"></build-settings>
                    </div>
                </div>

                <div class="d-md-flex align-items-center mt-4">
                    <div class="mr-auto">
                        <strong>Modpack ID:</strong> {{ modpack.id }}
                        <span>,</span>
                        <strong>Build ID:</strong> {{ build.id }}
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
    import Modpack from "../models/Modpack";
    import Build from "../models/Build";
    import BuildSettings from "../components/BuildSettings";

    export default {
        props: ['modpackId', 'buildId'],


        components: {
            BuildSettings,
        },


        /**
         * The component's data.
         */
        data() {
            return {
                modpack: new Modpack({}),
                build: new Build({}),
            }
        },


        /**
         * The component has been created by Vue.
         */
        created() {
            var self = this;

            this.getBuild();

            Bus.$on('updateBuild', function () {
                self.getBuild();
            });
        },


        /**
         * Watch for changes.
         */
        watch: {
            buildId: function() {
                this.getBuild();
            }
        },


        computed: {
            modpackUrl: function() {
                return '/modpacks/' + this.modpackId;
            }
        },


        methods: {
            /**
             * Get the build.
             */
            async getBuild() {
                this.modpack = await Modpack.find(this.modpackId);
                this.build = await this.modpack.builds().find(this.buildId);
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