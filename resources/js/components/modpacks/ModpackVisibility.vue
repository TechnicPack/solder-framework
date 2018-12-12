<template>
    <div class="card card-default">
        <div class="card-header">Modpack Visibility</div>

        <div class="card-body">
            <form role="form">
                <div class="row">
                    <div class="col-md-9 offset-1">
                        <div class="custom-control custom-radio">
                            <input @click="setVisibility('hidden')" type="radio" id="customRadio1" name="customRadio" class="custom-control-input" :checked="modpack.visibility === 'hidden'">
                            <label class="custom-control-label" for="customRadio1">Hidden</label>
                        </div>

                        <small class="form-text mb-2 ml-4 mt-0 text-muted">
                            Hidden modpacks will not show up the modpack list regardless of whether or not a client has access to the modpack.
                        </small>

                        <div class="custom-control custom-radio">
                            <input @click="setVisibility('private')" type="radio" id="customRadio2" name="customRadio" class="custom-control-input" :checked="modpack.visibility === 'private'">
                            <label class="custom-control-label" for="customRadio2">Private</label>
                        </div>

                        <small class="form-text mb-2 ml-4 mt-0 text-muted">
                            Private modpacks will only be available to clients that are linked to this modpack. You can link clients below. You can also individually mark builds as private.
                        </small>

                        <div class="custom-control custom-radio">
                            <input @click="setVisibility('public')" type="radio" id="customRadio3" name="customRadio" class="custom-control-input" :checked="modpack.visibility === 'public'">
                            <label class="custom-control-label" for="customRadio3">Public</label>
                        </div>

                        <small class="form-text mb-2 ml-4 mt-0 text-muted">
                            Public modpacks can be seen by and shared with anyone using the Technic Launcher.
                        </small>
                    </div>
                </div>
            </form>
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
