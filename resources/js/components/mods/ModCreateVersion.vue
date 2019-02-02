<template>
    <div class="card card-default">
        <div class="card-header">Add Version</div>

        <div class="card-body">
            <form role="form">


                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right">Version Package</label>

                    <div class="col-md-6">
                        <div class="custom-file">
                            <input type="file" ref="package" class="custom-file-input" id="package" @change="handleFileInput"  :class="{'is-invalid': errors.has('package')}">
                            <label class="custom-file-label" for="package">{{ packageName }}</label>
                        </div>
                        
                        <span class="invalid-feedback d-block" v-show="errors.has('package')">
                            {{ errors.get('package') }}
                        </span>

                        <small id="passwordHelpBlock" class="form-text text-muted">
                            You must upload a properly formatted zip file. See <a href="https://docs.solder.io/docs/zip-file-structure">the documentation</a> for more information.
                        </small>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right">Version Tag</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control" name="name" v-model="tag" :class="{'is-invalid': errors.has('tag')}">

                        <span class="invalid-feedback" v-show="errors.has('tag')">
                            {{ errors.get('tag') }}
                        </span>
                    </div>
                </div>

                <!-- Update Button -->
                <div class="form-group row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary"
                                @click.prevent="create">
                            Create
                        </button>

                        <span class="invalid-feedback" v-show="errors.has('form')">
                            {{ errors.get('form') }}
                        </span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import solder from "../../solder";
    import ErrorCollection from "../../models/ErrorCollection";

    export default {
        props: ['mod'],

        /**
         * The component's data.
         */
        data() {
            return {
                tag: '',
                package: null,
                errors: new ErrorCollection(),
            }
        },

        computed: {
            packageName: function () {
                return this.package ? this.package.name : 'Choose file';
            }
        },

        methods: {
            /**
             * Create the new build.
             */
            async create() {
                this.errors.forget();

                let form = new FormData();
                let options = { headers: {'Content-Type': 'multipart/form-data'}};

                form.append('tag', this.tag);
                form.append('package', this.package);

                try {
                    await window.axios.post(`${solder.apiBaseUrl}/mods/${this.mod.id}/versions`, form, options);
                    this.resetForm();
                    Bus.$emit('updateMod');
                } catch(error) {
                    this.errors.set(error.response.data.errors);
                }
            },

            handleFileInput() {
                this.package = this.$refs.package.files[0];
            },

            resetForm() {
                this.$refs.package.value = null;
                this.package = null;
                this.tag = null;
            }
        }
    }
</script>