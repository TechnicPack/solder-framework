<template>
    <div>
        <div class="card card-default">
            <div class="card-header">Add Technic Key</div>

            <div class="card-body">
                <form role="form">
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right">Name</label>

                        <div class="col-md-6">
                            <input type="text" class="form-control" name="name" v-model="key.name" :class="{'is-invalid': key.errors.has('name')}">

                            <span class="invalid-feedback" v-show="key.errors.has('name')">
                                {{ key.errors.get('name') }}
                            </span>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right">Key</label>

                        <div class="col-md-6">
                            <input type="text" class="form-control" name="name" v-model="key.token" :class="{'is-invalid': key.errors.has('token')}">

                            <span class="invalid-feedback" v-show="key.errors.has('name')">
                                {{ key.errors.get('token') }}
                            </span>
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary"
                                    @click.prevent="create"
                                    :disabled="key.busy">

                                Create
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card card-default" v-if="keys.length">
            <div class="card-header">
                Current Keys
            </div>

            <div class="table-responsive">
                <table class="table table-valign-middle mb-0">
                    <thead>
                    <th width="20%">Name</th>
                    <th>Token</th>
                    <th width="30%">Created</th>
                    <th width="10%">&nbsp;</th>
                    </thead>

                    <tbody>
                    <tr v-for="key in keys">
                        <td>
                            {{ key.name }}
                        </td>

                        <td>
                            {{ key.token }}
                        </td>

                        <td>
                            {{ key.created_at }}
                        </td>

                        <!-- Edit Button -->
                        <td class="text-right">
                            <button class="btn btn-sm btn-outline-danger" @click="destroy(key)"
                                data-toggle="tooltip" title="Remove Keys">
                                <i class="fa fa-times"></i>
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    import TechnicKey from "../models/TechnicKey";

    export default {
        /**
         * The component's data.
         */
        data() {
            return {
                keys: [],
                key: new TechnicKey({}),
                successful: false,
            }
        },

        /**
         * The component has been created by Vue.
         */
        created() {
            this.getKeys();
        },

        methods: {
            /**
             * Get the keys.
             */
            async getKeys() {
                this.keys = await TechnicKey.get();
            },

            /**
             * Create the new build.
             */
            create(e) {
                e.preventDefault();

                this.key.startProcessing();
                this.successful = false;
                this.key.save()
                    .then(
                        () => {
                            Bus.$emit('updateMod');
                            this.key.finishProcessing();
                            this.key = new TechnicKey({});
                            this.successful = true;
                            this.getKeys();
                        },
                        (error) => {
                            this.key.setErrors(error.response.data.errors);
                            this.key.finishProcessing();
                        }
                    );
            },

            /**
             * Remove the given key.
             */
            async destroy(key) {
                await key.delete();
                this.getKeys();
            }
        }

    }
</script>

<style scoped>
    table {
        table-layout: fixed;
    }

    td {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>