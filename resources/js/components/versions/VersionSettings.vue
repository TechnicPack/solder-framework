<template>
    <div class="card card-default">
        <div class="card-header">Version Settings</div>

        <div class="card-body">
            <!-- Success Message -->
            <div class="alert alert-success" v-if="successful">
                Version settings have been updated!
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
    import Version from '../../models/Version'

    export default {
        props: ['mod', 'version'],


       /**
        * The component's data.
        */
        data() {
            return {
                form: new Version({}),
                successful: false,
            }
        },


        /**
         * Watch for changes.
         */
        watch: {
            build: function() {
                this.form = new Version(this.version)
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
                this.form.for(this.mod).save()
                    .then(
                        () => {
                            Bus.$emit('updateMod');
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