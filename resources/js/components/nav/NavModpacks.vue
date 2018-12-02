<template>
    <li class="nav-item dropdown px-2">
        <a class="nav-link dropdown-toggle" href="#" id="modpack-dropdown-menu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Modpacks
        </a>
        <div class="dropdown-menu" aria-labelledby="modpack-dropdown-menu">
            <a class="dropdown-item"
                         v-for="modpack in modpacks"
                         :key="modpack.id"
                         :href="getModpackUrl(modpack.id)"
            >
                <img :src="modpack.icon" class="icon-xs"><i class="fa fa-btn"></i>
                {{ modpack.name }}
            </a>

            <h6 class="dropdown-header" v-if="modpacks.length === 0">No Modpacks</h6>
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
            },

            getModpackUrl(modpackId) {
                let resolved = this.$router.resolve({
                    name: 'modpack',
                    params: { modpackId: modpackId }
                });

                return resolved.href;
            }
        }
    }
</script>

<style>
    .icon-xs {
        display: inline-block;
        height: 20px;
        width: 20px;
    }
</style>
