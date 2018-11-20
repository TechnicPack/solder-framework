<template>
    <div class="card card-default">
        <div class="card-header">Add Mod</div>

        <div class="card-body">
            <form role="form">
                <!-- Name -->
                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right">Name</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control" name="name" v-model="mod.name" :class="{'is-invalid': mod.errors.has('name')}">

                        <span class="invalid-feedback" v-show="mod.errors.has('name')">
                            {{ mod.errors.get('name') }}
                        </span>
                    </div>
                </div>

                <!-- Mod ID -->
                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right">Mod ID</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control" name="modid" v-model="mod.modid" :class="{'is-invalid': mod.errors.has('modid')}">

                        <span class="invalid-feedback" v-show="mod.errors.has('modid')">
                            {{ mod.errors.get('modid') }}
                        </span>
                    </div>
                </div>

                <!-- Author -->
                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right">Author</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control" name="author" v-model="mod.author" :class="{'is-invalid': mod.errors.has('author')}">

                        <span class="invalid-feedback" v-show="mod.errors.has('author')">
                            {{ mod.errors.get('author') }}
                        </span>
                    </div>
                </div>

                <!-- URL -->
                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right">URL</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control" name="modid" v-model="mod.url" :class="{'is-invalid': mod.errors.has('url')}">

                        <span class="invalid-feedback" v-show="mod.errors.has('url')">
                            {{ mod.errors.get('url') }}
                        </span>
                    </div>
                </div>

                <!-- Description -->
                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right">Description</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control" name="description" v-model="mod.description" :class="{'is-invalid': mod.errors.has('description')}">

                        <span class="invalid-feedback" v-show="mod.errors.has('description')">
                            {{ mod.errors.get('description') }}
                        </span>
                    </div>
                </div>

                <!-- Create Button -->
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
                mod: new Mod({
                    name: '',
                    modid: '',
                }),
            }
        },

        watch: {
            /**
             * Watch the name for changes.
             */
            'mod.name': function (val, oldVal) {
                if (this.mod.modid === '' || this.mod.modid === oldVal.toLowerCase().replace(/[\s\W-]+/g, '')) {
                    this.mod.modid = val.toLowerCase().replace(/[\s\W-]+/g, '');
                }
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