<template>
    <div>
        <div class="card card-default">
            <div class="card-header">Add Launcher Client</div>

            <div class="card-body">
                <form role="form">
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right">Name</label>

                        <div class="col-md-6">
                            <input type="text" class="form-control" name="name" v-model="client.name" :class="{'is-invalid': client.errors.has('name')}">

                            <span class="invalid-feedback" v-show="client.errors.has('name')">
                                {{ client.errors.get('name') }}
                            </span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right">Client</label>

                        <div class="col-md-6">
                            <input type="text" class="form-control" name="name" v-model="client.token" :class="{'is-invalid': client.errors.has('token')}">

                            <span class="invalid-feedback" v-show="client.errors.has('name')">
                                {{ client.errors.get('token') }}
                            </span>
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary"
                                    @click.prevent="create"
                                    :disabled="client.busy">

                                Create
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card card-default" v-if="clients.length">
            <div class="card-header">
                Current Clients
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
                    <tr v-for="client in clients">
                        <td>
                            {{ client.name }}
                        </td>

                        <td>
                            {{ client.token }}
                        </td>

                        <td>
                            {{ client.created_at }}
                        </td>

                        <!-- Edit Button -->
                        <td class="text-right">
                            <button class="btn btn-sm btn-outline-danger" @click="destroy(client)"
                                data-toggle="tooltip" title="Remove Clients">
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
    import LauncherClient from "../models/LauncherClient";

    export default {
        /**
         * The component's data.
         */
        data() {
            return {
                clients: [],
                client: new LauncherClient({}),
            }
        },

        /**
         * The component has been created by Vue.
         */
        created() {
            this.getClients();
        },

        methods: {
            /**
             * Get the clients.
             */
            async getClients() {
                this.clients = await LauncherClient.get();
            },

            /**
             * Create the new build.
             */
            create(e) {
                e.preventDefault();

                this.client.startProcessing();
                this.client.save()
                    .then(
                        () => {
                            Bus.$emit('updateMod');
                            this.client.finishProcessing();
                            this.client = new LauncherClient({});
                            this.getClients();
                        },
                        (error) => {
                            this.client.setErrors(error.response.data.errors);
                            this.client.finishProcessing();
                        }
                    );
            },

            /**
             * Remove the given client.
             */
            async destroy(client) {
                await client.delete();
                this.getClients();
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