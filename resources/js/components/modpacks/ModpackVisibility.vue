<template>
    <div class="card card-default">
        <div class="card-header">Modpack Visibility</div>

        <div class="card-body">
            <p>Modpack visibility settings only apply to integration with TechnicPack.net and still require that you
                link the modpack with your TechnicPack.net account.</p>

            <div class="list-group">
                <button @click="setVisibility('hidden')" class="list-group-item list-group-item-action flex-column align-items-start" :class="{ active: modpack.visibility === 'hidden' }">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1"><i class="fa fa-fw fa-eye-slash"></i> Hidden</h5>
                    </div>
                    <p class="mb-1">Hidden modpacks will not show up the modpack list regardless of whether or not a client has access to the modpack.</p>
                </button>

                <button @click="setVisibility('private')" class="list-group-item list-group-item-action flex-column align-items-start" :class="{ active: modpack.visibility === 'private' }">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1"><i class="fa fa-fw fa-low-vision"></i> Private</h5>
                    </div>
                    <p class="mb-1">Private modpacks will only be available to clients that are linked to this modpack. You can link clients below. You can also individually mark builds as private.</p>
                </button>

                <button @click="setVisibility('public')" class="list-group-item list-group-item-action flex-column align-items-start" :class="{ active: modpack.visibility === 'public' }">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1"><i class="fa fa-fw fa-eye"></i> Public</h5>
                    </div>
                    <p class="mb-1">Public modpacks can be seen by and shared with anyone using the Technic Launcher.</p>
                </button>
            </div>

        </div>
    </div>
</template>

<script>
    import Modpack from "../../models/Modpack";

    export default {
        props: ['modpack'],

        methods: {
            /**
             * Update the modpack's visibility.
             */
            async setVisibility(visibility) {
                let modpack = new Modpack(this.modpack);
                modpack.visibility = visibility;

                await modpack.save();
                Bus.$emit('updateModpack');
            }
        }
    }
</script>
