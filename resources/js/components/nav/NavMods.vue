<template>
    <li class="nav-item dropdown px-2">
        <a class="nav-link dropdown-toggle" href="#" id="modpack-dropdown-menu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Mods
        </a>
        <div class="dropdown-menu" aria-labelledby="modpack-dropdown-menu">
            <router-link class="dropdown-item"
                         v-for="mod in mods"
                         :key="mod.id"
                         :to="{ name: 'mod', params: { modId: mod.id }}"
            >
                {{ mod.name }}
            </router-link>
        </div>
    </li>
</template>

<script>
    import Mod from '../../models/Mod'

    export default {

       /**
        * The component's data.
        */
        data() {
            return {
                mods: [],
            }
        },


        /**
         * The component has been created by Vue.
         */
        created() {
            var self = this;

            this.getMods();

            Bus.$on('updateMod', function () {
                self.getMods();
            });
        },


        methods: {
            /**
             * Get all modpacks.
             */
            async getMods() {
                this.mods = await Mod.get();
            }
        }
    }
</script>
