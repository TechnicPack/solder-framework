<template>
    <div class="card card-default">
        <div class="card-header">Build Settings</div>

        <div class="card-body">
            <!-- Success Message -->
            <div class="alert alert-success" v-if="successful">
                Build settings have been updated!
            </div>

            <form role="form">
                <!-- Tag -->
                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right">Tag</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control" name="name" v-model="form.tag" :class="{'is-invalid': form.errors.has('tag')}">

                        <span class="invalid-feedback" v-show="form.errors.has('tag')">
                            {{ form.errors.get('tag') }}
                        </span>
                    </div>
                </div>

                <!-- Minecraft Version -->
                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right">Minecraft Version</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control" name="slug" v-model="form.minecraft_version" :class="{'is-invalid': form.errors.has('minecraft_version')}">

                        <span class="invalid-feedback" v-show="form.errors.has('minecraft_version')">
                            {{ form.errors.get('minecraft_version') }}
                        </span>
                    </div>
                </div>

                <!-- Java Version -->
                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right">Java Version</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control" name="slug" v-model="form.java_version" :class="{'is-invalid': form.errors.has('java_version')}">

                        <span class="invalid-feedback" v-show="form.errors.has('java_version')">
                            {{ form.errors.get('java_version') }}
                        </span>
                    </div>
                </div>

                <!-- Memory -->
                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right">Memory</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control" name="slug" v-model="form.java_memory" :class="{'is-invalid': form.errors.has('java_memory')}">

                        <span class="invalid-feedback" v-show="form.errors.has('java_memory')">
                            {{ form.errors.get('java_memory') }}
                        </span>
                    </div>
                </div>

                <!-- Update Button -->
                <div class="form-group row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary"
                                @click.prevent="update"
                                :disabled="form.busy">

                            Update
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import Modpack from '../../models/Modpack'
    import Build from '../../models/Build'

    export default {
        props: ['modpack', 'build'],


       /**
        * The component's data.
        */
        data() {
            return {
                form: new Build({}),
                successful: false,
            }
        },


        /**
         * Watch for changes.
         */
        watch: {
            build: function(query) {
                this.form = new Build(this.build)
            }
        },


        methods: {
            /**
             * Update the modpack's icon.
             */
            update(e) {
                e.preventDefault();

                var self = this;

                this.form.startProcessing();
                this.successful = false;
                this.form.for(this.modpack).save()
                    .then(
                        () => {
                            Bus.$emit('updateBuild');
                            this.form.finishProcessing();
                            this.successful = true;
                        },
                        (error) => {
                            self.form.setErrors(error.response.data.errors);
                            this.form.finishProcessing();
                        }
                    );
            }
        }
    }
</script>