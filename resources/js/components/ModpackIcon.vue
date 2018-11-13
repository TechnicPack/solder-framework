<template>
    <div class="card card-default">
        <div class="card-header">Modpack Icon</div>

        <div class="card-body">
            <div class="alert alert-danger" v-if="errors.has('icon')">
                {{ errors.get('icon') }}
            </div>

            <form role="form">
                <div class="form-group row justify-content-center">
                    <div class="col-md-6 d-flex align-items-center">
                        <div class="mr-4">
                            <span role="img" class="icon-preview" :style="previewStyle"></span>
                        </div>
                        <div class="uploader mr-4">
                            <input ref="icon" type="file" class="uploader-control" name="icon" @change="update" :disabled="form.busy">
                            <div class="btn btn-outline-dark">Update Icon</div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import ErrorCollection from '../models/ErrorCollection'
    import Solder from '../solder'

    export default {
        props: ['modpack'],

        data() {
            return {
                busy: false,
                form: new FormData({}),
                errors: new ErrorCollection,
            }
        },

        methods: {
            /**
             * Update the modpack's icon.
             */
            update(e) {
                e.preventDefault();

                if (!this.$refs.icon.files.length) {
                    return;
                }

                this.busy = true;
                this.errors.forget();

                var self = this;

                // We need to gather a fresh FormData instance with the profile photo appended to
                // the data so we can POST it up to the server. This will allow us to do async
                // uploads of the profile photos. We will update the user after this action.
                axios.post(`${Solder.apiBaseUrl}/modpacks/${this.modpack.id}/icon`, this.gatherFormData())
                    .then(() => {
                            Bus.$emit('updateModpack');
                            self.busy = false;
                        },
                        (error) => {
                            self.busy = false;
                            self.errors.set(error.response.data.errors);
                        }
                    );
            },


            /**
             * Gather the form data for the photo upload.
             */
            gatherFormData() {
                const data = new FormData();

                data.append('icon', this.$refs.icon.files[0]);

                return data;
            }
        },

        computed: {
            /**
             * Calculate the style attribute for the photo preview.
             */
            previewStyle() {
                return `background-image: url(${this.modpack.icon})`;
            }
        }
    }
</script>

<style scoped>
    .icon-preview {
        display: inline-block;
        background-position: center;
        background-size: cover;
        height: 50px;
        width: 50px;
    }

    .uploader {
        position: relative;
        border-radius: 0.25rem;
        cursor: pointer !important;
    }

    .uploader-control {
        position: absolute;
        top: 0;
        bottom: 0;
        max-width: 100%;
        opacity: 0;
        z-index: 99;
    }
</style>