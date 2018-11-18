<template>
    <div class="card card-default">
        <div class="card-header">Mod Settings</div>

        <div class="card-body">
            <!-- Success Message -->
            <div class="alert alert-success" v-if="successful">
                Mod has been updated!
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

                <!-- ModID -->
                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right">ModID</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control" name="modid" v-model="form.modid" :class="{'is-invalid': form.errors.has('modid')}">

                        <span class="invalid-feedback" v-show="form.errors.has('modid')">
                            {{ form.errors.get('modid') }}
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
    import Mod from '../../models/Mod'

    export default {
        props: ['mod'],


        /**
         * The component's data.
         */
        data() {
            return {
                form: new Mod({}),
                successful: false,
            }
        },


        /**
         * Watch for changes.
         */
        watch: {
            mod: function() {
                this.form = new Mod(this.mod)
            }
        },


        methods: {
            /**
             * Update the mod's data.
             */
            update(e) {
                e.preventDefault();

                var self = this;

                this.form.startProcessing();
                this.successful = false;
                this.form.save()
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