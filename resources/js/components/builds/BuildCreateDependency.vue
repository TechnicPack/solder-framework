<template>
    <div class="card card-default">
        <div class="card-header">Add Mod</div>

        <div class="card-body" v-if="mods.length">
            <form role="form">
                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right">Mod</label>

                    <div class="col-md-6">
                        <select class="custom-select" v-model="modId">
                            <option v-for="mod in mods" :key="mod.id" :value="mod.id">
                                {{ mod.name }}
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Minecraft Version -->
                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right">Version</label>

                    <div class="col-md-6">
                        <select class="custom-select" v-model="dependency.version_id">
                            <option v-for="version in versions" :key="version.id" :value="version.id">
                                {{ version.tag }}
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Button -->
                <div class="form-group row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary"
                                @click.prevent="create"
                                :disabled="dependency.busy">

                            Add
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="card-body text-center text-muted" v-else>
            <svg class="fill-current dim-25 m-3" height="40" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M17.4 18H20v2H0v-2h2.6L8 0h4l5.4 18zm-3.2-4H5.8l-1.2 4h10.8l-1.2-4zm-2.4-8H8.2L7 10h6l-1.2-4z"></path>
            </svg>
            <p class="lead">There are no mods available.</p>
        </div>
    </div>
</template>

<script>
    import Mod from '../../models/Mod';
    import Dependency from "../../models/Dependency";

    export default {
        props: ['build'],

        /**
         * The component's data.
         */
        data() {
            return {
                mods: [],
                versions: [],
                modId: null,
                dependency: new Dependency({}),
            }
        },

        /**
         * Watch for changes.
         */
        watch: {
            build: function(build) {
                this.getMods();
                this.dependency.build_id = build.id;
            },

            modId: function(value) {
                this.getVersions(value);
            }
        },

        methods: {
            /**
             * Get all mods.
             */
            async getMods() {
                this.mods = await Mod.where('not_in_build', this.build.id).get();
            },

            /**
             * Get all mod versions.
             */
            async getVersions(modId) {
                let mod = new Mod({id: modId});
                this.versions = await mod.versions().get();
            },

            /**
             * Create the new build.
             */
            create(e) {
                e.preventDefault();

                var self = this;

                this.dependency.startProcessing();
                this.dependency.save()
                    .then(
                        () => {
                            Bus.$emit('updateBuild');
                            this.dependency.finishProcessing();
                            this.dependency = new Dependency({});
                        },
                        (error) => {
                            self.dependency.setErrors(error.response.data.errors);
                            this.dependency.finishProcessing();
                        }
                    );
            }
        }


    }
</script>