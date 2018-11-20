<template>
    <div class="card card-default">
        <div class="card-header">Add Build</div>

        <div class="card-body">
            <form role="form">
                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right">Build Tag</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control" name="name" v-model="form.tag" :class="{'is-invalid': form.errors.has('tag')}">

                        <span class="invalid-feedback" v-show="form.errors.has('tag')">
                            {{ form.errors.get('tag') }}
                        </span>

                        <small class="form-text text-muted" v-show="!form.errors.has('tag')">
                            Enter a unique identifier for this build (aka version or name)
                        </small>
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

                <!-- Update Button -->
                <div class="form-group row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary"
                                @click.prevent="create"
                                :disabled="form.busy">

                            Create
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import Build from '../../models/Build'

    export default {
        props: ['modpack'],

        /**
         * The component's data.
         */
        data() {
            return {
                form: new Build({}),
                successful: false,
            }
        },

        methods: {
            /**
             * Create the new build.
             */
            create(e) {
                e.preventDefault();

                var self = this;

                this.form.startProcessing();
                this.successful = false;
                this.form.for(this.modpack).save()
                    .then(
                        () => {
                            Bus.$emit('updateModpack');
                            this.form.finishProcessing();
                            this.form = new Build({});
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
