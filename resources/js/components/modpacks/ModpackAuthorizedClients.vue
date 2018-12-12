<template>
    <div>
        <div class="card card-default">
            <div class="card-header">Client Access</div>

            <div class="card-body" v-if="all_clients.length > 0">
                <form role="form">
                    <div class="form-group d-flex flex-wrap">
                        <div class="col-md-3 offset-1" v-for="client in all_clients">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" :value="client.id" v-model="form.clients" :id="getCheckID(client)" >
                                <label class="custom-control-label" :for="getCheckID(client)">{{ client.name }}</label>
                            </div>
                            <small class="form-text mb-2 ml-4 mt-0 text-muted">{{ client.token }}</small>
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-1">
                            <button type="submit" class="btn btn-primary"
                                    @click.prevent="syncClients">

                                Save Clients
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card-body text-center text-muted" v-else>
                <svg class="fill-current dim-25 m-3" height="40" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17.4 18H20v2H0v-2h2.6L8 0h4l5.4 18zm-3.2-4H5.8l-1.2 4h10.8l-1.2-4zm-2.4-8H8.2L7 10h6l-1.2-4z"></path>
                </svg>
                <p class="lead">No clients matched the given criteria.</p>
            </div>
        </div>
    </div>
</template>

<script>
    import LauncherClient from "../../models/LauncherClient";
    import AuthorizedClient from "../../models/AuthorizedClient";

    export default {
        props: ['modpack', 'clients'],

        /**
         * The component's data.
         */
        data() {
            return {
                all_clients: [],
                form: new AuthorizedClient({
                    'modpack_id': this.modpack.id,
                    'clients': [],
                }),
            }
        },

        /**
         * The component has been created by Vue.
         */
        created() {
            this.getAllClients();
            this.mapAuthorizedClients();
        },

        methods: {
            /**
             * Get the clients.
             */
            async getAllClients() {
                this.all_clients = await LauncherClient.get();
            },

            /**
             * Map authorized clients on the client list
             */
            mapAuthorizedClients() {
                this.form.clients = _.map(this.clients, 'id');
            },

            /**
             * Sync authorized clients to server
             */
            async syncClients() {
                await this.form.save();
                Bus.$emit('updateModpack');
            },

            /**
             *
             * @param client
             * @returns {string}
             */
            getCheckID(client) {
                return ['client', client.id].join('-');
            },
        }
    }
</script>

<style>
    .fill-current {
        fill: currentColor;
    }

    .dim-25 {
        opacity: 0.25;
    }
</style>
