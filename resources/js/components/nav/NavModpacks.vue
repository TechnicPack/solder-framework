<template>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="modpack-dropdown-menu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Modpacks
        </a>
        <div class="dropdown-menu" aria-labelledby="modpack-dropdown-menu">
            <router-link class="dropdown-item"
                         v-for="modpack in modpacks"
                         :key="modpack.id"
                         :to="{ name: 'modpack', params: { modpackId: modpack.id }}"
            >
                {{ modpack.name }}
            </router-link>
        </div>
    </li>
</template>

<script>
    import Modpack from '../../models/Modpack'

    export default {

       /**
        * The component's data.
        */
        data() {
            return {
                modpacks: [],
            }
        },


        /**
         * The component has been created by Vue.
         */
        created() {
            var self = this;

            this.getModpacks();

            Bus.$on('updateModpack', function () {
                self.getModpacks();
            });
        },


        methods: {
            /**
             * Get all modpacks.
             */
            async getModpacks() {
                this.modpacks = await Modpack.get();
            }
        }
    }
</script>
