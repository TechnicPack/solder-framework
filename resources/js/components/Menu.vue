<template>
    <div class="solder-menu">
        <nav class="nav nav-primary bg-dark d-flex flex-column">
            <a class="nav-link w-brand h-brand d-flex align-items-center mb-2 active" href="#">
                <svg class="w-100 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path d="M16 16v2H4v-2H0V4h4V2h12v2h4v12h-4zM14 5.5V4H6v12h8V5.5zm2 .5v8h2V6h-2zM4 6H2v8h2V6z"></path>
                </svg>
            </a>

            <a class="nav-link w-brand h-brand d-flex align-items-center mb-2" href="#">
                <svg class="w-100 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path d="M6 4H5a1 1 0 1 1 0-2h11V1a1 1 0 0 0-1-1H4a2 2 0 0 0-2 2v16c0 1.1.9 2 2 2h12a2 2 0 0 0 2-2V5a1 1 0 0 0-1-1h-7v8l-2-2-2 2V4z"></path>
                </svg>
            </a>

            <a class="nav-link w-brand h-brand d-flex align-items-center mb-2" href="#">
                <svg class="w-100 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path d="M17 16v4h-2v-4h-2v-3h6v3h-2zM1 9h6v3H1V9zm6-4h6v3H7V5zM3 0h2v8H3V0zm12 0h2v12h-2V0zM9 0h2v4H9V0zM3 12h2v8H3v-8zm6-4h2v12H9V8z"></path>
                </svg>
            </a>
        </nav>
        <div class="flex-1 bg-secondary">
            <nav class="nav nav-secondary flex-column my-3 mx-2">
                <h6 class="nav-header d-flex justify-content-between align-items-center">
                    <span>Modpacks</span>
                    <router-link
                        data-toggle="tooltip" data-placement="top" title="Create Modpack"
                        :to="{ name: 'modpack.create' }"
                    >
                        <svg class="fill-current mr-2" width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M11 9h4v2h-4v4H9v-4H5V9h4V5h2v4zm-1 11C4.477 20 0 15.523 0 10S4.477 0 10 0s10 4.477 10 10-4.477 10-10 10zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16z" fill-rule="nonzero"/></svg>
                    </router-link>
                </h6>

                <router-link
                    v-for="modpack in modpacks"
                    :key="modpack.id"
                    :to="{ name: 'modpack.show', params: { modpackId: modpack.id }}"
                    class="nav-link d-flex align-items-center"
                    active-class="active"
                >
                    <img :src="modpack.icon" class="mr-2" width="30" />
                    <span class="h5 mb-0 mr-2">{{ modpack.name }}</span>
                </router-link>
            </nav>
        </div>
    </div>
</template>

<script>
    export default {
        name: "solder-menu",

       /*
        * The component's data.
        */
        data() {
            return {
                modpacks: []
            }
        },


        /**
         * Prepare the component (Vue 2.x).
         */
        mounted() {
            this.prepareComponent();
        },


        methods: {
            /**
             * Prepare the component.
             */
            prepareComponent() {
                this.getModpacks();
            },

            /**
             * Get all of the OAuth clients for the user.
             */
            getModpacks() {
                axios.get(`${this.$hostname}/modpacks`)
                    .then(response => {
                        this.modpacks = response.data;
                    });
            },
        }
    }
</script>

<style>
    .solder-menu {
        height: 100%;
        display: flex;
        flex: 1;
    }
</style>

<style scoped>
    .nav-primary .nav-link:hover, .nav-primary .active {
        background-color: #6c757d;
    }

    .nav-primary a {
        color: #6c757d;
    }

    .nav-primary a.active {
        color: #fff;
    }

    .nav-primary a:hover {
        color: rgba(255, 255, 255, .65);
    }

    .nav-secondary, .nav-secondary a {
        color: rgba(255, 255, 255, .65);
    }

    .nav-secondary a:hover, .nav-secondary .active {
        color: #fff;
    }

    .nav-secondary .active:after {
        content: '\2192';
    }

    .nav-header {
        padding: 0.5rem;
        margin-bottom: 0;
    }
</style>