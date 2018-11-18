<template>
    <div class="card card-default">
        <div class="card-header">Add Version</div>

        <div class="card-body">
            <form role="form">
                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right">Version Tag</label>

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
    import Version from '../../models/Version'

    export default {
        props: ['mod'],

        /**
         * The component's data.
         */
        data() {
            return {
                form: new Version({}),
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
                this.form.for(this.mod).save()
                    .then(
                        () => {
                            Bus.$emit('updateMod');
                            this.form.finishProcessing();
                            this.form = new Version({});
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