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

                <!-- Mod ID -->
                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right">ModID</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control" name="modid" v-model="form.modid" :class="{'is-invalid': form.errors.has('modid')}">

                        <span class="invalid-feedback" v-show="form.errors.has('modid')">
                            {{ form.errors.get('modid') }}
                        </span>
                    </div>
                </div>

                <!-- Author -->
                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right">Author</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control" name="author" v-model="form.author" :class="{'is-invalid': form.errors.has('author')}">

                        <span class="invalid-feedback" v-show="form.errors.has('author')">
                            {{ form.errors.get('author') }}
                        </span>
                    </div>
                </div>

                <!-- URL -->
                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right">URL</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control" name="modid" v-model="form.url" :class="{'is-invalid': form.errors.has('url')}">

                        <span class="invalid-feedback" v-show="form.errors.has('url')">
                            {{ form.errors.get('url') }}
                        </span>
                    </div>
                </div>

                <!-- Description -->
                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right">Description</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control" name="description" v-model="form.description" :class="{'is-invalid': form.errors.has('description')}">

                        <span class="invalid-feedback" v-show="form.errors.has('description')">
                            {{ form.errors.get('description') }}
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


                this.successful = false;
                this.form
                    .startProcessing()
                    .save()
                    .then(
                        () => {
                            Bus.$emit('updateMod');
                            this.form.finishProcessing();
                            this.successful = true;
                        },
                        (error) => {
                            this.form
                                .setErrors(error.response.data.errors)
                                .finishProcessing();
                        }
                    );
            }
        }
    }
</script>