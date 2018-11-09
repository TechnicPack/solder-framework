<template>
    <div class="mb-4">
        <form v-if="editing" @submit.prevent="updateModpack" class="d-flex justify-content-between align-items-center mb-2" style="min-height: 50px;">
            <div class="flex-1 mr-2">
                <label class="sr-only" for="name">Name</label>
                <input type="text" class="form-control mb-2 mr-sm-2" id="name" v-model="updateName">
            </div>

            <div>
                <button type="submit" class="btn btn-primary mb-2">Save</button>
                <button type="reset" @click="editing = false" class="btn btn-link mb-2">Cancel</button>
            </div>
        </form>

        <div v-else class="d-flex justify-content-between align-items-center mb-2">
            <div class="d-flex align-items-center">
                <img :src="modpack.icon" class="mr-2" width="50px" height="50px"/>
                <h2 class="mb-0">{{ modpack.name }}</h2>
            </div>

            <div>
                <button class="btn btn-outline-secondary disabled">Upload Icon</button>
                <button @click="editing = true" class="btn btn-outline-primary">Edit Modpack</button>
            </div>

        </div>

        <div class="card mb-4">
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex">
                        <div class="w-25">Slug</div>
                        <div class="w-75">{{ modpack.slug }}</div>
                    </li>

                    <li class="list-group-item d-flex">
                        <div class="w-25">Created</div>
                        <div class="w-75">{{ modpack.created_at }}</div>
                    </li>

                    <li class="list-group-item d-flex">
                        <div class="w-25">Updated</div>
                        <div class="w-75">{{ modpack.updated_at }}</div>
                    </li>
                </ul>
            </div>
        </div>

        <h3>Danger Zone</h3>
        <div class="card border-danger mb-3">
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between">
                    <div>
                        <h5 class="card-title mb-0">Delete this modpack</h5>
                        <p class="card-text">Once you delete a modpack, there is no going back. Please be certain.</p>
                    </div>
                    <button class="btn btn-outline-danger" @click="deleteModpack()">Delete Modpack</button>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    import Modpack from '../models/Modpack'
    import FileSelect from '../components/FileSelect'

    export default {
        props: ['modpackId'],

        components: {
            FileSelect
        },


       /*
        * The component's data.
        */
        data() {
            return {
                modpack: [],
                updateName: '',
                editing: false,
                icon: null
            }
        },


        /**
         * Prepare the component.
         */
        mounted() {
            this.getModpack();
        },


        /**
         * Watch for changes.
         */
        watch: {
            modpackId: function(query) {
                this.getModpack();
            }
        },


        methods: {
            /**
             * Get the modpack.
             */
            async getModpack() {
                this.modpack = await Modpack.find(this.modpackId);
                this.updateName = this.modpack.name;
            },


            /**
             * Update the modpack.
             */
            async updateModpack() {
                this.modpack.name = this.updateName;
                this.modpack = await this.modpack.save();
                this.editing = false;
            },

            /**
             * Delete the modpack.
             */
            async deleteModpack() {
                this.modpack.delete();
                this.$router.push({name: 'home'})
            }
        }
    }
</script>

<style scoped>
    .file-select > input[type="file"] {
        display: none;
    }
</style>