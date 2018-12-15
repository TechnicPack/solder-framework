<template>
    <div class="card card-default">
        <div class="card-header">Build Visibility</div>

        <div class="card-body">
            <form role="form">
                <div class="row">
                    <div class="col-md-9 offset-1">
                        <div class="custom-control custom-radio">
                            <input @click="setVisibility('hidden')" type="radio" id="customRadio1" name="customRadio" class="custom-control-input" :checked="build.visibility === 'hidden'">
                            <label class="custom-control-label" for="customRadio1">Hidden</label>
                        </div>

                        <small class="form-text mb-2 ml-4 mt-0 text-muted">
                            Hidden builds will not show up the build list regardless of whether or not a client has access to the modpack.
                        </small>

                        <div class="custom-control custom-radio">
                            <input @click="setVisibility('private')" type="radio" id="customRadio2" name="customRadio" class="custom-control-input" :checked="build.visibility === 'private'">
                            <label class="custom-control-label" for="customRadio2">Private</label>
                        </div>

                        <small class="form-text mb-2 ml-4 mt-0 text-muted">
                            Private builds will only be available to clients that are linked to this modpack. You can link clients from the modpack page.
                        </small>

                        <div class="custom-control custom-radio">
                            <input @click="setVisibility('public')" type="radio" id="customRadio3" name="customRadio" class="custom-control-input" :checked="build.visibility === 'public'">
                            <label class="custom-control-label" for="customRadio3">Public</label>
                        </div>

                        <small class="form-text mb-2 ml-4 mt-0 text-muted">
                            Public builds can be seen by and shared with anyone using the Technic Launcher.
                        </small>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import Build from "../../models/Build";
    import Visibility from "../../models/Visibility";

    export default {
        props: ['build'],

        methods: {
            /**
             * Update the build's visibility.
             */
             async setVisibility(value) {
                let build = new Build(this.build);
                let visibility = new Visibility({
                    visibility: value,
                }).for(build);

                await visibility.save();
                Bus.$emit('updateBuild');
            }
        }
    }
</script>
