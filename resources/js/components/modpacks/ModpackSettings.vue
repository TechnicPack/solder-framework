<template>
    <div class="card card-default">
        <div class="card-header">Modpack Settings</div>

        <div class="card-body">
            <!-- Success Message -->
            <div class="alert alert-success" v-if="successful">
                Modpack settings have been updated!
            </div>

            <form role="form">
                <!-- Name -->
                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right">Name</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control" name="name" v-model="form.name" :class="{'is-invalid': form.errors.has('name')}">

                        <span class="invalid-feedback" v-show="form.errors.has('name')">
                            {{ form.errors.get('name') }}
                        </span>
                    </div>
                </div>

                <!-- Slug -->
                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right">Slug</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control" name="slug" v-model="form.slug" :class="{'is-invalid': form.errors.has('slug')}">

                        <span class="invalid-feedback" v-show="form.errors.has('slug')">
                            {{ form.errors.get('slug') }}
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

    export default {
        props: ['modpack'],


       /**
        * The component's data.
        */
        data() {
            return {
                form: new Modpack({}),
                successful: false,
            }
        },


        /**
         * Watch for changes.
         */
        watch: {
            modpack: function(query) {
                this.form = new Modpack(this.modpack)
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
                this.form.save()
                    .then(
                        () => {
                            Bus.$emit('updateModpack');
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
