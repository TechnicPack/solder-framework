<template>
    <div class="container">
        <div class="row mb-3">
            <div class="col-md-3">
                <h4>Modpack Details</h4>
            </div>
            <div class="col d-md-flex justify-content-md-end text-uppercase">
                <div class="ml-md-4">{{ modpack.name }}</div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3 solder-tabs">
                <aside>
                    <ul class="nav flex-column mb-4 ">
                        <li class="nav-item ">
                            <a class="nav-link active" href="#builds" aria-controls="builds" role="tab" data-toggle="tab">
                                <svg class="icon-20 " viewBox="0 0 20 16 " xmlns="http://www.w3.org/2000/svg ">
                                    <path d="M16 14v2H4v-2H0V2h4V0h12v2h4v12h-4zM14 3.5V2H6v12h8V3.5zm2 .5v8h2V4h-2zM4 4H2v8h2V4z" />
                                </svg>
                                Builds
                            </a>
                        </li>

                        <li class="nav-item ">
                            <a class="nav-link" href="#meta" aria-controls="meta" role="tab" data-toggle="tab">
                                <svg class="icon-20 " viewBox="0 0 20 20 " xmlns="http://www.w3.org/2000/svg ">
                                    <path d="M0 10V2l2-2h8l10 10-10 10L0 10zm4.5-4a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z" />
                                </svg>
                                Meta
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
                        <modpack-current-builds :modpack="modpack"></modpack-current-builds>
                    </div>

                    <!-- Meta -->
                    <div role="tabcard" class="tab-pane" id="meta">
                        <modpack-icon :modpack="modpack"></modpack-icon>
                        <modpack-metadata :modpack="modpack"></modpack-metadata>
                    </div>
                </div>

                <div class="d-md-flex align-items-center mt-4">
                    <div class="mr-auto">
                        <strong>ID:</strong> {{ modpack.id }}
                        <span>,</span>
                        <strong>Slug:</strong> {{ modpack.slug }}
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
    import Modpack from '../models/Modpack'
    import ModpackCreateBuild from '../components/ModpackCreateBuild'
    import ModpackCurrentBuilds from '../components/ModpackCurrentBuilds'
    import ModpackIcon from '../components/ModpackIcon'
    import ModpackMetadata from '../components/ModpackMetadata'

    export default {
        props: ['modpackId'],

        components: {
            ModpackCreateBuild,
            ModpackCurrentBuilds,
            ModpackIcon,
            ModpackMetadata,
        },


       /**
        * The component's data.
        */
        data() {
            return {
                modpack: [],
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
            modpackId: function(query) {
                this.getModpack();
            }
        },


        methods: {
            /**
             * Get the modpack.
             */
            async getModpack() {
                this.modpack = await Modpack.find(this.modpackId);
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