<template>
    <div class="card card-default">
        <div class="card-header">Add Mod</div>

        <div class="card-body">
            <form role="form">
                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right">Name</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control" name="name" v-model="mod.name" :class="{'is-invalid': mod.errors.has('name')}">

                        <span class="invalid-feedback" v-show="mod.errors.has('name')">
                            {{ mod.errors.get('name') }}
                        </span>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right">ModId</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control" name="modid" v-model="mod.modid" :class="{'is-invalid': mod.errors.has('modid')}">

                        <span class="invalid-feedback" v-show="mod.errors.has('modid')">
                            {{ mod.errors.get('modid') }}
                        </span>
                    </div>
                </div>

                <!-- Update Button -->
                <div class="form-group row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary"
                                @click.prevent="create"
                                :disabled="mod.busy">

                            Create
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
        /**
         * The component's data.
         */
        data() {
            return {
                mod: new Mod({}),
            }
        },

        methods: {
            /**
             * Create the new build.
             */
            create(e) {
                e.preventDefault();

                this.mod
                    .startProcessing()
                    .save()
                    .then(
                        () => {
                            Bus.$emit('updateMod');
                            this.mod.finishProcessing();
                            this.mod = new Mod({});
                        },
                        (error) => {
                            this.mod
                                .setErrors(error.response.data.errors)
                                .finishProcessing();
                        }
                    );
            }
        }
    }
</script>