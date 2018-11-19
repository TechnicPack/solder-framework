<template>
    <div class="card card-default">
        <div class="card-header">Add Modpack</div>

        <div class="card-body">
            <form role="form">
                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right">Name</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control" name="name" v-model="modpack.name" :class="{'is-invalid': modpack.errors.has('name')}">

                        <span class="invalid-feedback" v-show="modpack.errors.has('name')">
                            {{ modpack.errors.get('name') }}
                        </span>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right">Slug</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control" name="slug" v-model="modpack.slug" :class="{'is-invalid': modpack.errors.has('slug')}">

                        <span class="invalid-feedback" v-show="modpack.errors.has('slug')">
                            {{ modpack.errors.get('slug') }}
                        </span>
                    </div>
                </div>

                <!-- Update Button -->
                <div class="form-group row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary"
                                @click.prevent="create"
                                :disabled="modpack.busy">

                            Create
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
        /**
         * The component's data.
         */
        data() {
            return {
                modpack: new Modpack({}),
            }
        },

        methods: {
            /**
             * Create the new build.
             */
            create(e) {
                e.preventDefault();

                this.modpack
                    .startProcessing()
                    .save()
                    .then(
                        () => {
                            Bus.$emit('updateModpack');
                            this.modpack.finishProcessing();
                            this.modpack = new Modpack({});
                        },
                        (error) => {
                            this.modpack
                                .setErrors(error.response.data.errors)
                                .finishProcessing();
                        }
                    );
            }
        }
    }
</script>