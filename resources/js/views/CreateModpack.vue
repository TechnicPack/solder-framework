<template>
    <div>
        <h2>Create Modpack</h2>

        <div class="card mb-4">
            <div class="card-body">

                <div class="form-group row">
                    <label for="name" class="col-sm-2 col-form-label">Name</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="name"
                               v-model="modpack.name"
                        >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="slug" class="col-sm-2 col-form-label">Slug</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="slug"
                               v-model="modpack.slug"
                        >
                    </div>
                </div>

                <div class="form-user row">
                    <div class="offset-sm-2 col-sm-10">
                        <button type="submit" class="btn btn-primary" @click="create(false)">Create & Add Another</button>
                        <button type="submit" class="btn btn-primary" @click="create(true)">Create Modpack</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
    import Modpack from '../models/Modpack'

    export default {
        data() {
            return {
                modpack: new Modpack({}),
            }
        },

        methods: {
            async create(redirect = true) {
                await this.modpack.save();

                if(redirect) {
                    this.$router.push({name: 'modpack.show', params: {modpackId: this.modpack.id}})
                }

                this.modpack = new Modpack({});
            }
        }
    }
</script>